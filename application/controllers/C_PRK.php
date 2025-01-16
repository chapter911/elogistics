<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 * @property Upload $upload
 * @property Uri $uri
 */

class C_PRK extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('username')) {
            redirect('C_Login');
        } else {
            $user = $this->M_AllFunction->Where('mst_user', "username = '" . $this->session->userdata('username') . "' AND is_active = 1");
            if (count($user) == 0) {
                $this->session->sess_destroy();
                $this->session->set_flashdata('message', 'User Di Non Aktifkan.');
                redirect("C_Login");
            }
            $controller = $this->uri->segment(1);
            if ($this->uri->segment(2) != null) {
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

    function index()
    {
        $data['data'] = $this->M_AllFunction->Get("vw_prk");
        $data['basket'] = $this->M_AllFunction->Get("mst_basket");
        $this->template->display('prk/index', $data);
    }

    function getPRK() {
        $id = $this->uri->segment(3);
        $data = $this->M_AllFunction->Where("vw_prk", "id = $id");
        echo json_encode($data);
    }

    function Save()
    {
        if ($this->input->post('id', true) != 0) {
            $data = array(
                "tahun"        => $this->input->post('tahun', true),
                "no_prk"       => $this->input->post('no_prk', true),
                "basket_id"    => $this->input->post('basket_id', true),
                "uraian_prk"   => $this->input->post('uraian_prk', true),
                "is_murni"     => $this->input->post('is_murni', true),
                "material"     => $this->input->post('material', true),
                "jasa"         => $this->input->post('jasa', true),
                "updated_by"   => $this->session->userdata('username'),
                "updated_date" => date('Y-m-d H: i:s')
            );
            $this->M_AllFunction->Update('mst_prk', $data, "id = '" . $this->input->post('id', true) . "'");
        } else {
            $cek = $this->M_AllFunction->Where('mst_prk', "no_prk = '" . $this->input->post('no_prk', true) . "'");
            if (count($cek) == 0) {
                $data = array(
                    "tahun"        => $this->input->post('tahun', true),
                    "no_prk"       => $this->input->post('no_prk', true),
                    "basket_id"    => $this->input->post('basket_id', true),
                    "uraian_prk"   => $this->input->post('uraian_prk', true),
                    "is_murni"     => $this->input->post('is_murni', true),
                    "material"     => $this->input->post('material', true),
                    "jasa"         => $this->input->post('jasa', true),
                    "created_by"   => $this->session->userdata('username'),
                    "created_date" => date('Y-m-d H:i:s')
                );
                $this->M_AllFunction->Insert('mst_prk', $data);
            } else {
                $this->session->set_flashdata('message', 'Unit Gagal DiTambahkan, Plant Site Sudah DiGunakan.');
            }
        }
        redirect('C_PRK');
    }
}
