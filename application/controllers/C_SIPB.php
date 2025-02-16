<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 * @property Uri $uri
 */

class C_SIPB extends CI_Controller
{
    function __construct()
    {
        parent::__construct();


        $this->load->library('Pdf');
        $this->load->library('curl');

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
        echo phpinfo();
        die();
        $data = json_decode($this->curl->simple_get("10.3.0.185:8088/api/v.2.1/check_kr?no_kr=0169.Pj/DAN.01.02/F06050000/2024"));
        echo '<pre>'; print_r($data); echo '</pre>';
        die();
        $data['data'] = $this->M_AllFunction->Get("mst_satuan");
        $this->template->display('satuan/index', $data);
    }

    function SIPB(){
        $unit_id = $this->session->userdata('unit_id');
        $cek_sipb = $this->M_AllFunction->Where("trn_sipb_hdr", "no_sipb LIKE '" . $unit_id . "%' ORDER BY no_sipb DESC LIMIT 1");
        if(count($cek_sipb) == 0){
            $data['sipb'] = $unit_id . "000001";
        } else {
            $data['sipb'] = $unit_id . str_pad(substr($cek_sipb[0]->no_sipb, 5) + 1, 6, "0", STR_PAD_LEFT);
        }
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $data['bidang'] = $this->M_AllFunction->Get("mst_bidang");
        $data['storage_location'] = $this->M_AllFunction->Get("mst_storage_location");
        $data['vendor'] = $this->M_AllFunction->Get("mst_vendor");
        $data['material'] = $this->M_AllFunction->CustomQuery("SELECT id, material, satuan FROM vw_material");
        $this->template->display('SIPB/sipb', $data);
    }

    function Save(){
        $data = array(
            "no_sipb"                  => $this->input->post('no_sipb', true),
            "form_name"                => $this->input->post('form_name', true),
            "storage_location"         => $this->input->post('storage_location', true) ?? null,
            "plat_no"                  => $this->input->post('plat_no', true) ?? null,
            "unit_asal"                => $this->input->post('unit_asal', true) ?? null,
            "unit_tujuan"              => $this->input->post('unit_tujuan', true) ?? null,
            "unit_tujuan_manual"       => $this->input->post('unit_tujuan_manual', true) ?? null,
            "bidang_tujuan"            => $this->input->post('bidang_tujuan', true) ?? null,
            "vendor"                   => $this->input->post('vendor', true) ?? null,
            "ttd_team_leader_logistik" => $this->input->post('ttd_team_leader_logistik', true) ?? null,
            "ttd_pengawas_pekerjaan"   => $this->input->post('ttd_pengawas_pekerjaan', true) ?? null,
            "ttd_pembawa_barang"       => $this->input->post('ttd_pembawa_barang', true) ?? null,
            "slip"                     => $this->input->post('slip', true) ?? null,
            "no_spj"                   => $this->input->post('no_spj', true) ?? null,
            "no_wbs_order"             => $this->input->post('no_wbs_order', true) ?? null,
            "pekerjaan"                => $this->input->post('pekerjaan', true) ?? null,
            "lokasi"                   => $this->input->post('lokasi', true) ?? null,
            "nama_pelaksana"           => $this->input->post('nama_pelaksana', true) ?? null,
            "surat_penghapusan_limbah" => $this->input->post('surat_penghapusan_limbah', true) ?? null,
            "no_ae1"                   => $this->input->post('no_ae1', true) ?? null,
            "reservasi"                => $this->input->post('reservasi', true) ?? null,
            "no_tug3"                  => $this->input->post('no_tug3', true) ?? null,
            "no_tug5"                  => $this->input->post('no_tug5', true) ?? null,
            "no_tug7"                  => $this->input->post('no_tug7', true) ?? null,
            "no_tug8"                  => $this->input->post('no_tug8', true) ?? null,
            "no_tug9"                  => $this->input->post('no_tug9', true) ?? null,
            "no_tug16"                 => $this->input->post('no_tug16', true) ?? null,
        );

        if($this->input->post("is_insert") == 1){
            $directory = 'data_uploads/sipb/' . date('Y-m') . '/' . $this->session->userdata('unit_id');

            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $config['allowed_types'] = 'pdf';
            $config['remove_spaces'] = TRUE;
            $config['max_size'] = 10000;
            $config['upload_path'] = $directory;

            $this->load->library('upload', $config);

            $array_file = array(
                "file_ae1",
                "file_reservasi",
                "file_tug_3",
                "file_tug_5",
                "file_tug_7",
                "file_tug_8",
                "file_tug_9",
                "file_tug_16",
                "file_surat_usulan_limbah",
            );

            $array_file_name = array();
            $i = 0;

            foreach ($array_file as $key => $file) {
                $array_file_name[$i] = $this->session->userdata('unit_id') . '-' . bin2hex(random_bytes(24));
                $config['file_name'] = $array_file_name[$i];
                $this->upload->initialize($config);
                if (isset($_FILES[$file]) && !empty($_FILES[$file]['name'])) {
                    if (!$this->upload->do_upload($file)) {
                        $errornya = $this->upload->display_errors();
                        $this->session->set_flashdata('flash_failed', 'Maaf Error Ketika Upload ' . $file . '.' . $errornya);
                        return redirect('C_SIPB/SIPB');
                    } else {
                        $array_file_name[$i] .= ".pdf";
                    }
                } else {
                    $array_file_name[$i] = null;
                }
                $i++;
            }

            $data["file_directory"]           = $directory;
            $data["file_ae_1"]                = $array_file_name[0];
            $data["file_reservasi"]           = $array_file_name[1];
            $data["file_tug_3"]               = $array_file_name[2];
            $data["file_tug_5"]               = $array_file_name[3];
            $data["file_tug_7"]               = $array_file_name[4];
            $data["file_tug_8"]               = $array_file_name[5];
            $data["file_tug_9"]               = $array_file_name[6];
            $data["file_tug_16"]              = $$array_file_name[7];
            $data["file_surat_usulan_limbah"] = $$array_file_name[8];
            $data["created_by"]               = $this->session->userdata('username');
            $data["created_date"]             = $date('Y-m-d H:i:s');

            $this->M_AllFunction->Insert('trn_sipb_hdr', $data);

            for($i = 0; $i < count($this->input->post('volume', true)); $i++){
                $detail = array(
                    'no_sipb'     => $this->input->post('no_sipb', true),
                    'normalisasi' => $this->input->post('normalisasi', true)[$i] ?? null,
                    'material'    => $this->input->post('material', true)[$i] ?? null,
                    'volume'      => $this->input->post('volume', true)[$i] ?? null,
                    'merk'        => $this->input->post('merk', true)[$i] ?? null,
                    'no_seri'     => $this->input->post('no_seri', true)[$i] ?? null,
                    'satuan'      => $this->input->post('satuan', true)[$i] ?? null,
                    'pabrikan'    => $this->input->post('pabrikan', true)[$i ?? null]
                );
                $this->M_AllFunction->Insert('trn_sipb_dtl', $detail);
            }
            $this->session->set_flashdata('flash_success', 'Berhasil Menginputkan Data.');
        } else {
            $this->M_AllFunction->Update('trn_sipb_hdr', $data, "no_sipb = '" . $this->input->post('no_sipb', true) . "'");
        }

        redirect('C_SIPB/SIPB');
    }

    function Update(){
        $file_name = $this->session->userdata('unit_id') . '-' . bin2hex(random_bytes(24));

        $cek_directory = $this->M_AllFunction->Where('trn_sipb_hdr', "no_sipb = '" . $this->input->post('no_sipb_update', true) . "'");
        $directory = $cek_directory[0]->file_directory;

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $config['allowed_types'] = 'pdf';
        $config['remove_spaces'] = TRUE;
        $config['file_name'] = $file_name;
        $config['max_size'] = 10000;
        $config['upload_path'] = $directory;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if($this->input->post('form_name') == "file_sipb"){
            if (!$this->upload->do_upload('file_sipb')) {
                $errornya = $this->upload->display_errors();
                $this->session->set_flashdata('flash_failed', 'Maaf Error Ketika Upload SIPB.' . $errornya);
                return redirect('C_SIPB/SIPB');
            } else {
                $extension = end(explode(".", $_FILES['file_sipb']['name']));
                $file_name .= "." . $extension;
            }

            $data = array(
                "file_sipb"           => $file_name,
                "is_selesai"          => 1,
                "updated_by"          => $this->session->userdata('username'),
                "updated_date"        => date('Y-m-d H:i:s')
            );
        } else if($this->input->post('form_name') == "file_tug9"){
            if (!$this->upload->do_upload('file_tug9')) {
                $errornya = $this->upload->display_errors();
                $this->session->set_flashdata('flash_failed', 'Maaf Error Ketika Upload File TUG 9.' . $errornya);
                return redirect('C_SIPB/SIPB');
            } else {
                $extension = end(explode(".", $_FILES['file_tug9']['name']));
                $file_name .= "." . $extension;
            }

            $data = array(
                "file_tug_9"           => $file_name,
                "updated_by"          => $this->session->userdata('username'),
                "updated_date"        => date('Y-m-d H:i:s')
            );
        }

        $this->M_AllFunction->Update('trn_sipb_hdr', $data, "no_sipb = '" . $this->input->post('no_sipb_update', true) . "'");
        redirect('C_SIPB/SIPB');
    }

    function ajaxSIPB(){
        $hasil = $this->M_AllFunction->Get('vw_sipb_hdr');

        $data['data'] = array();

        foreach ($hasil as $h) {
            $row = array();

            $row[] = html_escape($h->no_sipb);
            $row[] = strtoupper(str_replace("_", " ", html_escape($h->form_name)));
            $row[] = html_escape($h->unit_asal_name);
            $row[] = html_escape($h->unit_tujuan_name);
            $row[] = html_escape(strtoupper($h->bidang_tujuan));
            $row[] = html_escape($h->vendor);

            $array_file = array(
                "file_ae_1",
                "file_reservasi",
                "file_tug_3",
                "file_tug_5",
                "file_tug_7",
                "file_tug_8",
                "file_tug_9",
                "file_tug_16",
                "file_surat_usulan_limbah",
            );

            $text = "";
            for($i = 0; $i < count($array_file); $i++){
                if(html_escape($h->{$array_file[$i]}) == null){
                    $text .= "";
                } else {
                    $text .= "<a href=\"" . base_url() . html_escape($h->file_directory) . '/' . html_escape($h->{$array_file[$i]}) . "\" class=\"btn btn-text-danger btn-hover-light-danger btn-sm\" target=\"_blank\"> <i class=\"fa fa-file-pdf\"></i> " . str_replace("_", " ", str_replace("FILE", "", strtoupper($array_file[$i]))) . "</a>";
                }
            }
            if(html_escape($h->is_selesai) == 0){
                $text .= "<a href=\"#\" class=\"btn btn-text-secondary btn-hover-light-danger btn-sm\"> <i class=\"fa fa-file-pdf\"></i> SIPB</a>";
            } else {
                $text .= "<a href=\"" . base_url() . html_escape($h->file_directory) . '/' . html_escape($h->file_sipb) . "\" class=\"btn btn-text-danger btn-hover-light-danger btn-sm\" target=\"_blank\"> <i class=\"fa fa-file-pdf\"></i> SIPB</a>";
            }
            $row[] = $text;

            if(html_escape($h->is_selesai) == 0){
                $row[] = "<span class='badge badge-danger btn-danger'>Belum Selesai</span>";
            } else {
                $row[] = "<span class='badge badge-primary btn-primary'>Selesai</span>";
            }
            $row[] = "<button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"detail('" . html_escape($h->no_sipb) .  "','" . str_replace("_", " ", html_escape($h->form_name)) . "','" . str_replace("_", " ", html_escape($h->is_selesai)) . "')\"><i class=\"fa fa-pencil\"></i></button>";
            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    function detailSIPB(){
        $no_sipb = $this->input->post('no_sipb', true);

        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $data['bidang'] = $this->M_AllFunction->Get("mst_bidang");
        $data['storage_location'] = $this->M_AllFunction->Get("mst_storage_location");
        $data['vendor'] = $this->M_AllFunction->Get("mst_vendor");

        $data['header'] = $this->M_AllFunction->Where('vw_sipb_hdr', "no_sipb = '" . $no_sipb . "'");
        $data['detail'] = $this->M_AllFunction->Where('vw_sipb_dtl', "no_sipb = '" . $no_sipb . "'");
        if($data['header'][0]->form_name == "reservasi"){
            $this->load->view('SIPB/kategori/sipb_reservasi', $data);
        } else if($data['header'][0]->form_name == "ago"){
            $this->load->view('SIPB/kategori/sipb_ago', $data);
        } else if($data['header'][0]->form_name == "antar_unit"){
            $this->load->view('SIPB/kategori/sipb_antar_unit', $data);
        } else if($data['header'][0]->form_name == "attb"){
            $this->load->view('SIPB/kategori/sipb_attb', $data);
        } else if($data['header'][0]->form_name == "klaim_garansi_retrofit"){
            $this->load->view('SIPB/kategori/sipb_klaim_garansi_retrofit', $data);
        } else if($data['header'][0]->form_name == "limbah"){
            $this->load->view('SIPB/kategori/sipb_limbah', $data);
        } else if($data['header'][0]->form_name == "manual"){
            $this->load->view('SIPB/kategori/sipb_manual', $data);
        }
    }

    function downloadSIPB(){
        $no_sipb = $this->uri->segment(3);
        $data['header'] = $this->M_AllFunction->Where('vw_sipb_hdr', "no_sipb = '" . $no_sipb . "'");
        $data['detail'] = $this->M_AllFunction->Where('vw_sipb_dtl', "no_sipb = '" . $no_sipb . "'");
        $this->load->view('SIPB/sipb_pdf', $data);
    }
}