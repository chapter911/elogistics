<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 */

class C_ATTB extends CI_Controller {

	function __construct(){
		parent::__construct();

        $this->load->model(array("M_AllFunction", "M_Attb"));

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

    function index(){
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $data['material'] = $this->M_AllFunction->Get('vw_material');
        $data['gudang'] = $this->M_AllFunction->CustomQuery("SELECT * FROM mst_gudang GROUP BY nama_gudang");
        $this->template->display('attb/index', $data);
    }

    function getData(){
        $hasil = $this->M_Attb->getData();

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

			$row[] = $i++;
			$row[] = html_escape($h->singkatan);
			$row[] = html_escape($h->no_attb);
			$row[] = html_escape($h->tug);
			$row[] = html_escape($h->sipb);
			$row[] = html_escape($h->material_id);
			$row[] = html_escape($h->material);
			$row[] = html_escape($h->kategori);
			$row[] = number_format(html_escape($h->volume), 0, ',', '.');
			$row[] = html_escape($h->status);
			$row[] = (new DateTime('today'))->diff(new DateTime(html_escape($h->location_update_date)))->days . " Hari";
            $buttonFoto = html_escape($h->foto1) == null ? "<button class='btn btn-danger btn-sm' onclick=\"foto(" . html_escape($h->id) . ", 1, '" . html_escape($h->filepath) . '/' . html_escape($h->foto1) . "')\">1</button> " : "<button class='btn btn-success btn-sm' onclick=\"foto(" . html_escape($h->id) . ", 1, '" . html_escape($h->filepath) . '/' . html_escape($h->foto1) . "')\">1</button> ";
            $buttonFoto .= html_escape($h->foto2) == null ? "<button class='btn btn-danger btn-sm' onclick=\"foto(" . html_escape($h->id) . ", 2, '" . html_escape($h->filepath) . '/' . html_escape($h->foto2) . "')\">2</button> " : "<button class='btn btn-success btn-sm' onclick=\"foto(" . html_escape($h->id) . ", 2, '" . html_escape($h->filepath) . '/' . html_escape($h->foto2) . "')\">2</button> ";
            $buttonFoto .= html_escape($h->foto3) == null ? "<button class='btn btn-danger btn-sm' onclick=\"foto(" . html_escape($h->id) . ", 3, '" . html_escape($h->filepath) . '/' . html_escape($h->foto3) . "')\">3</button> " : "<button class='btn btn-success btn-sm' onclick=\"foto(" . html_escape($h->id) . ", 3, '" . html_escape($h->filepath) . '/' . html_escape($h->foto3) . "')\">3</button> ";
            $buttonFoto .= html_escape($h->foto4) == null ? "<button class='btn btn-danger btn-sm' onclick=\"foto(" . html_escape($h->id) . ", 4, '" . html_escape($h->filepath) . '/' . html_escape($h->foto4) . "')\">4</button> " : "<button class='btn btn-success btn-sm' onclick=\"foto(" . html_escape($h->id) . ", 4, '" . html_escape($h->filepath) . '/' . html_escape($h->foto4) . "')\">4</button> ";
            $row[] = $buttonFoto;
			$row[] = "<a href='" . html_escape($h->link_gmap) . "' target='_blank'  class='btn btn-secondary btn-sm w-100'>" . html_escape($h->location) . "</a>";

            $action = "<div class='btn-group'>";
            $action .= $this->session->userdata("edit") == 1 ? "<button type='button' class='btn btn btn-outline-secondary btn-sm' onclick=\"update('" . html_escape($h->id) . "')\"><i class=\"fa fa-pencil\"></i></button>" : "";
            $action .= "<button type='button' class='btn btn-outline-secondary btn-sm' onclick=\"history('" . html_escape($h->id) . "')\"><i class='fa-regular fa-eye'></i></button>";
            $action .= "</div>";
            $row[] = $action;

            $data['data'][] = $row;
        }

		echo json_encode($data);
    }

    function getDetail(){
        $id = $this->input->post('id', true);
        $data = $this->M_AllFunction->Where('vw_attb', "id = '" . $id . "'");
        echo json_encode($data);
    }

    function getHistory(){
        $id = $this->input->post('id', true);
        $data['header'] = $this->M_AllFunction->Where('vw_attb', "id = '" . $id . "'");

        $query_detail = "WITH date_combine AS (
                            SELECT
                                    id,
                                    hdr_id,
                                    status_update_date AS tanggal
                            FROM trn_attb_dtl
                            WHERE hdr_id = '$id' AND status_update_date IS NOT NULL
                            UNION
                            SELECT
                                    id,
                                    hdr_id,
                                    location_update_date AS tanggal
                            FROM trn_attb_dtl
                            WHERE hdr_id = '$id' AND location_update_date IS NOT NULL
                    ), without_status AS (
                        SELECT
                            id,
                            hdr_id,
                            tanggal AS updated_date,
                            CASE WHEN (SELECT status_id FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.status_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1) IS NULL
                                THEN (SELECT status_id FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal > trn_attb_dtl.status_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                                ELSE (SELECT status_id FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.status_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                            END AS status_id,
                            CASE WHEN (SELECT location FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date  ORDER BY trn_attb_dtl.id DESC LIMIT 1) IS NULL
                                THEN (SELECT location FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal > trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                                ELSE (SELECT location FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                            END AS location,
                            CASE WHEN (SELECT sipb FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date  ORDER BY trn_attb_dtl.id DESC LIMIT 1) IS NULL
                                THEN (SELECT sipb FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal > trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                                ELSE (SELECT sipb FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                            END AS sipb,
                            CASE WHEN (SELECT filepath FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date  ORDER BY trn_attb_dtl.id DESC LIMIT 1) IS NULL
                                THEN (SELECT filepath FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal > trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                                ELSE (SELECT filepath FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                            END AS filepath,
                            CASE WHEN (SELECT sipb_file FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date  ORDER BY trn_attb_dtl.id DESC LIMIT 1) IS NULL
                                THEN (SELECT sipb_file FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal > trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                                ELSE (SELECT sipb_file FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                            END AS sipb_file,
                            CASE WHEN (SELECT foto1 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date  ORDER BY trn_attb_dtl.id DESC LIMIT 1) IS NULL
                                THEN (SELECT foto1 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal > trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                                ELSE (SELECT foto1 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                            END AS foto1,
                            CASE WHEN (SELECT foto2 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date  ORDER BY trn_attb_dtl.id DESC LIMIT 1) IS NULL
                                THEN (SELECT foto2 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal > trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                                ELSE (SELECT foto2 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                            END AS foto2,
                            CASE WHEN (SELECT foto3 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date  ORDER BY trn_attb_dtl.id DESC LIMIT 1) IS NULL
                                THEN (SELECT foto3 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal > trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                                ELSE (SELECT foto3 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                            END AS foto3,
                            CASE WHEN (SELECT foto4 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date  ORDER BY trn_attb_dtl.id DESC LIMIT 1) IS NULL
                                THEN (SELECT foto4 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal > trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                                ELSE (SELECT foto4 FROM trn_attb_dtl WHERE date_combine.hdr_id = trn_attb_dtl.hdr_id AND date_combine.tanggal = trn_attb_dtl.location_update_date ORDER BY trn_attb_dtl.id DESC LIMIT 1)
                            END AS foto4
                        FROM date_combine
                    )
                    SELECT without_status.*, mst_status_attb.status FROM without_status
                    LEFT JOIN mst_status_attb
                    ON without_status.status_id = mst_status_attb.id
                    ORDER BY updated_date DESC;";
        $data['detail'] = $this->M_AllFunction->CustomQuery($query_detail);
        $this->load->view('attb/history', $data);
    }

    function Save(){
        $path = 'data_uploads/attb/' . $this->input->post('no_attb', true);

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $input_file = array('foto1', 'foto2', 'foto3', 'foto4', 'sipb_file', 'tug_file');
        $filename = array();

        foreach($input_file as $in){
            $new_name = $in. "-" . bin2hex(random_bytes(24));
            $config = array();

            $config['upload_path'] = $path;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['remove_spaces'] = TRUE;
            $config['max_size'] = 10000;
            $config['file_name'] = $new_name;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload($in)) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('flash_failed', 'Format File Tidak Sesuai' . $error['error']);
                redirect('C_ATTB');
            } else {
                array_push($filename, $this->upload->data()['file_name']);
            }
        }
        $dataHeader = array(
            "no_attb"       => $this->input->post('no_attb', true),
            "tug"           => $this->input->post('tug', true),
            "material_id"   => $this->input->post('material_id', true),
            "volume"        => $this->input->post('volume', true),
            "pat"           => $this->input->post('pat', true),
            "serial_number" => $this->input->post('serial_number', true),
            "unit"          => $this->session->userdata('unit_id'),
            "created_by"    => $this->session->userdata('username'),
            "created_date"  => date('Y-m-d H:i:s')
        );
        $hdr_id = $this->M_AllFunction->InsertGetId('trn_attb', $dataHeader);

        $dataDetail = array(
            "hdr_id"               => $hdr_id,
            "no_attb"              => $this->input->post('no_attb', true),
            "status_id"            => $this->input->post('status_id', true),
            "status_update_date"   => $this->input->post('status_update_date', true),
            "location"             => $this->input->post('location', true),
            "location_update_date" => $this->input->post('location_update_date', true),
            "sipb"                 => $this->input->post('sipb', true),
            "filepath"             => $path,
            "foto1"                => $filename[0],
            "foto2"                => $filename[1],
            "foto3"                => $filename[2],
            "foto4"                => $filename[3],
            "sipb_file"            => $filename[4],
            "tug_file"             => $filename[5],
            "created_by"           => $this->session->userdata('username'),
            "created_date"         => date('Y-m-d H:i:s')
        );
        $this->M_AllFunction->Insert('trn_attb_dtl', $dataDetail);
        redirect('C_ATTB');
    }

    function Update(){
        if($this->input->post('type', true) == "status"){
            $dataDetail = array(
                "hdr_id"               => $this->input->post('edit_id', true),
                "no_attb"              => $this->input->post('edit_no_attb', true),
                "status_id"            => $this->input->post('status_id', true),
                "status_update_date"   => $this->input->post('status_update_date', true),
                "created_by"           => $this->session->userdata('username'),
                "created_date"         => date('Y-m-d H:i:s')
            );

            $cek = $this->M_AllFunction->Where('trn_attb_dtl', "hdr_id = '" . $this->input->post('edit_id', true) . "' AND status_id = '" . $this->input->post('status_id', true) . "'");
            if(count($cek) == 0){
                $this->M_AllFunction->Insert('trn_attb_dtl', $dataDetail);
            } else {
                $this->M_AllFunction->Update('trn_attb_dtl', $dataDetail, "hdr_id = '" . $this->input->post('edit_id', true) . "' AND status_id = '" . $this->input->post('status_id', true) . "'");
            }
        } else {
            $path = 'data_uploads/attb/' . $this->input->post('edit_no_attb', true);

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $input_file = array('foto1', 'foto2', 'foto3', 'foto4', 'sipb_file');
            $filename = array();

            foreach($input_file as $in){
                $new_name = $in. "-" . bin2hex(random_bytes(24));
                $config = array();

                $config['upload_path'] = $path;
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['remove_spaces'] = TRUE;
                $config['max_size'] = 10000;
                $config['file_name'] = $new_name;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload($in)) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('flash_failed', 'Format File Tidak Sesuai' . $error['error']);
                    redirect('C_ATTB');
                } else {
                    array_push($filename, $this->upload->data()['file_name']);
                }
            }
            $dataDetail = array(
                "hdr_id"               => $this->input->post('edit_id', true),
                "no_attb"              => $this->input->post('edit_no_attb', true),
                "location"             => $this->input->post('location', true),
                "location_update_date" => $this->input->post('location_update_date', true),
                "sipb"                 => $this->input->post('sipb', true),
                "filepath"             => $path,
                "foto1"                => $filename[0],
                "foto2"                => $filename[1],
                "foto3"                => $filename[2],
                "foto4"                => $filename[3],
                "sipb_file"            => $filename[4],
                "created_by"           => $this->session->userdata('username'),
                "created_date"         => date('Y-m-d H:i:s')
            );
            $this->M_AllFunction->Insert('trn_attb_dtl', $dataDetail);
        }
        redirect('C_ATTB');
    }

    function upload_foto(){
        $hdr_id = $this->input->post('hdr_id', true);
        $foto = "foto" . $this->input->post('foto', true);

        $no_attb = $this->M_AllFunction->Where('trn_attb_dtl', "hdr_id = '$hdr_id'")[0]->no_attb;

        $path = 'data_uploads/attb/' . $no_attb;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $config['upload_path'] = $path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;
        $config['file_name'] = $foto. "-" . bin2hex(random_bytes(24));

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('upload_foto')) {
            $this->session->set_flashdata('flash_failed', 'Format File Tidak Sesuai');
        } else {
            $data = array(
                'filepath'     => $path,
                $foto          => $this->upload->data()['file_name'],
                'updated_by'   => $this->session->userdata('username'),
                'updated_date' => date('Y-m-d H:i:s')
            );
            $this->M_AllFunction->Update('trn_attb_dtl', $data, 'hdr_id = ' . $hdr_id);
        }
        redirect('C_ATTB');
    }

    function delete(){
        $id = $this->input->post('id', true);
        $this->M_AllFunction->Delete('trn_attb', 'id = ' . $id);
        echo "success";
    }

    function import(){
        $path = 'data_uploads/attb/';

        $config['upload_path'] = $path;
        $config['allowed_types'] = 'xls|xlsx';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;
        $config['file_name'] = "attb-" . bin2hex(random_bytes(24));

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('upload_file')) {
            $this->session->set_flashdata('flash_failed', 'Format File Tidak Sesuai');
        } else {
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if (isset($_FILES['upload_file']['name']) && in_array($_FILES['upload_file']['type'], $file_mimes)) {
                $arr_file = explode('.', $_FILES['upload_file']['name']);
                $extension = end($arr_file);
                if ('xls' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                for ($i = 1; $i < count($sheetData); $i++) {
                    if ($sheetData[$i][1] == '' || $sheetData[$i][2] == '') {
                        continue;
                    } else {
                        $dataHeader = array(
                            "no_attb"       => $sheetData[$i][0],
                            "tug"           => $sheetData[$i][1],
                            "material_id"   => $sheetData[$i][2],
                            "volume"        => $sheetData[$i][3],
                            "pat"           => $sheetData[$i][5],
                            "serial_number" => $sheetData[$i][6],
                            "unit"          => $this->session->userdata('unit_id'),
                            "created_by"    => $this->session->userdata('username'),
                            "created_date"  => date('Y-m-d H:i:s')
                        );
                        $hdr_id = $this->M_AllFunction->InsertGetId('trn_attb', $dataHeader);

                        $status = 0;
                        if ($sheetData[$i][4] == 'Belum DiUsulkan') {
                            $status = 0;
                        } elseif ($sheetData[$i][4] == 'AE1') {
                            $status = 1;
                        } elseif ($sheetData[$i][4] == 'AE2') {
                            $status = 2;
                        } elseif ($sheetData[$i][4] == 'AE3') {
                            $status = 3;
                        } elseif ($sheetData[$i][4] == 'AE4') {
                            $status = 4;
                        } else {
                            $status = 0;
                        }

                        $dataDetail = array(
                            "hdr_id"               => $hdr_id,
                            "no_attb"              => $sheetData[$i][0],
                            "status_id"            => $status,
                            "status_update_date"   => $sheetData[$i][7],
                            "location"             => $sheetData[$i][8],
                            "location_update_date" => $sheetData[$i][9],
                            "sipb"                 => $sheetData[$i][10],
                            "created_by"           => $this->session->userdata('username'),
                            "created_date"         => date('Y-m-d H:i:s')
                        );
                        $this->M_AllFunction->Insert('trn_attb_dtl', $dataDetail);
                    }
                    $this->session->set_flashdata('flash_success', 'Data Berhasil Di Import');
                }
            }
        }
        redirect('C_ATTB');
    }
}