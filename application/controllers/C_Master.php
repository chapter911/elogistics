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

class C_Master extends CI_Controller
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

    function JenisAnggaran()
    {
        $data['data'] = $this->M_AllFunction->Get('mst_jenis_anggaran');
        $this->template->display('master/jenis_anggaran', $data);
    }

    function JenisSave()
    {
        if (isset($_POST['Edit'])) {
            $data = array(
                "jenis"       => $this->input->post('jenis', true),
                "updatedby"   => $this->session->userdata('username'),
                "updateddate" => date('Y-m-d H:i:s')
            );
            $this->M_AllFunction->Update('mst_jenis_anggaran', $data, "id = '" . $this->input->post('id', true) . "'");
            redirect('C_Master/JenisAnggaran');
        } else {
            $cek = $this->M_AllFunction->Where('mst_jenis_anggaran', "jenis = '" . $this->input->post('jenis', true) . "'");
            if (count($cek) == 0) {
                $data = array(
                    "jenis"       => $this->input->post('jenis', true),
                    "createdby"   => $this->session->userdata('username'),
                    "createddate" => date('Y-m-d H:i:s')
                );
                $this->M_AllFunction->Insert('mst_jenis_anggaran', $data);
                redirect('C_Master/JenisAnggaran');
            } else {
                $this->session->set_flashdata('message', 'Jenis Gagal DiTambahkan, Jenis Sudah DiGunakan.');
                redirect('C_Master/JenisAnggaran');
            }
        }
    }

    function Pejabat() {
        $data['data'] = $this->M_AllFunction->Get('trn_pejabat_pln');
        $this->template->display('master/pejabat', $data);
    }

    function PejabatSave() {
        if ($this->input->post('id', true) != 0) {
            $data = array(
                "name"        => $this->input->post('name', true),
                "jabatan"     => $this->input->post('jabatan', true),
                "updated_by"   => $this->session->userdata('username'),
                "updated_date" => date('Y-m-d H:i:s')
            );
            $this->M_AllFunction->Update('trn_pejabat_pln', $data, "id = '" . $this->input->post('id', true) . "'");
        } else {
            $data = array(
                "name"        => $this->input->post('name', true),
                "jabatan"     => $this->input->post('jabatan', true),
                "created_by"  => $this->session->userdata('username')
            );
            $this->M_AllFunction->Insert('trn_pejabat_pln', $data);
        }
        redirect('C_Master/Pejabat');
    }
}