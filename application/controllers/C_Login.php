<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 */

class C_Login extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index(){
		echo sys_get_temp_dir();
		die();
		if($this->session->userdata('username')){
			redirect("C_Stock");
		} else {
			$data['kategori'] = $this->M_AllFunction->CustomQuery("SELECT id, kategori FROM mst_material_hdr WHERE is_active = 1");
			$data['tanggal_stock'] = $this->M_AllFunction->CustomQuery("SELECT MAX(tanggal_stock) AS tanggal_stock FROM trn_stock_material");
			$this->load->view("v_login", $data);
		}
	}

	public function auth(){
		$username = $this->input->post('username', true);
		$password = $this->input->post('password', true);

		// $secretKey = "6LdbpGAqAAAAALNaaosolvareVwEBJciDkV_9PLp";
		// $token = $this->input->post('token', true);
		// $ip = $_SERVER['REMOTE_ADDR'];
		// $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $token . "&remoteip=" . $ip;
		// $request = file_get_contents($url);
		// $response = json_decode($request);

		$insert = array(
			'username' => $username,
			'local_ip_addr' => $_SERVER['REMOTE_ADDR'],
			'ip_addr' => $_SERVER['SERVER_ADDR'],
			'login_date' => date('Y-m-d H:i:s'),
			'capcha_passed' => 1
		);

		// if($response->success != 1){
		// 	$insert['is_logged_in'] = 0;
		// 	$insert['capcha_passed'] = 0;
		// 	$this->M_AllFunction->Insert('trn_login_log', $insert);
		// 	$this->session->set_flashdata('message', 'Mohon Coba Lagi');
		// 	redirect("C_Login");
		// } else {
		$cek = $this->M_AllFunction->Where('vw_user', "username = '$username'");

		if(count($cek) == 0){
			$insert['is_logged_in'] = 0;
			$this->M_AllFunction->Insert('trn_login_log', $insert);
			$this->session->set_flashdata('message', 'username / password Anda salah');
			redirect('C_Login');
		} else {
			if($cek[0]->is_active == 0){
				$insert['is_logged_in'] = 0;
				$this->M_AllFunction->Insert('trn_login_log', $insert);
				$this->session->set_flashdata('pesan', 'User Telah DiNonAktifkan');
				redirect('C_Login');
			}
			if(password_verify($password, $cek[0]->password)){
				$insert['is_logged_in'] = 1;
				$this->M_AllFunction->Insert('trn_login_log', $insert);

				$this->session->set_userdata('username', $cek[0]->username);
				$this->session->set_userdata('group_name', $cek[0]->group_name);
				$this->session->set_userdata('group_id', $cek[0]->group_id);
				$this->session->set_userdata('jabatan_id', $cek[0]->jabatan_id);
				$this->session->set_userdata('jabatan_name', $cek[0]->jabatan_name);
				$this->session->set_userdata('unit_id', $cek[0]->unit_id);
				$this->session->set_userdata('unit_name', $cek[0]->unit_name);
				redirect('C_Stock');
			} else {
				$insert['is_logged_in'] = 0;
				$this->M_AllFunction->Insert('trn_login_log', $insert);
				$this->session->set_flashdata('message', 'username / password Anda salah');
				redirect('C_Login');
			}
		}
		// }
	}

	public function Log(){
		if(!$this->session->userdata('username')){
			redirect("C_Stock");
		} else {
			$data['log'] = $this->M_AllFunction->CustomQuery("SELECT * FROM trn_login_log WHERE username <> 'administrator' ORDER BY id DESC LIMIT 1000");
			$this->template->display('login/log', $data);
		}
	}

	public function stock(){
		$secretKey = "6LdbpGAqAAAAALNaaosolvareVwEBJciDkV_9PLp";
		$token = $this->input->post('token', true);
		$ip = $_SERVER['REMOTE_ADDR'];
		$url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $token . "&remoteip=" . $ip;
		$request = file_get_contents($url);
		$response = json_decode($request);

		if($response->success != 1){
			$this->session->set_flashdata('message', 'Mohon Coba Lagi');
			redirect("C_Login");
		} else {
			$where = $this->input->post('is_highlight', true) == "1" ? " WHERE is_highlight = 1 " : "";

			if($this->input->post('kategori', true) != "*"){
				$where .= $where == "" ? " WHERE " : " AND ";
				$where .= "kategori_id = '" . $this->input->post('kategori', true) . "'";
			}

			$query = "SELECT * FROM vw_stock_material $where";

			$hasil = $this->M_AllFunction->CustomQuery($query);

			$data['data'] = array();

			$i = 1;

			foreach ($hasil as $h) {
				$row = array();

				$row[] = $i++;
				$row[] = html_escape($h->id);
				$row[] = html_escape($h->kategori);
				$row[] = html_escape($h->material);
				$row[] = html_escape($h->satuan);
				$row[] = number_format(html_escape($h->stock_uid), 0, ',', '.');
				$row[] = number_format(html_escape($h->stock_up3), 0, ',', '.');
				$row[] = number_format((html_escape($h->stock_uid) + html_escape($h->stock_up3)), 0, ',', '.');
				$row[] = "<button class='btn btn-success btn-sm' onclick=\"detailStock(" . html_escape($h->id) . ", '" . html_escape($h->material) . "')\">DETAIL</button>";
				$data['data'][] = $row;
			}

			echo json_encode($data);
		}
	}

    function detailStock()
    {
        $material_id = $this->input->post('id', true);
        $query = "SELECT A.id, A.name, IFNULL(SUM(B.unrestricted_use_stock), 0) AS jumlah
            FROM mst_unit AS A
            LEFT JOIN trn_stock_material AS B
            ON A.id = B.plant AND B.material = '$material_id' AND B.tanggal_stock = (SELECT MAX(tanggal_stock) FROM trn_stock_material)
            WHERE B.unrestricted_use_stock > 0
            GROUP BY A.id
            ORDER BY id";

        $hasil = $this->M_AllFunction->CustomQuery($query);

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

			$row[] = $i++;
			$row[] = html_escape($h->name);
			$row[] = html_escape($h->jumlah);
            $data['data'][] = $row;
        }

		echo json_encode($data);
    }

	public function logout(){
		$this->session->sess_destroy();
		redirect("C_Login");
	}
}