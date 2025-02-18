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

class C_Unit extends CI_Controller
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
        $data['data'] = $this->M_AllFunction->Get('mst_unit');
        $this->template->display('unit/index', $data);
    }

    function Save()
    {
        if (isset($_POST['Edit'])) {
            $data = array(
                "name"        => $this->input->post('name', true),
                "singkatan"   => strtoupper($this->input->post('singkatan', true)),
                "updatedby"   => $this->session->userdata('username'),
                "updateddate" => date('Y-m-d H: i: s')
            );
            $this->M_AllFunction->Update('mst_unit', $data, "id = '" . $this->input->post('id', true) . "'");
            redirect('C_Unit');
        } else {
            $cek = $this->M_AllFunction->Where('mst_unit', "id = '" . $this->input->post('id', true) . "'");
            if (count($cek) == 0) {
                $data = array(
                    "id"          => $this->input->post('id', true),
                    "name"        => $this->input->post('name', true),
                    "singkatan"   => strtoupper($this->input->post('singkatan', true)),
                    "createdby"   => $this->session->userdata('username'),
                    "createddate" => date('Y-m-d H: i: s')
                );
                $this->M_AllFunction->Insert('mst_unit', $data);
                redirect('C_Unit');
            } else {
                $this->session->set_flashdata('message', 'Unit Gagal DiTambahkan, Plant Site Sudah DiGunakan.');
                redirect("C_Unit/Add");
            }
        }
    }
}
