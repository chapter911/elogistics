<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 */

class C_KHS extends CI_Controller
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

    public function index()
    {
        $data['vendor'] = $this->M_AllFunction->Get("mst_vendor");
        $data['data'] = $this->M_AllFunction->Get("vw_khs");
        $data['material'] = $this->M_AllFunction->Get("vw_material");
        $this->template->display("khs/index", $data);
    }

    function getKHS(){
        $data = $this->M_AllFunction->Where("vw_khs", "id = '" . $this->input->post('id', true) . "'");
        $data['detail'] = $this->M_AllFunction->Where('mst_khs_alokasi', "nomor_khs = '" . $this->input->post('nomor_khs', true) . "'");
        echo json_encode($data);
    }

    function getAlokasiKHS(){
        $query = "SELECT mst_khs_alokasi.*, vw_material.material
            FROM mst_khs_alokasi
            LEFT JOIN vw_material
            ON mst_khs_alokasi.material_id = vw_material.id
            WHERE nomor_khs = '" . $this->input->post('nomor_khs', true) . "'";
        $data['data'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view("khs/material_alokasi", $data);
    }

    public function Save() {
        $path = 'data_uploads/khs/';

        $config['upload_path'] = $path;
        $config['allowed_types'] = 'pdf';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;
        $config['file_name'] = "khs-" . bin2hex(random_bytes(24));

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->input->post('is_edit', true) == "1") {
            if ($this->upload->do_upload('filepdf')) {
                $data = array('upload_data' => $this->upload->data());
                $filename = $data['upload_data']['file_name'];
            }

            $data = array(
                "id"                    => $this->input->post('id', true),
                "judul"                 => $this->input->post('judul', true),
                "id_vendor"             => $this->input->post('id_vendor', true),
                "nomor_khs"             => $this->input->post('nomor_khs', true),
                "nomor_amandemen"       => $this->input->post('nomor_amandemen', true),
                "tahun_kontrak"         => $this->input->post('tahun_kontrak', true),
                "harga_kontrak"         => $this->input->post('harga_kontrak', true),
                "tanggal_awal_kontrak"  => $this->input->post('tanggal_awal_kontrak', true),
                "tanggal_akhir_kontrak" => $this->input->post('tanggal_akhir_kontrak', true),
                "updatedby"             => $this->session->userdata('username'),
                "updateddate"           => date('Y-m-d H:i:s')
            );

            if ($filename != "") {
                $data["file_name"] = $filename;
                $data["file_location"] = $path;
            }

            $this->M_AllFunction->Update('mst_khs', $data, "id = '" . $this->input->post('id', true) . "'");

            $this->M_AllFunction->Delete('mst_khs_alokasi', "nomor_khs = '" . $this->input->post('nomor_khs', true) . "'");

            for ($i = 0; $i < count($this->input->post('material', true)); $i++) {
                $datadetail[$i] = array(
                    "nomor_khs"    => $this->input->post('nomor_khs', true),
                    "material_id"  => $this->input->post('material', true)[$i],
                    "alokasi"      => $this->input->post('alokasi', true)[$i]
                );
            }

            $this->M_AllFunction->InsertBatch('mst_khs_alokasi', $datadetail);

            redirect('C_KHS');
        } else {
            if ($this->upload->do_upload('filepdf')) {
                $data = array('upload_data' => $this->upload->data());
            }

            $data = array(
                "judul"                 => $this->input->post('judul', true),
                "id_vendor"             => $this->input->post('id_vendor', true),
                "nomor_khs"             => $this->input->post('nomor_khs', true),
                "nomor_amandemen"       => $this->input->post('nomor_amandemen', true),
                "tahun_kontrak"         => $this->input->post('tahun_kontrak', true),
                "harga_kontrak"         => $this->input->post('harga_kontrak', true),
                "tanggal_awal_kontrak"  => $this->input->post('tanggal_awal_kontrak', true),
                "tanggal_akhir_kontrak" => $this->input->post('tanggal_akhir_kontrak', true),
                "createdby"             => $this->session->userdata('username'),
                "createddate"           => date('Y-m-d H:i:s')
            );

            if ($data['upload_data']['file_name'] != "") {
                $data["file_name"] = $data['upload_data']['file_name'];
                $data["file_location"] = $path;
            }

            $this->M_AllFunction->Insert('mst_khs', $data);

            for ($i = 0; $i < count($this->input->post('material', true)); $i++) {
                $datadetail[$i] = array(
                    "nomor_khs"    => $this->input->post('nomor_khs', true),
                    "material_id"  => $this->input->post('material', true)[$i],
                    "alokasi"       => $this->input->post('alokasi', true)[$i]
                );
            }

            $this->M_AllFunction->InsertBatch('mst_khs_alokasi', $datadetail);

            redirect('C_KHS');
        }
    }
}