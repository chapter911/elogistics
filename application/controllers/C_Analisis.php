<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 */

class C_Analisis extends CI_Controller {

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
        $data['kategori'] = $this->M_AllFunction->Get('mst_material_hdr');
		$this->template->display("analisis/index", $data);
	}

    function queryIndex(){
        $where = "";
        if($this->input->post('status', true) == "AMAN"){
            $where .= "WHERE status >= 100";
        } else if($this->input->post('status', true) == "SIAGA") {
            $where .= "WHERE status >= 50 AND status < 100";
        } else if($this->input->post('status', true) == "KRITIS") {
            $where .= "WHERE status < 50";
        }

        if($this->input->post('kategori', true) != "*"){
            $where .= $where == "" ? " WHERE " : " AND ";
            $where .= "kategori = '" . $this->input->post('kategori', true) . "'";
        }

        if($this->input->post('highlight', true) == "1"){
            $where .= $where == "" ? " WHERE " : " AND ";
            $where .= "is_highlight = '" . $this->input->post('highlight', true) . "'";
        }

        $basket = $this->input->post('basket', true) == "*" ? "" : "WHERE kebutuhan = '" . $this->input->post('basket', true) . "'";

        $query = "WITH kebutuhan AS (
                    SELECT
                        material_id,
                        SUM(sisa) AS kebutuhan
                    FROM vw_kebutuhan
                    $basket
                    GROUP BY material_id
                )

                SELECT
                    vw_analisis_pengadaan.*,
                    kebutuhan.kebutuhan
                FROM vw_analisis_pengadaan
                LEFT JOIN kebutuhan
                ON vw_analisis_pengadaan.material_id = kebutuhan.material_id
                $where
                ORDER BY status";

        return $this->M_AllFunction->CustomQuery($query);
    }

    public function ajaxIndex(){
        $hasil = $this->queryIndex();

        $data['data'] = array();

        $i = 1;
        $data['total'] = 0;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->material_id);
            $row[] = html_escape($h->kategori);
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->satuan);

            if(html_escape($h->status) >= 100){
                $row[] = "<button class='btn btn-sm' style='background-color: #50cd89; color: white'> AMAN </button>";
            } else if(html_escape($h->status) >= 50 && html_escape($h->status) < 100) {
                $row[] = "<button class='btn btn-sm btn-warning'> SIAGA </button>";
            } else {
                $row[] = "<button class='btn btn-sm btn-danger'> KRITIS </button>";
            }

            $row[] = number_format(html_escape($h->stock_uid), 0, ",", ".");
            $row[] = number_format(html_escape($h->stock_up3), 0, ",", ".");
            $row[] = number_format(html_escape($h->rencana_tiba), 0, ",", ".");
            $row[] = number_format(html_escape($h->total_stock), 0, ",", ".");
            $row[] = ""; //dikosongkan untuk memberi spasi
            $row[] = number_format(html_escape($h->pemakaian_perminggu), 0, ",", ".");
            $row[] = number_format(html_escape($h->pemakaian_perminggu * 4.3), 0, ",", ".");
            $row[] = number_format(html_escape($h->kebutuhan), 0, ",", ".");
            $row[] = number_format(html_escape($h->leadtime), 0, ",", ".");
            $row[] = number_format(html_escape($h->pemakaian_perminggu * $h->safety), 0, ",", ".");
            $row[] = number_format(html_escape(($h->leadtime * $h->pemakaian_perminggu) + $h->safety), 0, ",", ".");
            $row[] = html_escape($h->rop) == 0 ? 0 : number_format((html_escape($h->total_stock) / html_escape($h->pemakaian_perminggu * 4.3)), 2, ",", ".");
            $row[] = "<button onclick='showAddRencana(" . html_escape($h->material_id) . ")' class='btn btn-outline-secondary btn-sm'><i class=\"fa fa-pencil\"></i></button>";

            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    public function exportIndex(){
        $hasil = $this->queryIndex();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);

        $styleHeading = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
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

        $sheet->getStyle('A1:P2')->applyFromArray($styleHeading);

        foreach(range('A', 'P') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $spreadsheet->getActiveSheet()->mergeCells("A1:A2");
        $sheet->setCellValue('B1', 'NORMALISASI');
        $spreadsheet->getActiveSheet()->mergeCells("B1:B2");
        $sheet->setCellValue('C1', 'KATEGORI');
        $spreadsheet->getActiveSheet()->mergeCells("C1:C2");
        $sheet->setCellValue('D1', 'MATERIAL');
        $spreadsheet->getActiveSheet()->mergeCells("D1:D2");
        $sheet->setCellValue('E1', 'SATUAN');
        $spreadsheet->getActiveSheet()->mergeCells("E1:E2");
        $sheet->setCellValue('F1', 'STATUS');
        $spreadsheet->getActiveSheet()->mergeCells("F1:F2");
        $sheet->setCellValue('G1', 'STOCK');
        $spreadsheet->getActiveSheet()->mergeCells("G1:J1");
        $sheet->setCellValue('G2', 'UID');
        $sheet->setCellValue('H2', 'UNIT');
        $sheet->setCellValue('I2', 'RENCANA TIBA');
        $sheet->setCellValue('J2', 'TOTAL');
        $sheet->setCellValue('K1', 'PEMAKAIAN');
        $spreadsheet->getActiveSheet()->mergeCells("K1:L1");
        $sheet->setCellValue('K2', 'PER MINGGU');
        $sheet->setCellValue('L2', 'PER BULAN');
        $sheet->setCellValue('M1', 'LEADTIME (MINGGU)');
        $spreadsheet->getActiveSheet()->mergeCells("M1:M2");
        $sheet->setCellValue('N1', 'SAFETY STOCK');
        $spreadsheet->getActiveSheet()->mergeCells("N1:N2");
        $sheet->setCellValue('O1', 'ROP');
        $spreadsheet->getActiveSheet()->mergeCells("O1:O2");
        $sheet->setCellValue('P1', 'RASIO');
        $spreadsheet->getActiveSheet()->mergeCells("P1:P2");

        $i = 3;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-2);

            $sheet->setCellValue('B' . $i, html_escape($h->material_id));
            $sheet->setCellValue('C' . $i, html_escape($h->kategori));
            $sheet->setCellValue('D' . $i, html_escape($h->material));
            $sheet->setCellValue('E' . $i, html_escape($h->satuan));
            if(html_escape($h->status) >= 100){
                $sheet->setCellValue('F' . $i, "AMAN");
            } else if(html_escape($h->status) >= 50 && html_escape($h->status) < 100) {
                $sheet->setCellValue('F' . $i, "SIAGA");
            } else {
                $sheet->setCellValue('F' . $i, "KRITIS");
            }
            $sheet->setCellValue('G' . $i, html_escape($h->stock_uid));
            $sheet->setCellValue('H' . $i, html_escape($h->stock_up3));
            $sheet->setCellValue('I' . $i, html_escape($h->rencana_tiba));
            $sheet->setCellValue('J' . $i, html_escape($h->total_stock));
            $sheet->setCellValue('K' . $i, html_escape($h->pemakaian_perminggu));
            $sheet->setCellValue('L' . $i, html_escape($h->pemakaian_perminggu * 4.3));
            $sheet->setCellValue('M' . $i, html_escape($h->leadtime));
            $sheet->setCellValue('N' . $i, html_escape($h->pemakaian_perminggu * $h->safety));
            $sheet->setCellValue('O' . $i, html_escape(($h->leadtime * $h->pemakaian_perminggu) + $h->safety));
            $sheet->setCellValue('P' . $i, html_escape($h->rop) == 0 ? 0 : (html_escape($h->total_stock) / html_escape($h->rop)));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Status_Material_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

	public function DataPemakaian(){
        $data['material'] = $this->M_AllFunction->Get('vw_material');
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
		$this->template->display("analisis/data_pemakaian", $data);
	}

    function queryDataPemakaian(){
        $tahun = $this->input->post('tahun', true);
        $unit = $this->input->post('unit', true) == "*" ? '' : "AND plant = " . $this->input->post('unit', true);

        $query = "SELECT
                tahun, id_material, material, SUM(tahunan) AS tahunan, CEILING(SUM(tahunan) / 12) AS bulanan, CEILING(SUM(tahunan) / 12 / 4) AS mingguan
            FROM vw_data_pemakaian
            WHERE tahun = '$tahun' $unit
            GROUP BY tahun, id_material";
        return $this->M_AllFunction->CustomQuery($query);
    }

    public function AjaxDataPemakaian(){
        $hasil = $this->queryDataPemakaian();

        $data['data'] = array();

        foreach ($hasil as $h) {
            $row = array();

            $row[] = html_escape($h->tahun);
            $row[] = html_escape($h->id_material);
            $row[] = html_escape($h->material);
            $row[] = number_format(html_escape($h->tahunan), 0, ',', '.');
            $row[] = number_format(html_escape($h->bulanan), 0, ',', '.');
            $row[] = number_format(html_escape($h->mingguan), 0, ',', '.');

            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    function exportDataPemakaian(){
        $hasil = $this->queryDataPemakaian();

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

        $sheet->getStyle('A1:F1')->applyFromArray($styleHeading);
        $sheet->getStyle('A:C')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('D:F')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'TAHUN PEMAKAIAN');
        $sheet->setCellValue('B1', 'NORMALISASI');
        $sheet->setCellValue('C1', 'MATERIAL DESCRIPITON');
        $sheet->setCellValue('D1', 'TAHUNAN');
        $sheet->setCellValue('E1', 'BULANAN');
        $sheet->setCellValue('F1', 'MINGGUAN');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, html_escape($h->tahun));
            $sheet->setCellValue('B' . $i, html_escape($h->id_material));
            $sheet->setCellValue('C' . $i, html_escape($h->material));
            $sheet->setCellValue('D' . $i, html_escape($h->tahunan));
            $sheet->setCellValue('E' . $i, html_escape($h->bulanan));
            $sheet->setCellValue('F' . $i, html_escape($h->mingguan));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Data_Pemakaian_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function grafikPemakaian(){
        $id_material = $this->input->post('id_material', true);
        $unit = $this->input->post('unit_grafik', true) == "*" ? '' : "AND plant = " . $this->input->post('unit_grafik', true);

        $query = "SELECT tahun, SUM(tahunan) AS tahunan FROM vw_data_pemakaian_unit WHERE id_material = $id_material $unit GROUP BY tahun";

        $data['hasil'] = $this->M_AllFunction->CustomQuery($query);

        $this->load->view("analisis/grafik_pemakaian", $data);
    }

    function import() {
        ini_set('max_execution_time', 0);
        $config['allowed_types'] = 'xls|xlsx|csv';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 20000;
        $config['upload_path'] = "data_uploads/stockmaterial/";
        $config['file_name'] = "stock-" . $this->session->userdata('username') . " " . date('Y-m-d H-i-s');

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('upload_file')) {
            $this->session->set_flashdata('flash_failed', 'Format File Tidak Sesuai');
            redirect('C_Stock');
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
                    $sheetData[0][0] != "Material" &&
                    $sheetData[0][1] != "Reservation" &&
                    $sheetData[0][2] != "Material Description" &&
                    $sheetData[0][3] != "Storage Location" &&
                    $sheetData[0][4] != "Movement Type" &&
                    $sheetData[0][5] != "Order" &&
                    $sheetData[0][6] != "Material Document" &&
                    $sheetData[0][7] != "Posting Date" &&
                    $sheetData[0][8] != "Document Header Text" &&
                    $sheetData[0][9] != "WBS Element" &&
                    $sheetData[0][10] != "Entry Date" &&
                    $sheetData[0][11] != "User name" &&
                    $sheetData[0][12] != "Document Date" &&
                    $sheetData[0][13] != "Qty in Un. of Entry" &&
                    $sheetData[0][14] != "Unit of Entry" &&
                    $sheetData[0][15] != "Amount in LC" &&
                    $sheetData[0][16] != "Vendor" &&
                    $sheetData[0][17] != "Purchase Order" &&
                    $sheetData[0][18] != "Plant" &&
                    $sheetData[0][19] != "Company Code"
                ) {
                    $this->session->set_flashdata('message', 'Format Header File Tidak Sesuai');
                } else {
                    $this->M_AllFunction->Delete('trn_data_pemakaian', "tahun = '" . $this->input->post('tahun', true) . "'");
                    for ($i = 1; $i < count($sheetData); $i++) {
                        if ($sheetData[$i][0] != '') {
                            $data = array(
                                "tahun"                => $this->input->post('tahun', true),
                                "id_material"          => $sheetData[$i][0],
                                "reservation"          => $sheetData[$i][1],
                                "material_descripiton" => $sheetData[$i][2],
                                "storage_location"     => $sheetData[$i][3],
                                "movement_type"        => $sheetData[$i][4],
                                "ordering"             => $sheetData[$i][5],
                                "material_document"    => $sheetData[$i][6],
                                "posting_date"         => $sheetData[$i][7],
                                "document_header_text" => $sheetData[$i][8],
                                "wbs_element"          => $sheetData[$i][9],
                                "entry_date"           => $sheetData[$i][10],
                                "username"             => $sheetData[$i][11],
                                "document_date"        => $sheetData[$i][12],
                                "qty_in"               => $sheetData[$i][13],
                                "unit"                 => $sheetData[$i][14],
                                "lc_amount"            => $sheetData[$i][15],
                                "vendor"               => $sheetData[$i][16],
                                "purchase_order"       => $sheetData[$i][17],
                                "plant"                => $sheetData[$i][18],
                                "company_code"         => $sheetData[$i][19],
                                "uploaded_by"          => $this->session->userdata('username'),
                                "uploaded_date"        => date('Y-m-d H-i-s')
                            );
                            $this->M_AllFunction->Insert('trn_data_pemakaian', $data);
                        }
                    }
                }
                redirect('C_Analisis/DataPemakaian');
            }
        }
    }

    function dataRencana(){
        $material_id = $this->input->post('material_id', true);
        $year = date("Y");
        $query = "WITH data_skki AS (
                SELECT * FROM vw_skki WHERE tahun = $year AND jenis = 'M'
            )
            SELECT
                vw_material.id,
                vw_material.material,
                vw_material.satuan,
                data_skki.basket_id,
                SUM(data_skki.selisih_skki) AS selisih_skki,
                SUM(data_skki.harga_skki) AS harga_skki
            FROM vw_material
            LEFT JOIN data_skki
            ON vw_material.id = data_skki.material_id
            WHERE vw_material.id = $material_id
            GROUP BY vw_material.id, data_skki.basket_id
            ORDER BY data_skki.basket_id";
        $data = $this->M_AllFunction->CustomQuery($query);
        echo json_encode($data);
    }

    function RencanaSave(){
        $material_id = $this->input->post('materialid', true);
        $bulan = explode('-', $this->input->post('bulantahun', true))[1];
        $tahun = explode('-', $this->input->post('bulantahun', true))[0];
        if($this->input->post('totalb1', true) != 0){
            $minggub1 = $this->input->post('minggub1', true);
            $cek = $this->M_AllFunction->Where("trn_rencana_kontrak_hdr", "bulan = '$bulan' AND tahun = '$tahun' AND minggu = '$minggub1' AND basket = '1'");
            if(empty($cek)){
                $data = array(
                    "bulan"        => $bulan,
                    "tahun"        => $tahun,
                    "minggu"       => $minggub1,
                    "basket"       => 1,
                    "created_by"   => $this->session->userdata('username'),
                    "created_date" => date('Y-m-d H:i:s')
                );
                $id = $this->M_AllFunction->InsertGetId('trn_rencana_kontrak_hdr', $data);
            } else {
                $id = $cek[0]->id;
            }

            $datadetail = array(
                "id_hdr"       => $id,
                "material_id"  => $this->input->post('materialid', true),
                "volume"       => $this->input->post('volumeb1', true),
                "harga"        => $this->input->post('hargab1', true),
                "total"        => $this->input->post('totalb1', true),
                "created_by"   => $this->session->userdata('username'),
                "created_date" => date('Y-m-d H:i:s')
            );
            $cek_dtl = $this->M_AllFunction->Where("trn_rencana_kontrak_dtl", "id_hdr = '$id' AND material_id = '$material_id'");
            if(empty($cek_dtl)){
                $this->M_AllFunction->Insert('trn_rencana_kontrak_dtl', $datadetail);
            } else {
                $this->M_AllFunction->Update('trn_rencana_kontrak_dtl', $datadetail, "id = '" . $cek_dtl[0]->id . "'");
            }
        }
        if($this->input->post('totalb2', true) != 0){
            $minggub2 = $this->input->post('minggub2', true);
            $cek = $this->M_AllFunction->Where("trn_rencana_kontrak_hdr", "bulan = '$bulan' AND tahun = '$tahun' AND minggu = '$minggub2' AND basket = '2'");
            if(empty($cek)){
                $data = array(
                    "bulan"        => $bulan,
                    "tahun"        => $tahun,
                    "minggu"       => $minggub2,
                    "basket"       => 2,
                    "created_by"   => $this->session->userdata('username'),
                    "created_date" => date('Y-m-d H:i:s')
                );
                $id = $this->M_AllFunction->InsertGetId('trn_rencana_kontrak_hdr', $data);
            } else {
                $id = $cek[0]->id;
            }

            $datadetail = array(
                "id_hdr"       => $id,
                "material_id"  => $this->input->post('materialid', true),
                "volume"       => $this->input->post('volumeb2', true),
                "harga"        => $this->input->post('hargab2', true),
                "total"        => $this->input->post('totalb2', true),
                "created_by"   => $this->session->userdata('username'),
                "created_date" => date('Y-m-d H:i:s')
            );
            $cek_dtl = $this->M_AllFunction->Where("trn_rencana_kontrak_dtl", "id_hdr = '$id' AND material_id = '$material_id'");
            if(empty($cek_dtl)){
                $this->M_AllFunction->Insert('trn_rencana_kontrak_dtl', $datadetail);
            } else {
                $this->M_AllFunction->Update('trn_rencana_kontrak_dtl', $datadetail, "id = '" . $cek_dtl[0]->id . "'");
            }
        }
        if($this->input->post('totalb3', true) != 0){
            $minggub3 = $this->input->post('minggub3', true);
            $cek = $this->M_AllFunction->Where("trn_rencana_kontrak_hdr", "bulan = '$bulan' AND tahun = '$tahun' AND minggu = '$minggub3' AND basket = '3'");
            if(empty($cek)){
                $data = array(
                    "bulan"        => $bulan,
                    "tahun"        => $tahun,
                    "minggu"       => $minggub3,
                    "basket"       => 3,
                    "created_by"   => $this->session->userdata('username'),
                    "created_date" => date('Y-m-d H:i:s')
                );
                $id = $this->M_AllFunction->InsertGetId('trn_rencana_kontrak_hdr', $data);
            } else {
                $id = $cek[0]->id;
            }

            $datadetail = array(
                "id_hdr"       => $id,
                "material_id"  => $this->input->post('materialid', true),
                "volume"       => $this->input->post('volumeb3', true),
                "harga"        => $this->input->post('hargab3', true),
                "total"        => $this->input->post('totalb3', true),
                "created_by"   => $this->session->userdata('username'),
                "created_date" => date('Y-m-d H:i:s')
            );
            $cek_dtl = $this->M_AllFunction->Where("trn_rencana_kontrak_dtl", "id_hdr = '$id' AND material_id = '$material_id'");
            if(empty($cek_dtl)){
                $this->M_AllFunction->Insert('trn_rencana_kontrak_dtl', $datadetail);
            } else {
                $this->M_AllFunction->Update('trn_rencana_kontrak_dtl', $datadetail, "id = '" . $cek_dtl[0]->id . "'");
            }
        }
        redirect('C_Analisis');
    }
}