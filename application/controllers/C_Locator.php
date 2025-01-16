<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @property M_AllFunction $M_AllFunction
 * @property M_Locator $M_Locator
 * @property Session $session
 * @property Template $template
 * @property Upload $upload
 * @property Uri $uri
 */

class C_Locator extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model(array("M_AllFunction", "M_Locator"));
        $this->load->library('Pdf');

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
                if ($data[0]->akses == "0") {
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

    function index() {
        $data['kategori'] = $this->M_AllFunction->CustomQuery("SELECT id, kategori FROM mst_material_hdr WHERE is_active = 1");
        $data['gudang'] = $this->M_AllFunction->Get('mst_tipe_gudang');
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $this->template->display('locator/index', $data);
    }

    function getData() {
        $unit = $this->input->post('unit', true);
        if ($unit == "*") {
            $hasil = $this->M_AllFunction->Get('vw_stock_material_dtl');
        } else {
            $hasil = $this->M_AllFunction->Where('vw_stock_material_dtl', "unit_id = '$unit'");
        }

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->name);
            $row[] = html_escape($h->id);
            $row[] = html_escape($h->kategori);
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->satuan);
            $row[] = number_format(html_escape($h->stock), 0, ',', '.');
            $row[] = "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">
                        <button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick='showLokasi(\"" . html_escape($h->id) ."\", \"" . html_escape($h->material) . "\", \"" . html_escape($h->stock) . "\", \"" . html_escape($h->unit_id) . "\")' data-bs-toggle=\"modal\" data-bs-target=\"#modal_edit\"><i class=\"fa fa-eye\"></i></button>
                    </div>";
            $row[] = "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">
                        <button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"showDenah('" . html_escape($h->denah_gudang) . "')\" data-bs-toggle=\"modal\" data-bs-target=\"#modal_edit\"><i class=\"fa fa-eye\"></i></button>
                    </div>";
            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    function export() {
        $unit = $this->input->post('unit', true);
        if ($unit == "*") {
            $hasil = $this->M_AllFunction->Get('vw_stock_material_dtl');
        } else {
            $hasil = $this->M_AllFunction->Where('vw_stock_material_dtl', "unit_id = '$unit'");
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);

        $styleHeading = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'CCCCCC',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('A1:G1')->applyFromArray($styleHeading);
        $sheet->getStyle('G')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'UNIT');
        $sheet->setCellValue('C1', 'NORMALISASI');
        $sheet->setCellValue('D1', 'KATEGORI');
        $sheet->setCellValue('E1', 'MATERIAL');
        $sheet->setCellValue('F1', 'SATUAN');
        $sheet->setCellValue('G1', 'STOCK');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i - 1);
            $sheet->setCellValue('B' . $i, html_escape($h->name));
            $sheet->setCellValue('C' . $i, html_escape($h->id));
            $sheet->setCellValue('D' . $i, html_escape($h->kategori));
            $sheet->setCellValue('E' . $i, html_escape($h->material));
            $sheet->setCellValue('F' . $i, html_escape($h->satuan));
            $sheet->setCellValue('G' . $i, html_escape($h->stock));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data_Lokasi_Material.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function qr_export() {
        $normalisasi = explode(PHP_EOL, $this->input->post('normalisasi', true));
        $data['data'] = $this->M_AllFunction->WhereIn("vw_material", "id", $normalisasi);
        $this->load->view('locator/qr_export', $data);
    }

    function getLokasiMaterial() {
        $id_material = $this->input->post('id_material', true);
        $unit = $this->input->post('unit', true);
        $data = $this->M_AllFunction->Where('trn_stock_material_dtl', "id_material = '$id_material' AND unit = '$unit'");
        echo json_encode($data);
    }

    function UpdateLokasiMaterial()
    {
        $id_material = $this->input->post('id_material', true);
        $unit = $this->input->post('unit', true);
        $this->M_AllFunction->Delete('trn_stock_material_dtl', "id_material = '$id_material' AND unit = '$unit'");
        if(isset($_POST['gudang'])){
            for ($i = 0; $i < count($this->input->post('gudang', true)); $i++) {
                $data = array(
                    'id_material' => $id_material,
                    'id_gudang'   => $this->input->post('gudang', true)[$i],
                    'no_gudang'   => $this->input->post('no_gudang', true)[$i],
                    'rak'         => $this->input->post('rak', true)[$i],
                    'lantai'      => $this->input->post('lantai', true)[$i],
                    'petak'       => $this->input->post('petak', true)[$i],
                    'unit'        => $unit,
                    'created_by'  => $this->session->userdata('username')
                );
                $this->M_AllFunction->Replace('trn_stock_material_dtl', $data);
            }
        }
        redirect('C_Locator');
    }

    function import()
    {
        $username = str_replace(".", "_", $this->session->userdata('username'));
        $config['allowed_types'] = 'xls|xlsx';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;
        $config['upload_path'] = "data_uploads/lokasimaterial/";
        $config['file_name'] = "lokasi-" . $username . " " . date('Y-m-d H-i-s');

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('upload_file')) {
            $this->session->set_flashdata('flash_failed', 'Format File Tidak Sesuai');
            redirect('C_Locator');
        } else {
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if (isset($_FILES['upload_file']['name']) && in_array($_FILES['upload_file']['type'], $file_mimes)) {
                $arr_file = explode('.', $_FILES['upload_file']['name']);
                $extension = end($arr_file);
                if ('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else if ('xls' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                if (
                    $sheetData[0][0] != "id_material" &&
                    $sheetData[0][1] != "id_gudang" &&
                    $sheetData[0][2] != "no_gudang" &&
                    $sheetData[0][3] != "rak" &&
                    $sheetData[0][4] != "lantai" &&
                    $sheetData[0][5] != "petak"
                ) {
                    $this->session->set_flashdata('message', 'Format Header File Tidak Sesuai');
                    redirect('C_Locator');
                } else {
                    for ($i = 1; $i < count($sheetData); $i++) {
                        if ($sheetData[$i][0] == '') {
                            continue;
                        } else {
                            $data = array(
                                "id_material"  => $sheetData[$i][0],
                                "unit"         => $this->session->userdata('unit_id'),
                                "id_gudang"    => strtoupper($sheetData[$i][1]),
                                "no_gudang"    => $sheetData[$i][2],
                                "rak"          => $sheetData[$i][3],
                                "lantai"       => $sheetData[$i][4],
                                "petak"        => $sheetData[$i][5],
                                "created_by"   => $this->session->userdata('username'),
                                "created_date" => date('Y-m-d H-i-s')
                            );
                            $this->M_AllFunction->Replace('trn_stock_material_dtl', $data);
                        }
                    }
                    $this->session->set_flashdata('flash_success', 'Data Berhasil Di Import');
                    redirect('C_Locator');
                }
            }
        }
    }

    function layout()
    {
        $data['gudang'] = $this->M_AllFunction->Get('mst_tipe_gudang');
        $data['no_gudang'] = $this->M_AllFunction->GroupBy('no_gudang', 'trn_stock_material_dtl');
        $data['rak'] = $this->M_AllFunction->GroupBy('rak', 'trn_stock_material_dtl');
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $this->template->display('locator/layout', $data);
    }

    function getAjaxLayout(){
        $hasil = $this->M_Locator->getDataLayout();

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->name);
            $row[] = html_escape($h->tipe_gudang);
            $row[] = html_escape($h->no_gudang);
            $row[] = html_escape($h->rak);
            $row[] = "<button onclick='getMaterial(\"" . html_escape($h->unit) . "\", \"" . html_escape($h->id_gudang) . "\", \"" . html_escape($h->no_gudang) . "\", \"" . html_escape($h->rak) . "\")' class='btn btn-sm btn-secondary'>MATERIAL</button>";
            if(html_escape($h->id) == 0){
                $row[] = "<button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick='uploadLayout(\"" . html_escape($h->unit) . "\", \"" . html_escape($h->id_gudang) . "\", \"" . html_escape($h->no_gudang) . "\", \"" . html_escape($h->rak) . "\")'><i class=\"fa-solid fa-upload\"></i></button>";
            } else {
                $row[] = "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">
                        <button onclick='showImage(\"" . html_escape($h->image) . "\")' class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\"><i class='fa-regular fa-eye'></i></button>
                        <button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"deleteLayout('" . html_escape($h->id) . "')\"><i class=\"fa fa-trash\"></i></button>
                    </div>";
            }
            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    function export_layout(){
        $hasil = $this->M_Locator->getDataLayout();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);

        $styleHeading = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'CCCCCC',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('A1:E1')->applyFromArray($styleHeading);

        foreach(range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'UNIT');
        $sheet->setCellValue('C1', 'GUDANG');
        $sheet->setCellValue('D1', 'NOMOR GUDANG');
        $sheet->setCellValue('E1', 'RAK');

        $i = 2;

        $i = 2;
        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->name));
            $sheet->setCellValue('C' . $i, html_escape($h->tipe_gudang));
            $sheet->setCellValue('D' . $i, html_escape($h->no_gudang));
            $sheet->setCellValue('E' . $i, html_escape($h->rak));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="layout.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function getMaterialByLayout() {
        $unit      = $this->input->post('unit', true);
        $gudang    = $this->input->post('gudang', true);
        $no_gudang = $this->input->post('no_gudang', true);
        $rak       = $this->input->post('rak', true);
        $data['data'] = $this->M_AllFunction->Where('vw_stock_material_gudang', "id_unit = '$unit' AND id_gudang = '$gudang' AND no_gudang = '$no_gudang' AND rak = '$rak'");
        $this->load->view('locator/material', $data);
    }

    function layout_save() {
        $unit       = $this->input->post('unit');
        $id_gudang  = $this->input->post('id_gudang');
        $no_gudang  = $this->input->post('no_gudang');
        $rak        = $this->input->post('rak');
        $image_name = wordwrap(strtoupper(bin2hex(random_bytes(32))), 8, '-', true);

        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['remove_spaces'] = TRUE;
        $config['max_size']      = 2000;
        $config['upload_path']   = "data_uploads/denah_gudang/";
        $config['file_name']     = $image_name;
        $config['overwrite']     = TRUE;
        $config['file_ext_tolower'] = TRUE;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('upload_file')) {
            $this->session->set_flashdata('flash_failed', 'Format File Tidak Sesuai');
            redirect('C_Locator/layout');
        } else {
            $ext = explode('/', $_FILES['upload_file']['type'])[1];
            if($ext == "jpeg"){
                $ext = ctype_upper($ext) ? "JPG" : "jpg";
            }
            $data = array(
                'unit'       => $unit,
                'id_gudang'  => $id_gudang,
                'no_gudang'  => $no_gudang,
                'rak'        => $rak,
                'image_name' => $image_name,
                'extension'  => $ext,
                'created_by' => $this->session->userdata('username')
            );
            $this->M_AllFunction->Delete('mst_layout_rak', "unit = '$unit' AND id_gudang = '$id_gudang' AND no_gudang = '$no_gudang' AND rak = '$rak'");
            $this->M_AllFunction->Insert('mst_layout_rak', $data);
            redirect('C_Locator/layout');
        }
    }

    function layout_delete(){
        $id = $this->input->post('id', true);
        $this->M_AllFunction->Delete('mst_layout_rak', "id = '$id'");
        echo "success";
    }
}
