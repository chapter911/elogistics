<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 */

class C_Material extends CI_Controller {

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
		$data['data'] = $this->M_AllFunction->Get('vw_material');
        $data['kategori'] = $this->M_AllFunction->CustomQuery('SELECT * FROM mst_material_hdr ORDER BY kategori');
        $data['satuan'] = $this->M_AllFunction->Get('mst_satuan');
		$this->template->display("material/material", $data);
	}

	public function MaterialSave(){
        $cek = $this->M_AllFunction->Where('mst_material_dtl', "id = '" . $this->input->post('id', true) . "'");
        if(count($cek) == 0){
            $data = array(
                "id"           => $this->input->post('id', true),
                "kategori_id"  => $this->input->post('kategori_id', true),
                "material"     => $this->input->post('material', true),
                "satuan_id"    => $this->input->post('satuan_id', true),
                "leadtime"     => $this->input->post('leadtime', true),
                "safety"       => $this->input->post('safety', true),
                "is_highlight" => $this->input->post('is_highlight', true),
                "is_dashboard" => $this->input->post('is_dashboard', true),
                "createdby"    => $this->session->userdata('username'),
                "createddate"  => date('Y-m-d H: i: s')
            );
            $this->session->set_flashdata('flash_success', 'Material Berhasil DiTambahkan');
            $this->M_AllFunction->Insert('mst_material_dtl', $data);
        } else {
            $data = array(
                "kategori_id"  => $this->input->post('kategori_id', true),
                "material"     => $this->input->post('material', true),
                "satuan_id"    => $this->input->post('satuan_id', true),
                "leadtime"     => $this->input->post('leadtime', true),
                "safety"       => $this->input->post('safety', true),
                "is_highlight" => $this->input->post('is_highlight', true),
                "is_dashboard" => $this->input->post('is_dashboard', true),
                "updatedby"    => $this->session->userdata('username'),
                "updateddate"  => date('Y-m-d H: i: s')
            );
            $this->session->set_flashdata('flash_success', 'Material Berhasil DiUpdate');
            $this->M_AllFunction->Update('mst_material_dtl', $data, "id = '" . $this->input->post('id', true) . "'");
        }
        redirect('C_Material');
	}

    function getMaterial(){
        $data = $this->M_AllFunction->Where('vw_material', "id = '" . $this->input->post('material', true) . "'");
        echo json_encode($data);
    }

    public function Kategori(){
        $data['data'] = $this->M_AllFunction->Get('mst_material_hdr');
        $this->template->display('material/kategori', $data);
    }

    public function KategoriSave(){
        $kategori = $this->input->post('kategori', true);
        $cek = $this->M_AllFunction->Where('mst_material_hdr', "kategori = '$kategori'");
        if(count($cek) > 0){
            $this->session->set_flashdata('message', 'Kategori Material Gagal DiTambahkan, Kategori Material Sudah DiGunakan.');
            redirect('C_Material/Kategori');
        } else {
            $data = array(
                "kategori"    => $kategori,
                "createdby"   => $this->session->userdata('username'),
                "createddate" => date('Y-m-d H:i:s')
            );
            if($this->M_AllFunction->Insert('mst_material_hdr', $data)){
                $this->session->set_flashdata('flash_success', 'Kategori Material Berhasil DiTambahkan.');
                redirect('C_Material/Kategori');
            } else {
                $this->session->set_flashdata('flash_failed', 'Kategori Material Gagal DiTambahkan.');
                redirect('C_Material/Kategori');
            }
        }
    }
}