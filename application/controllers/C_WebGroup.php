<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 */

class C_WebGroup extends CI_Controller {

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
        $data['data'] = $this->M_AllFunction->Get("mst_user_group");
		$this->template->display("webgroup/index", $data);
	}

	public function Save(){
		$data = array(
			"group_name"  => $this->input->post('group_name', true),
			"createdby"   => $this->session->userdata('username'),
			"createddate" => date('Y-m-d H:i:s')
		);
		if($this->M_AllFunction->Insert('mst_user_group', $data)){
			redirect("C_WebGroup");
		} else {
			$this->session->set_flashdata('message', 'User Gagal DiTambahkan.');
			redirect("C_WebGroup");
		}
	}

	public function Akses(){
		$group_id = $this->input->post('group_id', true);
		$data['group_id'] = $group_id;
		$data['lv1'] = $this->M_AllFunction->MenuAkses($group_id, 'menu_lv1');
		$data['lv2'] = $this->M_AllFunction->MenuAkses($group_id, 'menu_lv2');
		$data['lv3'] = $this->M_AllFunction->MenuAkses($group_id, 'menu_lv3');
		$this->load->view('webgroup/akses', $data);
	}

	public function SaveAkses(){
		$group_id = $this->input->post('group_id', true);
		if (count($_POST) > 0) {
			if ((!array_key_exists("99", $_POST) || !array_key_exists("99-02", $_POST)) && $group_id == "1") {
				$this->session->set_flashdata('flash_failed', 'Akses User Web Administrator Tidak Boleh DiNonAktifkan!.');
				redirect("C_WebGroup/Akses/$group_id");
			} else {
				$menu_id = array_keys($_POST);
				for ($i = 0; $i < count($menu_id); $i++) {
					$add = false;
					$edit = false;
					$delete = false;
					$export = false;
					$import = false;
					if(is_array($this->input->post($menu_id[$i], true))){
						$add    = isset($this->input->post($menu_id[$i], true)['FiturAdd']) ? true : false;
						$edit	= isset($this->input->post($menu_id[$i], true)['FiturEdit']) ? true : false;
						$delete = isset($this->input->post($menu_id[$i], true)['FiturDelete']) ? true : false;
						$export = isset($this->input->post($menu_id[$i], true)['FiturExport']) ? true : false;
						$import = isset($this->input->post($menu_id[$i], true)['FiturImport']) ? true : false;
					}
					$data[$i] = array(
						'group_id'	 => $group_id,
						'menu_id'	 => $menu_id[$i],
						'FiturAdd'   => $add,
						'FiturEdit'   => $edit,
						'FiturDelete' => $delete,
						'FiturExport' => $export,
						'FiturImport' => $import
					);
				}
				$result = $this->M_AllFunction->Delete('menu_akses', "group_id = '$group_id'");
				if ($result) {
					if ($this->M_AllFunction->InsertBatch('menu_akses', $data)) {
						$this->session->set_flashdata('flash_succes', 'Berhasil Update Akses.');
						redirect("C_WebGroup");
					}
				}
			}
		} else {
			if($group_id == 1){
				$this->session->set_flashdata('flash_failed', 'Akses Administrator Tidak Boleh DiNonAktifkan Keseluruhan.');
				redirect("C_WebGroup/Akses");
			} else {
				$this->M_AllFunction->Delete('menu_akses', "group_id = '$group_id'");
				redirect("C_WebGroup/Akses");
			}
		}
	}

	public function Activation(){
        if($this->uri->segment(3) != "administrator"){
            $data = array(
                "is_active" => $this->uri->segment(4) == 1 ? 0 : 1
            );
            $this->M_AllFunction->Update("mst_user_group", $data, "group_id = '" . $this->uri->segment(3) . "'");
        }
        redirect('C_WebGroup');
	}
}