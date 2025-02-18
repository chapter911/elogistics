<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 */

class C_Vendor extends CI_Controller {

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
        $data['bank'] = $this->M_AllFunction->Get('mst_bank');
        $data['data'] = $this->M_AllFunction->Get("vw_vendor");
		$this->template->display("vendor/index", $data);
	}

	public function Save(){
        $cek = $this->M_AllFunction->Where('mst_vendor', "id = '" . $this->input->post('id', true) . "'");
        $data = array(
            "vendor"         => $this->input->post('vendor', true),
            "merk"           => $this->input->post('merk', true),
            "alamat"         => $this->input->post('alamat', true),
            "direktur"       => $this->input->post('direktur', true),
            "pic"            => $this->input->post('pic', true),
            "jabatan_pic"    => $this->input->post('jabatan_pic', true),
            "akte_pendirian" => $this->input->post('akte_pendirian', true),
            "id_bank"        => $this->input->post('id_bank', true),
            "nomor_rekening" => $this->input->post('nomor_rekening', true),
            "phone"          => $this->input->post('phone', true),
            "dihubungi"      => $this->input->post('dihubungi', true),
        );
        if(count($cek) == 0){
            $data['id'] = $this->input->post('id', true);
            $data["createdby"]   = $this->session->userdata('username');
            $data["createddate"] = date('Y-m-d H:i:s');
            $this->M_AllFunction->Insert('mst_vendor', $data);
        } else {
            $data["updatedby"]   = $this->session->userdata('username');
            $data["updateddate"] = date('Y-m-d H:i:s');
            $this->M_AllFunction->Update('mst_vendor', $data, "id = '" . $this->input->post('id', true) . "'");
        }
        redirect('C_Vendor');
	}

	public function Activation(){
        $data = array(
            "is_active" => $this->uri->segment(4) == 1 ? 0 : 1,
            "updatedby" => $this->session->userdata('username'),
            "updateddate" => date('Y-m-d H:i:s')
        );
        $this->M_AllFunction->Update("mst_vendor", $data, "id = '" . $this->uri->segment(3) . "'");
        redirect('C_Vendor');
	}

    public function getVendor(){
        $data = $this->M_AllFunction->Where('mst_vendor', "id = '" . $this->input->post('id', true) . "'");
        echo json_encode($data);
    }
}