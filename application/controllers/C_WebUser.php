<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 */

class C_WebUser extends CI_Controller {

	function __construct(){
		parent::__construct();

		if (!$this->session->userdata('username')) {
			redirect('C_Login');
		} else {
            $user = $this->M_AllFunction->Where('mst_user', "username = '" . $this->session->userdata('username') . "' AND is_active = 1");
            if(count($user) == 0){
                $this->session->sess_destroy();
                $this->session->set_flashdata('message', 'User Di Non Aktifkan.');
                redirect("C_Login");
            }
            $controller = $this->uri->segment(1);
            if($this->uri->segment(2) != null){
                $controller .=  "/" . $this->uri->segment(2);
            }
            $data = $this->M_AllFunction->CekAkses($this->session->userdata('group_id'), $controller);
            if (count($data) > 0) {
                if($data[0]->akses == "0") {
                    $this->session->set_userdata('controller', $controller);
                    redirect('C_My404/NoAccess');
                } else {
					$this->session->set_userdata('add', $data[0]->FiturAdd);
					$this->session->set_userdata('edit', $data[0]->FiturEdit);
					$this->session->set_userdata('delete', $data[0]->FiturDelete);
					$this->session->set_userdata('export', $data[0]->FiturExport);
					$this->session->set_userdata('import', $data[0]->FiturImport);
                }
            }
        }
	}

	public function index(){
        $data['data'] = $this->M_AllFunction->Get("vw_user");
        $data['group'] = $this->M_AllFunction->Where("mst_user_group", "is_active = 1");
        $data['jabatan'] = $this->M_AllFunction->Where("mst_jabatan", "is_active = 1");
        $data['unit'] = $this->M_AllFunction->Get("mst_unit");
		$this->template->display("webuser/index", $data);
	}

    public function getUser(){
        $username = $this->input->post('username', true);
        $data = $this->M_AllFunction->Where('vw_user', "username = '$username'");
        echo json_encode($data);
    }

	public function Save(){
        $cek = $this->M_AllFunction->Where('mst_user', "username = '" . $this->input->post('username', true) . "'");
        if(count($cek) > 0){
            if($this->input->post('username', true) == "administrator") {
                $this->session->set_flashdata('flash_failed', 'Maaf Administrator tidak bisa di edit.');
                redirect("C_WebUser");
            } else {
                $data = array(
                    "nik"         => $this->input->post('nik', true),
                    "name"        => $this->input->post('name', true),
                    "email"       => $this->input->post('email', true),
                    "jabatan_id"  => $this->input->post('jabatan_id', true),
                    "group_id"    => $this->input->post('group_id', true),
                    "unit_id"     => $this->input->post('unit_id', true),
                    "updatedby"   => $this->session->userdata('username'),
                    "updateddate" => date('Y-m-d H:i:s')
                );
                if($this->M_AllFunction->Update('mst_user', $data, "username = '" . $this->input->post('username', true) . "'")){
                    $this->session->set_flashdata('flash_success', 'User Berhasil DiUpdate.');
                } else {
                    $this->session->set_flashdata('flash_failed', 'User Gagal DiUpdate.');
                }
                redirect("C_WebUser");
            }
        } else {
            if($this->M_AllFunction->Where('mst_user', "username = '" . $this->input->post('username', true) . "'")){
				$this->session->set_flashdata('flash_failed', 'Username Sudah Digunakan.');
            } else {
                if($this->input->post('password', true) != $this->input->post('konfirmasi', true)){
                    $this->session->set_flashdata('flash_failed', 'Password dan Konfirmasi Tidak Sama.');
                    redirect("C_WebUser");
                } else if(strlen($this->input->post('password', true)) < 8){
                    $this->session->set_flashdata('flash_failed', 'Password minimal 8 karakter.');
                    redirect("C_WebUser");
                } else if (!preg_match("#[0-9]+#", $this->input->post('password', true))){
                    $this->session->set_flashdata('flash_failed', 'Password wajib mengandung angka.');
                    redirect("C_WebUser");
                } else if (!preg_match("#[a-z]+#", $this->input->post('password', true))){
                    $this->session->set_flashdata('flash_failed', 'Password wajib mengandung huruf kecil.');
                    redirect("C_WebUser");
                } else if (!preg_match("#[A-Z]+#", $this->input->post('password', true))){
                    $this->session->set_flashdata('flash_failed', 'Password wajib mengandung huruf kapital.');
                    redirect("C_WebUser");
                } else {
                    $data = array(
                        "username"    => strtolower($this->input->post('username', true)),
                        "nik"         => $this->input->post('nik', true),
                        "name"        => $this->input->post('name', true),
                        "email"       => $this->input->post('email', true),
                        "jabatan_id"  => $this->input->post('jabatan_id', true),
                        "password"    => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
                        "group_id"    => $this->input->post('group_id', true),
                        "unit_id"     => $this->input->post('unit_id', true),
                        "createdby"   => $this->session->userdata('username'),
                        "createddate" => date('Y-m-d H: i: s')
                    );
                    if($this->M_AllFunction->Insert('mst_user', $data)){
                        $this->session->set_flashdata('flash_success', 'User Gagal DiTambahkan.');
                    } else {
                        $this->session->set_flashdata('flash_failed', 'User Gagal DiTambahkan.');
                    }
                }
            }
            redirect("C_WebUser");
        }
	}

    function resetPassword(){
        // $user = $this->M_AllFunction->Where('mst_user', "username != 'administrator'");
        // $data_user[] = array();
        // $i = 1;
        // foreach ($user as $u) {
        //     $newpassword = $u->unit_id . "@" . rand(1000, 9999);
        //     $data = array(
        //         "password" => password_hash($newpassword, PASSWORD_DEFAULT)
        //     );
        //     $this->M_AllFunction->Update('mst_user', $data, "username = '" . $u->username . "'");
        //     $data_user[$i] = array(
        //         "username" => $u->username,
        //         "password" => $newpassword
        //     );
        //     $i++;
        // }
        // echo '<pre>'; print_r($data_user); echo '</pre>';
        // die();
    }

    function updateAkses(){
        $username = $this->input->post('username', true);
        $akses = $this->input->post('akses', true);
        if($this->input->post('platform', true) == "Web"){
            $this->M_AllFunction->Update('mst_user', array("is_web" => $akses), "username = '$username'");
        } else {
            $this->M_AllFunction->Update('mst_user', array("is_android" => $akses), "username = '$username'");
        }
        echo "success";
    }

	public function ChangePassword(){
        $username = $this->session->userdata('username');
        $oldpassword = $this->input->post('oldpassword', true);
        $newpassword = $this->input->post('newpassword');
        $confirmation = $this->input->post('confirmation');

        $cek = $this->M_AllFunction->Where('mst_user', "username = '$username'");

        if(count($cek) == 0 || !password_verify($password, $cek[0]->password)){
            $data = array(
                'status'  => 'failed',
                'message' => 'Password Lama Anda Salah.'
            );
            echo json_encode($data);
        } else {
            if(strlen($newpassword) == $this->input->post('oldpassword', true)){
                $data = array(
                    'status'  => 'failed',
                    'message' => 'Password baru dan lama sama.'
                );
                echo json_encode($data);
            } else if($newpassword != $confirmation){
                $data = array(
                    'status'  => 'failed',
                    'message' => 'Password dan Konfirmasi Tidak Sama.'
                );
                echo json_encode($data);
            } else if(strlen($newpassword) < 8){
                $data = array(
                    'status'  => 'failed',
                    'message' => 'Password minimal 8 karakter.'
                );
                echo json_encode($data);
            } else if (!preg_match("#[0-9]+#", $newpassword)){
                $data = array(
                    'status'  => 'failed',
                    'message' => 'Password wajib mengandung angka.'
                );
                echo json_encode($data);
            } else if (!preg_match("#[a-z]+#", $newpassword)){
                $data = array(
                    'status'  => 'failed',
                    'message' => 'Password wajib mengandung huruf kecil.'
                );
                echo json_encode($data);
            } else if (!preg_match("#[A-Z]+#", $newpassword)){
                $data = array(
                    'status'  => 'failed',
                    'message' => 'Password wajib mengandung huruf kapital.'
                );
                echo json_encode($data);
            } else {
                $update = array(
                    "password" => password_hash($this->input->post('newpassword'), PASSWORD_DEFAULT)
                );
                if($this->M_AllFunction->Update('mst_user', $update, "username = '$username'")){
                    $data = array(
                        'status'  => 'success',
                        'message' => 'Password Berhasil diPerbaharui.'
                    );
                    echo json_encode($data);
                } else {
                    $data = array(
                        'status'  => 'failed',
                        'message' => 'Gagal Mengganti Password.'
                    );
                    echo json_encode($data);
                }
            }
        }
	}

	public function Activation(){
        if($this->uri->segment(3) != "administrator"){
            $data = array(
                "is_active" => $this->uri->segment(4) == 1 ? 0 : 1,
                "updatedby" => $this->session->userdata('username'),
                "updateddate" => date('Y-m-d H:i:s')
            );
            $this->M_AllFunction->Update("mst_user", $data, "username = '" . $this->uri->segment(3) . "'");
        }
        redirect('C_WebUser');
	}
}