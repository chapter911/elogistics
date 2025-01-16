<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @property M_AllFunction $M_AllFunction
 * @property M_Kontrak $M_AllFunction
 * @property Session $session
 * @property Template $template
 * @property Upload $upload
 * @property Uri $uri
 */

class C_Kontrak extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model(array("M_AllFunction", "M_Kontrak"));

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

    function index(){
        $data['basket'] = $this->M_AllFunction->CustomQuery('SELECT * FROM mst_basket');
        $data['bulan'] = $this->M_AllFunction->CustomQuery('SELECT * FROM mst_bulan');
        $data['material_vendor'] = $this->M_AllFunction->Get('vw_kontrak_vendor');
        $this->template->display('kontrak/index', $data);
    }

    function getDashboard(){
        $where = "";
        if($this->input->post('basket', true) != "*"){
            $where = "WHERE id_basket = '" . $this->input->post('basket', true) . "'";
        }
        if($this->input->post('tahun_anggaran', true) != "*"){
            $where .= $where == "" ? " WHERE " : " AND ";
            $where .= "tahun_anggaran = '" . $this->input->post('tahun_anggaran', true) . "'";
        }
        if($this->input->post('is_murni', true) != "*"){
            $where .= $where == "" ? " WHERE " : " AND ";
            $where .= "is_murni = '" . $this->input->post('is_murni', true) . "'";
        }
        $query = "WITH data AS (
                SELECT * FROM vw_kontrak_dtl $where
            )
            SELECT
                material_id,
                material,
                kategori,
                satuan,
                SUM( CASE WHEN tujuan_code = 'BDG' THEN volume ELSE 0 END ) AS BDG,
                SUM( CASE WHEN tujuan_code = 'BLG' THEN volume ELSE 0 END ) AS BLG,
                SUM( CASE WHEN tujuan_code = 'BTR' THEN volume ELSE 0 END ) AS BTR,
                SUM( CASE WHEN tujuan_code = 'CKG' THEN volume ELSE 0 END ) AS CKG,
                SUM( CASE WHEN tujuan_code = 'CPP' THEN volume ELSE 0 END ) AS CPP,
                SUM( CASE WHEN tujuan_code = 'CPT' THEN volume ELSE 0 END ) AS CPT,
                SUM( CASE WHEN tujuan_code = 'CRC' THEN volume ELSE 0 END ) AS CRC,
                SUM( CASE WHEN tujuan_code = 'JTN' THEN volume ELSE 0 END ) AS JTN,
                SUM( CASE WHEN tujuan_code = 'KBJ' THEN volume ELSE 0 END ) AS KBJ,
                SUM( CASE WHEN tujuan_code = 'KJT' THEN volume ELSE 0 END ) AS KJT,
                SUM( CASE WHEN tujuan_code = 'LTA' THEN volume ELSE 0 END ) AS LTA,
                SUM( CASE WHEN tujuan_code = 'MRD' THEN volume ELSE 0 END ) AS MRD,
                SUM( CASE WHEN tujuan_code = 'MTG' THEN volume ELSE 0 END ) AS MTG,
                SUM( CASE WHEN tujuan_code = 'PDG' THEN volume ELSE 0 END ) AS PDG,
                SUM( CASE WHEN tujuan_code = 'PDK' THEN volume ELSE 0 END ) AS PDK,
                SUM( CASE WHEN tujuan_code = 'TJP' THEN volume ELSE 0 END ) AS TJP,
                SUM( CASE WHEN tujuan_code = 'UID' THEN volume ELSE 0 END ) AS UID,
                SUM( CASE WHEN tujuan_code = 'UP2D' THEN volume ELSE 0 END ) AS UP2D
            FROM
                data
            GROUP BY
                material_id,
                material,
                kategori,
                satuan";

        return $this->M_AllFunction->CustomQuery($query);
    }

    function ajaxDashboard(){
        $hasil = $this->getDashboard();

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

			$row[] = $i++;
			$row[] = html_escape($h->material_id);
			$row[] = html_escape($h->kategori);
			$row[] = html_escape($h->material);
			$row[] = html_escape($h->satuan);
            $total = html_escape($h->BDG) + html_escape($h->BLG) + html_escape($h->BTR) + html_escape($h->CKG) + html_escape($h->CPP) + html_escape($h->CPT) + html_escape($h->CRC) + html_escape($h->JTN) + html_escape($h->KBJ) + html_escape($h->KJT) + html_escape($h->LTA) + html_escape($h->MRD) + html_escape($h->MTG) + html_escape($h->PDG) + html_escape($h->PDK) + html_escape($h->TJP) + html_escape($h->UID) + html_escape($h->UP2D);
            $row[] = number_format($total, 0, ",", ".");
            $row[] = html_escape($h->UID) == 0 ? "" : number_format(html_escape($h->UID), 0, ",", ".");
            $row[] = html_escape($h->BDG) == 0 ? "" : number_format(html_escape($h->BDG), 0, ",", ".");
            $row[] = html_escape($h->BLG) == 0 ? "" : number_format(html_escape($h->BLG), 0, ",", ".");
            $row[] = html_escape($h->BTR) == 0 ? "" : number_format(html_escape($h->BTR), 0, ",", ".");
            $row[] = html_escape($h->CKG) == 0 ? "" : number_format(html_escape($h->CKG), 0, ",", ".");
            $row[] = html_escape($h->CPP) == 0 ? "" : number_format(html_escape($h->CPP), 0, ",", ".");
            $row[] = html_escape($h->CPT) == 0 ? "" : number_format(html_escape($h->CPT), 0, ",", ".");
            $row[] = html_escape($h->CRC) == 0 ? "" : number_format(html_escape($h->CRC), 0, ",", ".");
            $row[] = html_escape($h->JTN) == 0 ? "" : number_format(html_escape($h->JTN), 0, ",", ".");
            $row[] = html_escape($h->KBJ) == 0 ? "" : number_format(html_escape($h->KBJ), 0, ",", ".");
            $row[] = html_escape($h->KJT) == 0 ? "" : number_format(html_escape($h->KJT), 0, ",", ".");
            $row[] = html_escape($h->LTA) == 0 ? "" : number_format(html_escape($h->LTA), 0, ",", ".");
            $row[] = html_escape($h->MRD) == 0 ? "" : number_format(html_escape($h->MRD), 0, ",", ".");
            $row[] = html_escape($h->MTG) == 0 ? "" : number_format(html_escape($h->MTG), 0, ",", ".");
            $row[] = html_escape($h->PDG) == 0 ? "" : number_format(html_escape($h->PDG), 0, ",", ".");
            $row[] = html_escape($h->PDK) == 0 ? "" : number_format(html_escape($h->PDK), 0, ",", ".");
            $row[] = html_escape($h->TJP) == 0 ? "" : number_format(html_escape($h->TJP), 0, ",", ".");
            $row[] = html_escape($h->UP2D) == 0 ? "" : number_format(html_escape($h->UP2D), 0, ",", ".");
            $data['data'][] = $row;
        }

		echo json_encode($data);
    }

    function ExportDashboard(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Unit');

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

        $hasil = $this->getDashboard();

        $sheet->getStyle('A1:X1')->applyFromArray($styleHeading);

        foreach(range('A', 'X') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Material ID');
        $sheet->setCellValue('C1', 'Kategori');
        $sheet->setCellValue('D1', 'Material');
        $sheet->setCellValue('E1', 'Satuan');
        $sheet->setCellValue('F1', 'Total');
        $sheet->setCellValue('G1', 'UID');
        $sheet->setCellValue('H1', 'BDG');
        $sheet->setCellValue('I1', 'BLG');
        $sheet->setCellValue('J1', 'BTR');
        $sheet->setCellValue('K1', 'CKG');
        $sheet->setCellValue('L1', 'CPP');
        $sheet->setCellValue('M1', 'CPT');
        $sheet->setCellValue('N1', 'CRC');
        $sheet->setCellValue('O1', 'JTN');
        $sheet->setCellValue('P1', 'KBJ');
        $sheet->setCellValue('Q1', 'KJT');
        $sheet->setCellValue('R1', 'LTA');
        $sheet->setCellValue('S1', 'MRD');
        $sheet->setCellValue('T1', 'MTG');
        $sheet->setCellValue('U1', 'PDG');
        $sheet->setCellValue('V1', 'PDK');
        $sheet->setCellValue('W1', 'TJP');
        $sheet->setCellValue('X1', 'UP2D');

        $i = 2;
        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->material_id));
            $sheet->setCellValue('C' . $i, html_escape($h->kategori));
            $sheet->setCellValue('D' . $i, html_escape($h->material));
            $sheet->setCellValue('E' . $i, html_escape($h->satuan));
            $total = html_escape($h->BDG) + html_escape($h->BLG) + html_escape($h->BTR) + html_escape($h->CKG) + html_escape($h->CPP) + html_escape($h->CPT) + html_escape($h->CRC) + html_escape($h->JTN) + html_escape($h->KBJ) + html_escape($h->KJT) + html_escape($h->LTA) + html_escape($h->MRD) + html_escape($h->MTG) + html_escape($h->PDG) + html_escape($h->PDK) + html_escape($h->TJP) + html_escape($h->UID) + html_escape($h->UP2D);
            $sheet->setCellValue('F' . $i, $total);
            $sheet->setCellValue('G' . $i, html_escape($h->UID) == 0 ? "" : html_escape($h->UID));
            $sheet->setCellValue('H' . $i, html_escape($h->BDG) == 0 ? "" : html_escape($h->BDG));
            $sheet->setCellValue('I' . $i, html_escape($h->BLG) == 0 ? "" : html_escape($h->BLG));
            $sheet->setCellValue('J' . $i, html_escape($h->BTR) == 0 ? "" : html_escape($h->BTR));
            $sheet->setCellValue('K' . $i, html_escape($h->CKG) == 0 ? "" : html_escape($h->CKG));
            $sheet->setCellValue('L' . $i, html_escape($h->CPP) == 0 ? "" : html_escape($h->CPP));
            $sheet->setCellValue('M' . $i, html_escape($h->CPT) == 0 ? "" : html_escape($h->CPT));
            $sheet->setCellValue('N' . $i, html_escape($h->CRC) == 0 ? "" : html_escape($h->CRC));
            $sheet->setCellValue('O' . $i, html_escape($h->JTN) == 0 ? "" : html_escape($h->JTN));
            $sheet->setCellValue('P' . $i, html_escape($h->KBJ) == 0 ? "" : html_escape($h->KBJ));
            $sheet->setCellValue('Q' . $i, html_escape($h->KJT) == 0 ? "" : html_escape($h->KJT));
            $sheet->setCellValue('R' . $i, html_escape($h->LTA) == 0 ? "" : html_escape($h->LTA));
            $sheet->setCellValue('S' . $i, html_escape($h->MRD) == 0 ? "" : html_escape($h->MRD));
            $sheet->setCellValue('T' . $i, html_escape($h->MTG) == 0 ? "" : html_escape($h->MTG));
            $sheet->setCellValue('U' . $i, html_escape($h->PDG) == 0 ? "" : html_escape($h->PDG));
            $sheet->setCellValue('V' . $i, html_escape($h->PDK) == 0 ? "" : html_escape($h->PDK));
            $sheet->setCellValue('W' . $i, html_escape($h->TJP) == 0 ? "" : html_escape($h->TJP));
            $sheet->setCellValue('X' . $i, html_escape($h->UP2D) == 0 ? "" : html_escape($h->UP2D));
            $i++;
        }

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Bulanan');

        $hasil = $this->getDashboardMonth();

        $sheet2->getStyle('A1:R1')->applyFromArray($styleHeading);

        foreach(range('A', 'R') as $columnID) {
            $sheet2->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet2->setCellValue('A1', 'No');
        $sheet2->setCellValue('B1', 'Material ID');
        $sheet2->setCellValue('C1', 'Kategori');
        $sheet2->setCellValue('D1', 'Material');
        $sheet2->setCellValue('E1', 'Satuan');
        $sheet2->setCellValue('F1', 'Total');
        $sheet2->setCellValue('G1', 'JAN');
        $sheet2->setCellValue('H1', 'FEB');
        $sheet2->setCellValue('I1', 'MAR');
        $sheet2->setCellValue('J1', 'APR');
        $sheet2->setCellValue('K1', 'MEI');
        $sheet2->setCellValue('L1', 'JUNI');
        $sheet2->setCellValue('M1', 'JULI');
        $sheet2->setCellValue('N1', 'AGTS');
        $sheet2->setCellValue('O1', 'SEPT');
        $sheet2->setCellValue('P1', 'OKT');
        $sheet2->setCellValue('Q1', 'NOV');
        $sheet2->setCellValue('R1', 'DES');

        $i = 2;
        foreach ($hasil as $h) {
            $sheet2->setCellValue('A' . $i, $i-1);
            $sheet2->setCellValue('B' . $i, html_escape($h->material_id));
            $sheet2->setCellValue('C' . $i, html_escape($h->kategori));
            $sheet2->setCellValue('D' . $i, html_escape($h->material));
            $sheet2->setCellValue('E' . $i, html_escape($h->satuan));
            $total = html_escape($h->JAN) + html_escape($h->FEB) + html_escape($h->MAR) + html_escape($h->APR) + html_escape($h->MEI) + html_escape($h->JUNI) + html_escape($h->JULI) + html_escape($h->AGTS) + html_escape($h->SEPT) + html_escape($h->OKT) + html_escape($h->NOV) + html_escape($h->DES);
            $sheet2->setCellValue('F' . $i, $total);
            $sheet2->setCellValue('G' . $i, html_escape($h->JAN) == 0 ? "" : html_escape($h->JAN));
            $sheet2->setCellValue('H' . $i, html_escape($h->FEB) == 0 ? "" : html_escape($h->FEB));
            $sheet2->setCellValue('I' . $i, html_escape($h->MAR) == 0 ? "" : html_escape($h->MAR));
            $sheet2->setCellValue('J' . $i, html_escape($h->APR) == 0 ? "" : html_escape($h->APR));
            $sheet2->setCellValue('K' . $i, html_escape($h->MEI) == 0 ? "" : html_escape($h->MEI));
            $sheet2->setCellValue('L' . $i, html_escape($h->JUNI) == 0 ? "" : html_escape($h->JUNI));
            $sheet2->setCellValue('M' . $i, html_escape($h->JULI) == 0 ? "" : html_escape($h->JULI));
            $sheet2->setCellValue('N' . $i, html_escape($h->AGTS) == 0 ? "" : html_escape($h->AGTS));
            $sheet2->setCellValue('O' . $i, html_escape($h->SEPT) == 0 ? "" : html_escape($h->SEPT));
            $sheet2->setCellValue('P' . $i, html_escape($h->OKT) == 0 ? "" : html_escape($h->OKT));
            $sheet2->setCellValue('Q' . $i, html_escape($h->NOV) == 0 ? "" : html_escape($h->NOV));
            $sheet2->setCellValue('R' . $i, html_escape($h->DES) == 0 ? "" : html_escape($h->DES));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Dashboard_Kontrak_Rinci_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function getDashboardMonth(){
        $where = "";
        if($this->input->post('basket', true) != "*"){
            $where = "WHERE id_basket = '" . $this->input->post('basket', true) . "'";
        }
        if($this->input->post('tahun_anggaran', true) != "*"){
            $where .= $where == "" ? " WHERE " : " AND ";
            $where .= "tahun_anggaran = '" . $this->input->post('tahun_anggaran', true) . "'";
        }
        if($this->input->post('is_murni', true) != "*"){
            $where .= $where == "" ? " WHERE " : " AND ";
            $where .= "is_murni = '" . $this->input->post('is_murni', true) . "'";
        }
        $query = "WITH data AS (
                SELECT * FROM vw_kontrak_dtl $where
            )
            SELECT
                material_id,
                material,
                kategori,
                satuan,
                SUM( CASE WHEN MONTH(awal_kontrak) = 1 THEN volume ELSE 0 END ) AS JAN,
                SUM( CASE WHEN MONTH(awal_kontrak) = 2 THEN volume ELSE 0 END ) AS FEB,
                SUM( CASE WHEN MONTH(awal_kontrak) = 3 THEN volume ELSE 0 END ) AS MAR,
                SUM( CASE WHEN MONTH(awal_kontrak) = 4 THEN volume ELSE 0 END ) AS APR,
                SUM( CASE WHEN MONTH(awal_kontrak) = 5 THEN volume ELSE 0 END ) AS MEI,
                SUM( CASE WHEN MONTH(awal_kontrak) = 6 THEN volume ELSE 0 END ) AS JUNI,
                SUM( CASE WHEN MONTH(awal_kontrak) = 7 THEN volume ELSE 0 END ) AS JULI,
                SUM( CASE WHEN MONTH(awal_kontrak) = 8 THEN volume ELSE 0 END ) AS AGTS,
                SUM( CASE WHEN MONTH(awal_kontrak) = 9 THEN volume ELSE 0 END ) AS SEPT,
                SUM( CASE WHEN MONTH(awal_kontrak) = 10 THEN volume ELSE 0 END ) AS OKT,
                SUM( CASE WHEN MONTH(awal_kontrak) = 11 THEN volume ELSE 0 END ) AS NOV,
                SUM( CASE WHEN MONTH(awal_kontrak) = 12 THEN volume ELSE 0 END ) AS DES
            FROM
                data
            GROUP BY
                material_id,
                material,
                kategori,
                satuan";

        return $this->M_AllFunction->CustomQuery($query);
    }

    function ajaxDashboardMonth(){
        $hasil = $this->getDashboardMonth();

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

			$row[] = $i++;
			$row[] = html_escape($h->material_id);
			$row[] = html_escape($h->kategori);
			$row[] = html_escape($h->material);
			$row[] = html_escape($h->satuan);
            $total = html_escape($h->JAN) + html_escape($h->FEB) + html_escape($h->MAR) + html_escape($h->APR) + html_escape($h->MEI) + html_escape($h->JUNI) + html_escape($h->JULI) + html_escape($h->AGTS) + html_escape($h->SEPT) + html_escape($h->OKT) + html_escape($h->NOV) + html_escape($h->DES);
            $row[] = number_format($total, 0, ",", ".");
            $row[] = html_escape($h->JAN) == 0 ? "" : number_format(html_escape($h->JAN), 0, ",", ".");
            $row[] = html_escape($h->FEB) == 0 ? "" : number_format(html_escape($h->FEB), 0, ",", ".");
            $row[] = html_escape($h->MAR) == 0 ? "" : number_format(html_escape($h->MAR), 0, ",", ".");
            $row[] = html_escape($h->APR) == 0 ? "" : number_format(html_escape($h->APR), 0, ",", ".");
            $row[] = html_escape($h->MEI) == 0 ? "" : number_format(html_escape($h->MEI), 0, ",", ".");
            $row[] = html_escape($h->JUNI) == 0 ? "" : number_format(html_escape($h->JUNI), 0, ",", ".");
            $row[] = html_escape($h->JULI) == 0 ? "" : number_format(html_escape($h->JULI), 0, ",", ".");
            $row[] = html_escape($h->AGTS) == 0 ? "" : number_format(html_escape($h->AGTS), 0, ",", ".");
            $row[] = html_escape($h->SEPT) == 0 ? "" : number_format(html_escape($h->SEPT), 0, ",", ".");
            $row[] = html_escape($h->OKT) == 0 ? "" : number_format(html_escape($h->OKT), 0, ",", ".");
            $row[] = html_escape($h->NOV) == 0 ? "" : number_format(html_escape($h->NOV), 0, ",", ".");
            $row[] = html_escape($h->DES) == 0 ? "" : number_format(html_escape($h->DES), 0, ",", ".");
            $data['data'][] = $row;
        }

		echo json_encode($data);
    }

    function Alokasi_Penyedia(){
        $data['kategori'] = $this->M_AllFunction->Get('mst_material_hdr');
        $data['material'] = $this->M_AllFunction->Get('vw_material');
        $data['vendor'] = $this->M_AllFunction->Get('mst_vendor');
        $this->template->display('kontrak/alokasi_pemasaran', $data);
    }

    function getAlokasiPemasaran(){
        $hasil = $this->M_Kontrak->getAlokasiPemasaran();

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->material_id);
            $row[] = html_escape($h->nomor_khs);
            $row[] = html_escape($h->vendor);
            $row[] = html_escape($h->kategori);
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->satuan);
            $row[] = number_format(html_escape($h->alokasi), 0, ",", ".");
            $row[] = number_format(html_escape($h->jumlah), 0, ",", ".");
            $row[] = html_escape($h->alokasi) == 0 ? 0 : number_format(html_escape($h->jumlah) / html_escape($h->alokasi) * 100, 2, ",", ".") . "%";

            $data['data'][] = $row;
        }

		echo json_encode($data);
    }

    function ExportPabrikan(){
        $hasil = $this->M_Kontrak->exportData();

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
        $sheet->getStyle('E')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'VENDOR');
        $sheet->setCellValue('B1', 'NO KHS');
        $sheet->setCellValue('C1', 'NORMALISASI');
        $sheet->setCellValue('D1', 'MATERIAL');
        $sheet->setCellValue('E1', 'JUMLAH');
        $sheet->setCellValue('F1', 'SATUAN');

        $i = 2;
        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, html_escape($h->vendor));
            $sheet->setCellValue('B' . $i, html_escape($h->nomor_khs));
            $sheet->setCellValue('C' . $i, html_escape($h->material_id));
            $sheet->setCellValue('D' . $i, html_escape($h->material));
            $sheet->setCellValue('E' . $i, html_escape($h->jumlah));
            $sheet->setCellValue('F' . $i, html_escape($h->satuan));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Daftar_Pabrikan_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function KontrakRinci() {
        $data['basket'] = $this->M_AllFunction->Get('mst_basket');
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $data['vendor'] = $this->M_AllFunction->Get('mst_vendor');
        $data['material'] = $this->M_AllFunction->CustomQuery("SELECT id, material, satuan FROM vw_material");
        $data['khs'] = $this->M_AllFunction->Where('vw_khs', "nomor_khs <> ''");
        $data['prk'] = $this->M_AllFunction->Get('vw_prk');
        $data['skki'] = $this->M_AllFunction->Get('trn_skki_hdr');
        $this->template->display('kontrak/main', $data);
    }

    function ajaxKontrak() {
        $tahun = $this->input->post('tahun', true);

        $hasil = $this->M_AllFunction->CustomQuery("SELECT * FROM vw_kontrak WHERE tahun_anggaran = '$tahun' ORDER BY awal_kontrak DESC");

        $data['data'] = array();

        $i = 1;
        $total = 0;
        $total_durasi = 0;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->basket);
            $row[] = html_escape($h->tahun_anggaran);
            $row[] = html_escape($h->is_murni) == 1 ? "MURNI" : "LUNCURAN";
            $row[] = html_escape($h->no_skki);
            $row[] = html_escape($h->no_prk);
            $row[] = html_escape($h->nomor_khs);
            $row[] = html_escape($h->no_kontrak);
            $row[] = html_escape($h->vendor);
            $row[] = html_escape($h->kategori);
            $row[] = "<button onclick='getMaterialKontrak(\"" . html_escape($h->no_kontrak) . "\")' class=\"btn btn-secondary btn-sm w-100\" data-bs-toggle=\"modal\" data-bs-target=\"#kt_modal_material\">" . html_escape($h->material) . "</button>";

            $total += html_escape($h->nilai_kontrak);
            $row[] = "<button onclick='getDetailNilai(\"" . html_escape($h->no_kontrak) . "\")' class=\"btn btn-secondary btn-sm w-100\" data-bs-toggle=\"modal\" data-bs-target=\"#kt_modal_nilai\">" . number_format(html_escape($h->nilai_kontrak), 0, ',', '.') . "</button>";

            $row[] = html_escape($h->awal_kontrak);
            $row[] = html_escape($h->akhir_kontrak);
            $row[] = html_escape($h->is_using_jm) == 0 ? "TANPA BANK GARANSI" : html_escape($h->nomor_bae);
            $row[] = html_escape($h->bae_awal);
            $row[] = html_escape($h->bae_akhir);
            $row[] = empty(html_escape($h->file_kr)) ? "" : "<a href=\"" . base_url() . html_escape($h->file_kr_location) . '/' . html_escape($h->file_kr) . "\" class=\"btn btn-text-danger btn-hover-light-danger btn-sm\" target=\"_blank\"> <i class=\"fa fa-file-pdf\"></i> PDF";
            $row[] = empty(html_escape($h->file_bae)) ? "" : "<a href=\"" . base_url() . html_escape($h->file_bae_location) . '/' . html_escape($h->file_bae) . "\" class=\"btn btn-text-danger btn-hover-light-danger btn-sm\" target=\"_blank\"> <i class=\"fa fa-file-pdf\"></i> PDF";

            // INI ADA RUMUS DI QUERY DAN DI PHP TERKAIT STATUS KONTRAKNYA
            if (html_escape($h->status_kontrak) == "SELESAI") {
                $row[] = "<span class='badge bg-primary'>SELESAI</span>";
            } else {
                if (html_escape($h->status_kirim) == 'SELESAI KIRIM') {
                    $row[] = "<span class='badge bg-primary'>IN PROGRESS</span>";
                } else {
                    if (html_escape($h->status_kontrak) == "IN PROGRESS") {
                        $row[] = "<span class='badge bg-primary'>" . html_escape($h->status_kontrak) . "</span>";
                    } else {
                        $row[] = "<span class='badge bg-danger'>" . html_escape($h->status_kontrak) . "</span>";
                    }
                }
            }

            if(html_escape($h->status_kirim) == "BELUM KIRIM"){
                $row[] = "<span class='badge bg-danger'>" . html_escape($h->status_kirim) . "</span>";
            } else if(html_escape($h->status_kirim) == "PROSES KIRIM"){
                $row[] = "<span class='badge bg-warning'>" . html_escape($h->status_kirim) . "</span>";
            } else {
                $row[] = "<span class='badge bg-primary'>" . html_escape($h->status_kirim) . "</span>";
            }

            $row[] = html_escape($h->status_bayar) == "BELUM BAYAR"
                        ? "<span class=\"badge bg-danger\">" . html_escape($h->status_bayar) . "</span>"
                        : "<span class=\"badge bg-primary\">" . html_escape($h->status_bayar) . "</span>";

            $row[] = "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">
                            <button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"editdata('" . html_escape($h->id) . "')\" data-bs-toggle=\"modal\" data-bs-target=\"#modal_edit\"><i class=\"fa fa-pencil\"></i></button>
                            <button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"delete_data('" . html_escape($h->id) . "', '" . $h->no_kontrak . "')\"><i class=\"fa fa-trash\"></i></button>
                        </div>";
            $data['data'][] = $row;
        }
        $data['total'] = number_format($total, 0, ',', '.');

        echo json_encode($data);
    }

    function ExportKontrakRinci() {
        $tahun = $this->input->post('tahun', true);
        $data = $this->M_AllFunction->CustomQuery("SELECT * FROM vw_kontrak WHERE tahun_anggaran = '$tahun' ORDER BY awal_kontrak DESC");

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

        $sheet->getStyle('A1:S1')->applyFromArray($styleHeading);

        foreach(range('A', 'S') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'BASKET')
            ->setCellValue('C1', 'TAHUN')
            ->setCellValue('D1', 'JENIS ANGGARAN')
            ->setCellValue('E1', 'NO SKKI')
            ->setCellValue('F1', 'NO PRK')
            ->setCellValue('G1', 'NO KHS')
            ->setCellValue('H1', 'NO KONTRAK')
            ->setCellValue('I1', 'VENDOR')
            ->setCellValue('J1', 'KATEGORI MATERIAL')
            ->setCellValue('K1', 'MATERIAL')
            ->setCellValue('L1', 'NILAI KONTRAK')
            ->setCellValue('M1', 'AWAL KONTRAK')
            ->setCellValue('N1', 'AKHIR KONTRAK')
            ->setCellValue('O1', 'NOMOR BAE')
            ->setCellValue('P1', 'AWAL BAE')
            ->setCellValue('Q1', 'AKHIR BAE')
            ->setCellValue('R1', 'STATUS KIRIM')
            ->setCellValue('S1', 'STATUS BAYAR');

        $i = 1;
        $row = 2;
        foreach ($data as $d) {
            $sheet->setCellValue('A'.$row, $i++)
                ->setCellValue('B'.$row, html_escape($d->basket))
                ->setCellValue('C'.$row, html_escape($d->tahun_anggaran))
                ->setCellValue('D'.$row, html_escape($d->is_murni) == 1 ? "MURNI" : "LUNCURAN")
                ->setCellValue('E'.$row, html_escape($d->no_skki))
                ->setCellValue('F'.$row, html_escape($d->no_prk))
                ->setCellValue('G'.$row, html_escape($d->nomor_khs))
                ->setCellValue('H'.$row, html_escape($d->no_kontrak))
                ->setCellValue('I'.$row, html_escape($d->vendor))
                ->setCellValue('J'.$row, html_escape($d->kategori))
                ->setCellValue('K'.$row, html_escape($d->material))
                ->setCellValue('L'.$row, html_escape($d->nilai_kontrak))
                ->setCellValue('M'.$row, html_escape($d->awal_kontrak))
                ->setCellValue('N'.$row, html_escape($d->akhir_kontrak))
                ->setCellValue('O'.$row, html_escape($d->nomor_bae))
                ->setCellValue('P'.$row, html_escape($d->bae_awal))
                ->setCellValue('Q'.$row, html_escape($d->bae_akhir))
                ->setCellValue('R'.$row, html_escape($d->status_kirim) == "BELUM KIRIM" ? "BELUM KIRIM" : (html_escape($d->status_kirim) == "PROSES KIRIM" ? "PROSES KIRIM" : "SUDAH KIRIM"))
                ->setCellValue('S'.$row, html_escape($d->status_bayar) == "BELUM BAYAR" ? "BELUM BAYAR" : "SUDAH BAYAR");
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Kontrak_Rinci_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function delete_data() {
        $id = $this->input->get("id");
        $this->M_AllFunction->Delete('trn_kontrak_hdr', "id = '" . $id . "'");
        $this->M_AllFunction->Delete('trn_kontrak_dtl', "id_hdr = '" . $id . "'");
        $this->M_AllFunction->Delete('trn_kontrak_material', "id_hdr = '" . $id . "'");
        echo json_encode(array("status" => "success", "msg" => "Berhasil Hapus Data"));
    }

    function getMaterialKontrak() {
        $id = $this->input->get('id');
        $data['data'] = $this->M_AllFunction->CustomQueryArray("SELECT * FROM vw_kontrak_dtl WHERE no_kontrak = '$id'");
        $this->load->view('kontrak/index_material', $data);
    }

    function getDetailNilai() {
        $id = $this->input->get('id');
        $query = "SELECT
                    trn_kontrak_material.*,
                    vw_material.material,
                    vw_material.kategori,
                    vw_material.satuan
                FROM
                    trn_kontrak_material
                LEFT JOIN vw_material
                ON trn_kontrak_material.material_id = vw_material.id
                WHERE
                    trn_kontrak_material.no_kontrak = '$id'";
        $data['data'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('kontrak/main_nilai', $data);
    }

    function Save() {
        $config['allowed_types'] = 'pdf';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;

        $filekontrakname = "";
        $file_kr_location = 'data_uploads/kontrak/kr/';

        $config['upload_path'] = $file_kr_location;
        $config['file_name'] = "kr-" . bin2hex(random_bytes(24));
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('filekr')) {
            $data = array('upload_data' => $this->upload->data());
            $filekontrakname = $data['upload_data']['file_name'];
        }

        $data                  =  array(
            "id_basket"         => $this->input->post('id_basket', true),
            "tahun_anggaran"    => $this->input->post('tahun_anggaran', true),
            "is_murni"          => $this->input->post('is_murni', true),
            "id_skki"           => $this->input->post('id_skki', true),
            "no_prk"            => $this->input->post('no_prk', true),
            "no_khs"            => $this->input->post('no_khs', true),
            "no_kontrak"        => $this->input->post('no_kontrak', true),
            "no_po"             => $this->input->post('no_po', true),
            "id_vendor"         => $this->input->post('id_vendor', true),
            "awal_kontrak"      => $this->input->post('awal_kontrak', true),
            "akhir_kontrak"     => $this->input->post('akhir_kontrak', true),
            "nilai_kontrak"     => preg_replace('/[^0-9]/', '', $this->input->post('nilai_kontrak', true)),
            "is_using_jm"       => $this->input->post('is_using_jm', true),
            "file_kr"           => $filekontrakname,
            "file_kr_location"  => $file_kr_location,
            "createdby"         => $this->session->userdata('username'),
            "createddate"       => date('Y-m-d H:i:s')
        );

        $id = $this->M_AllFunction->InsertGetId('trn_kontrak_hdr', $data);

        for ($i = 0; $i < count($this->input->post('unit_tujuan_id', true)); $i++) {
            $datadetail[$i] = array(
                "id_hdr"         => $id,
                "no_kontrak"     => $this->input->post('no_kontrak', true),
                "unit_tujuan_id" => $this->input->post('unit_tujuan_id', true)[$i],
                "material_id"    => $this->input->post('material', true)[$i],
                "volume"         => $this->input->post('volume', true)[$i]
            );
        }

        $this->M_AllFunction->InsertBatch('trn_kontrak_dtl', $datadetail);

        $material = array_unique($this->input->post('material', true));
        $arr_no = 0;

        foreach ($material as $m) {
            $index  = array_search($m, $material);

            $total_vol = 0;
            foreach($datadetail as $dtl){
                if($m == $dtl['material_id']){
                    $total_vol += $dtl['volume'];
                }
            }

            $dataMaterial[$arr_no++] = array(
                "id_hdr"      => $id,
                "no_kontrak"  => $this->input->post('no_kontrak', true),
                "material_id" => $m,
                "volume"      => $total_vol,
                "harga"       => $this->input->post('harga', true)[$index],
                "ongkir"      => $this->input->post('ongkos', true)[$index],
                "total"       => $this->input->post('total', true)[$index]
            );
        }

        $this->M_AllFunction->InsertBatch('trn_kontrak_material', $dataMaterial);
        redirect("C_Kontrak/KontrakRinci");
    }

    function ImportSave() {
        $config['allowed_types'] = 'xls|xlsx';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;

        $filekontrakname = "";
        $file_kr_location = 'data_uploads/kontrak/import/';

        $config['upload_path'] = $file_kr_location;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('fileimport')) {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('flash_failed', $error['error']);
        } else {
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            if (isset($_FILES['fileimport']['name']) && in_array($_FILES['fileimport']['type'], $file_mimes)) {
                $arr_file = explode('.', $_FILES['fileimport']['name']);
                $extension = end($arr_file);
                if ('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else if ('xls' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                $spreadsheet = $reader->load($_FILES['fileimport']['tmp_name']);
                $header = $spreadsheet->getSheet(0)->toArray();
                $detail = $spreadsheet->getSheet(1)->toArray();

                for ($i=0; $i < count($header); $i++) {
                    if($i > 0 && $header[$i][11] != ""){
                        $data                  =  array(
                            "id_basket"         => $header[$i][0],
                            "tahun_anggaran"    => $header[$i][2],
                            "is_murni"          => $header[$i][3],
                            "id_skki"           => $header[$i][5],
                            "no_prk"            => $header[$i][7],
                            "no_khs"            => $header[$i][9],
                            "no_kontrak"        => $header[$i][11],
                            "no_po"             => null,
                            "id_vendor"         => $header[$i][12],
                            "awal_kontrak"      => $header[$i][14],
                            "akhir_kontrak"     => $header[$i][15],
                            "nilai_kontrak"     => null,
                            "is_using_jm"       => 0,
                            "file_kr"           => null,
                            "file_kr_location"  => null,
                            "is_import"         => 1,
                            "createdby"         => $this->session->userdata('username'),
                            "createddate"       => date('Y-m-d H:i:s')
                        );

                        $id = $this->M_AllFunction->InsertGetId('trn_kontrak_hdr', $data);
                        $nilai_kontrak = 0;

                        for($j = 0; $j < count($detail); $j++){
                            if ($j > 0 && $detail[$j][0] == $header[$i][11]) {
                                $datadetail = array(
                                    "id_hdr"         => $id,
                                    "no_kontrak"     => $detail[$j][0],
                                    "unit_tujuan_id" => $detail[$j][1],
                                    "material_id"    => $detail[$j][3],
                                    "volume"         => str_replace(['.', ','], '', $detail[$j][4]), // $detail[$j][4]
                                );
                                $this->M_AllFunction->Insert('trn_kontrak_dtl', $datadetail);
                            }
                        }

                        $dataMaterial = array();
                        $material = array();
                        foreach ($detail as $d) {
                            if($d[0] == $header[$i][11]){
                                $volume     = str_replace(['.', ','], '', $d[4]);
                                $harga      = str_replace(['.', ','], '', $d[5]);
                                $ongkir     = str_replace(['.', ','], '', $d[6]);
                                $total      = ($volume * ($harga + $ongkir)) * 1.11;
                                if(!in_array($d[3], $material)){
                                    $material[] = $d[3];
                                    $dataMaterial[] = array(
                                        "id_hdr"      => $id,
                                        "no_kontrak"  => $d[0],
                                        "material_id" => $d[3],
                                        "volume"      => $volume,
                                        "harga"       => $harga,
                                        "ongkir"      => $ongkir,
                                        "total"       => $total
                                    );
                                } else {
                                    $index  = array_search($d[3], $material);
                                    $dataMaterial[$index]['volume'] += $volume;
                                    $dataMaterial[$index]['total'] += $total;
                                }
                                $nilai_kontrak += $total;
                            }
                        }

                        $this->M_AllFunction->InsertBatch('trn_kontrak_material', $dataMaterial);
                        $this->M_AllFunction->Update('trn_kontrak_hdr', array('nilai_kontrak' => $nilai_kontrak), "id = $id");
                    }
                }
            }
        }
        redirect("C_Kontrak/KontrakRinci");
    }

    function get_data_edit() {
        $id = $this->input->post('id', true);
        $data = $this->M_AllFunction->Where('vw_kontrak', "id = '$id'");
        echo json_encode($data);
    }

    function Edit() {
        $config['allowed_types'] = 'pdf';
        $config['remove_spaces'] = TRUE;
        $config['max_size']      = 10000;

        $filekontrakname = "";
        $file_kr_location = 'data_uploads/kontrak/kr/';

        $config['upload_path'] = $file_kr_location;
        $config['file_name'] = "kr-" . bin2hex(random_bytes(24));
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('filekr')) {
            $data = array('upload_data' => $this->upload->data());
            $filekontrakname = $data['upload_data']['file_name'];
        }

        $data = array(
            "id_basket"      => $this->input->post('id_basket_edit', true),
            "tahun_anggaran" => $this->input->post('tahun_anggaran_edit', true),
            "is_murni"       => $this->input->post('is_murni_edit', true),
            "id_skki"        => $this->input->post('id_skki_edit', true),
            "no_prk"         => $this->input->post('no_prk_edit', true),
            "no_po"          => $this->input->post('nomor_po_edit', true),
            "no_khs"         => $this->input->post('no_khs_edit', true),
            "is_using_jm"    => $this->input->post('is_using_jm_edit', true),
            "updatedby"      => $this->session->userdata('username'),
            "updateddate"    => date('Y-m-d H:i:s')
        );

        if($filekontrakname != ""){
            $data['file_kr'] = $filekontrakname;
            $data['file_kr_location'] = $file_kr_location;
        }

        $this->M_AllFunction->Update('trn_kontrak_hdr', $data, "id = '" . $this->input->post('id_edit', true) . "'");
        $this->session->set_flashdata('flash_success', 'Berhasil Update Data.');
        redirect("C_Kontrak/KontrakRinci");
    }

    // // ========================= REGION KONTRAK RINCI UP3 ================================

    function KontrakRinciUP3() {
        $data['basket'] = $this->M_AllFunction->Get('mst_basket');
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $data['vendor'] = $this->M_AllFunction->Get('mst_vendor');
        $data['material'] = $this->M_AllFunction->CustomQuery("SELECT id, material, satuan FROM vw_material");
        $data['khs'] = $this->M_AllFunction->Where('vw_khs', "nomor_khs <> ''");
        $data['prk'] = $this->M_AllFunction->Where('vw_prk', "tahun = '" . date('Y') . "'");
        $data['skki'] = $this->M_AllFunction->Where('trn_skki_hdr', "unit = '" . $this->session->userdata('unit_id') . "'");
        $this->template->display('kontrak_up3/index', $data);
    }

    function ajaxKontrakUP3() {
        $tahun = $this->input->post('tahun', true);

        $hasil = $this->M_AllFunction->CustomQuery("SELECT * FROM vw_kontrak_up3 WHERE tahun_anggaran = '$tahun' ORDER BY awal_kontrak DESC");

        $data['data'] = array();

        $i = 1;
        $total = 0;
        $total_durasi = 0;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->name);
            $row[] = html_escape($h->basket);
            $row[] = html_escape($h->tahun_anggaran);
            $row[] = html_escape($h->is_murni) == 1 ? "MURNI" : "LUNCURAN";
            $row[] = html_escape($h->no_skki);
            $row[] = html_escape($h->no_prk);
            $row[] = html_escape($h->nomor_khs);
            $row[] = html_escape($h->no_kontrak);
            $row[] = html_escape($h->vendor);
            $row[] = '<a href="' . html_escape($h->lokasi) . '" target="_blank" class="btn btn-primary btn-sm w-100"><i class="fa fa-location-dot"></i></a>';
            $row[] = html_escape($h->kategori);
            $row[] = "<button onclick='getMaterialKontrak(\"" . html_escape($h->no_kontrak) . "\")' class=\"btn btn-secondary btn-sm w-100\" data-bs-toggle=\"modal\" data-bs-target=\"#kt_modal_material\">" . html_escape($h->material) . "</button>";

            $total += html_escape($h->nilai_kontrak);
            $row[] = "<button onclick='getDetailNilai(\"" . html_escape($h->no_kontrak) . "\")' class=\"btn btn-secondary btn-sm w-100\" data-bs-toggle=\"modal\" data-bs-target=\"#kt_modal_nilai\">" . number_format(html_escape($h->nilai_kontrak), 0, ',', '.') . "</button>";

            $row[] = html_escape($h->awal_kontrak);
            $row[] = html_escape($h->akhir_kontrak);
            $row[] = html_escape($h->is_using_jm) == 0 ? "TANPA BANK GARANSI" : html_escape($h->nomor_bae);
            $row[] = html_escape($h->bae_awal);
            $row[] = html_escape($h->bae_akhir);
            $row[] = empty(html_escape($h->file_kr)) ? "" : "<a href=\"" . base_url() . html_escape($h->file_kr_location) . '/' . html_escape($h->file_kr) . "\" class=\"btn btn-text-danger btn-hover-light-danger btn-sm\" target=\"_blank\"> <i class=\"fa fa-file-pdf\"></i> PDF";
            $row[] = empty(html_escape($h->file_bae)) ? "" : "<a href=\"" . base_url() . html_escape($h->file_bae_location) . '/' . html_escape($h->file_bae) . "\" class=\"btn btn-text-danger btn-hover-light-danger btn-sm\" target=\"_blank\"> <i class=\"fa fa-file-pdf\"></i> PDF";

            // INI ADA RUMUS DI QUERY DAN DI PHP TERKAIT STATUS KONTRAKNYA
            if (html_escape($h->status_kontrak) == "SELESAI") {
                $row[] = "<span class='badge bg-primary'>SELESAI</span>";
            } else {
                if (html_escape($h->status_kirim) == 'SELESAI KIRIM') {
                    $row[] = "<span class='badge bg-primary'>IN PROGRESS</span>";
                } else {
                    if (html_escape($h->status_kontrak) == "IN PROGRESS") {
                        $row[] = "<span class='badge bg-primary'>" . html_escape($h->status_kontrak) . "</span>";
                    } else {
                        $row[] = "<span class='badge bg-danger'>" . html_escape($h->status_kontrak) . "</span>";
                    }
                }
            }

            if(html_escape($h->status_kirim) == "BELUM KIRIM"){
                $row[] = "<span class='badge bg-danger'>" . html_escape($h->status_kirim) . "</span>";
            } else if(html_escape($h->status_kirim) == "PROSES KIRIM"){
                $row[] = "<span class='badge bg-warning'>" . html_escape($h->status_kirim) . "</span>";
            } else {
                $row[] = "<span class='badge bg-primary'>" . html_escape($h->status_kirim) . "</span>";
            }

            $row[] = html_escape($h->status_bayar) == "BELUM BAYAR"
                        ? "<span class=\"badge bg-danger\">" . html_escape($h->status_bayar) . "</span>"
                        : "<span class=\"badge bg-primary\">" . html_escape($h->status_bayar) . "</span>";

            $row[] = "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">
                            <button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"editdata('" . html_escape($h->id) . "')\" data-bs-toggle=\"modal\" data-bs-target=\"#modal_edit\"><i class=\"fa fa-pencil\"></i></button>
                            <button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"delete_data('" . html_escape($h->id) . "', '" . html_escape($h->no_kontrak) . "')\"><i class=\"fa fa-trash\"></i></button>
                        </div>";
            $data['data'][] = $row;
        }
        $data['total'] = number_format($total, 0, ',', '.');

        echo json_encode($data);
    }

    function ExportKontrakRinciUP3() {
        $tahun = $this->input->post('tahun', true);
        $data = $this->M_AllFunction->CustomQuery("SELECT * FROM vw_kontrak_up3 WHERE tahun_anggaran = '$tahun' ORDER BY awal_kontrak DESC");

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

        $sheet->getStyle('A1:S1')->applyFromArray($styleHeading);

        foreach(range('A', 'S') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'BASKET')
            ->setCellValue('C1', 'TAHUN')
            ->setCellValue('D1', 'JENIS ANGGARAN')
            ->setCellValue('E1', 'NO SKKI')
            ->setCellValue('F1', 'NO PRK')
            ->setCellValue('G1', 'NO KHS')
            ->setCellValue('H1', 'NO KONTRAK')
            ->setCellValue('I1', 'VENDOR')
            ->setCellValue('J1', 'KATEGORI MATERIAL')
            ->setCellValue('K1', 'MATERIAL')
            ->setCellValue('L1', 'NILAI KONTRAK')
            ->setCellValue('M1', 'AWAL KONTRAK')
            ->setCellValue('N1', 'AKHIR KONTRAK')
            ->setCellValue('O1', 'NOMOR BAE')
            ->setCellValue('P1', 'AWAL BAE')
            ->setCellValue('Q1', 'AKHIR BAE')
            ->setCellValue('R1', 'STATUS KIRIM')
            ->setCellValue('S1', 'STATUS BAYAR');

        $i = 1;
        $row = 2;
        foreach ($data as $d) {
            $sheet->setCellValue('A'.$row, $i++)
                ->setCellValue('B'.$row, html_escape($d->basket))
                ->setCellValue('C'.$row, html_escape($d->tahun_anggaran))
                ->setCellValue('D'.$row, html_escape($d->is_murni) == 1 ? "MURNI" : "LUNCURAN")
                ->setCellValue('E'.$row, html_escape($d->no_skki))
                ->setCellValue('F'.$row, html_escape($d->no_prk))
                ->setCellValue('G'.$row, html_escape($d->nomor_khs))
                ->setCellValue('H'.$row, html_escape($d->no_kontrak))
                ->setCellValue('I'.$row, html_escape($d->vendor))
                ->setCellValue('J'.$row, html_escape($d->kategori))
                ->setCellValue('K'.$row, html_escape($d->material))
                ->setCellValue('L'.$row, html_escape($d->nilai_kontrak))
                ->setCellValue('M'.$row, html_escape($d->awal_kontrak))
                ->setCellValue('N'.$row, html_escape($d->akhir_kontrak))
                ->setCellValue('O'.$row, html_escape($d->nomor_bae))
                ->setCellValue('P'.$row, html_escape($d->bae_awal))
                ->setCellValue('Q'.$row, html_escape($d->bae_akhir))
                ->setCellValue('R'.$row, html_escape($d->status_kirim) == "BELUM KIRIM" ? "BELUM KIRIM" : (html_escape($d->status_kirim) == "PROSES KIRIM" ? "PROSES KIRIM" : "SUDAH KIRIM"))
                ->setCellValue('S'.$row, html_escape($d->status_bayar) == "BELUM BAYAR" ? "BELUM BAYAR" : "SUDAH BAYAR");
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Kontrak_Rinci_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function delete_data_up3() {
        $id = $this->input->get("id");
        $this->M_AllFunction->Delete('trn_kontrak_up3_hdr', "id = '" . $id . "'");
        $this->M_AllFunction->Delete('trn_kontrak_up3_dtl', "id_hdr = '" . $id . "'");
        $this->M_AllFunction->Delete('trn_kontrak_up3_material', "id_hdr = '" . $id . "'");
        echo json_encode(array("status" => "success", "msg" => "Berhasil Hapus Data"));
    }

    function getMaterialKontrakUP3() {
        $id = $this->input->get('id');
        $data['data'] = $this->M_AllFunction->CustomQuery("SELECT * FROM vw_kontrak_up3_dtl WHERE no_kontrak = '$id'");
        $this->load->view('kontrak_up3/index_material', $data);
    }

    function getDetailNilaiUP3() {
        $id = $this->input->get('id');
        $query = "SELECT
                    trn_kontrak_up3_material.*,
                    vw_material.material,
                    vw_material.kategori,
                    vw_material.satuan
                FROM
                    trn_kontrak_up3_material
                LEFT JOIN vw_material
                ON trn_kontrak_up3_material.material_id = vw_material.id
                WHERE
                    trn_kontrak_up3_material.no_kontrak = '$id'";
        $data['data'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('kontrak/main_nilai', $data);
    }

    function SaveUP3() {
        $config['allowed_types'] = 'pdf';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;

        $filekontrakname = "";
        $file_kr_location = 'data_uploads/kontrak_up3/kr/';

        $config['upload_path'] = $file_kr_location;
        $config['file_name'] = "kr-" . bin2hex(random_bytes(24));
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('filekr')) {
            $data = array('upload_data' => $this->upload->data());
            $filekontrakname = $data['upload_data']['file_name'];
        }

        $data                  =  array(
            "id_basket"        => $this->input->post('id_basket', true),
            "tahun_anggaran"   => $this->input->post('tahun_anggaran', true),
            "is_murni"         => $this->input->post('is_murni', true),
            "id_skki"          => $this->input->post('id_skki', true),
            "no_prk"           => $this->input->post('no_prk', true),
            "no_khs"           => $this->input->post('no_khs', true),
            "no_kontrak"       => $this->input->post('no_kontrak', true),
            "no_po"            => $this->input->post('no_po', true),
            "id_vendor"        => $this->input->post('id_vendor', true),
            "awal_kontrak"     => $this->input->post('awal_kontrak', true),
            "akhir_kontrak"    => $this->input->post('akhir_kontrak', true),
            "nilai_kontrak"    => preg_replace('/[^0-9]/', '', $this->input->post('nilai_kontrak', true)),
            "is_using_jm"      => $this->input->post('is_using_jm', true),
            "file_kr"          => $filekontrakname,
            "file_kr_location" => $file_kr_location,
            "unit"             => $this->session->userdata('unit_id'),
            "createdby"        => $this->session->userdata('username'),
            "createddate"      => date('Y-m-d H: i: s')
        );

        $id = $this->M_AllFunction->InsertGetId('trn_kontrak_up3_hdr', $data);

        for ($i = 0; $i < count($this->input->post('unit_tujuan_id', true)); $i++) {
            $datadetail[$i] = array(
                "id_hdr"         => $id,
                "no_kontrak"     => $this->input->post('no_kontrak', true),
                "unit_tujuan_id" => $this->input->post('unit_tujuan_id', true)[$i],
                "material_id"    => $this->input->post('material', true)[$i],
                "volume"         => $this->input->post('volume', true)[$i],
                "lokasi"         => $this->input->post('lokasi', true)[$i],
                "latitude"       => $this->input->post('latitude', true)[$i],
                "longitude"      => $this->input->post('longitude', true)[$i]
            );
        }

        $this->M_AllFunction->InsertBatch('trn_kontrak_up3_dtl', $datadetail);

        $material = array_unique($this->input->post('material', true));
        $arr_no = 0;

        foreach ($material as $m) {
            $index  = array_search($m, $material);

            $total_vol = 0;
            foreach($datadetail as $dtl){
                if($m == $dtl['material_id']){
                    $total_vol += $dtl['volume'];
                }
            }

            $dataMaterial[$arr_no++] = array(
                "id_hdr"      => $id,
                "no_kontrak"  => $this->input->post('no_kontrak', true),
                "material_id" => $m,
                "volume"      => $total_vol,
                "harga"       => $this->input->post('harga', true)[$index],
                "ongkir"      => $this->input->post('ongkos', true)[$index],
                "total"       => $this->input->post('total', true)[$index],
                "lokasi"      => $this->input->post('lokasi', true)[$index],
                "latitude"    => $this->input->post('latitude', true)[$index],
                "longitude"   => $this->input->post('longitude', true)[$index]
            );
        }

        $this->M_AllFunction->InsertBatch('trn_kontrak_up3_material', $dataMaterial);
        redirect("C_Kontrak/KontrakRinciUP3");
    }

    function get_data_editUP3(){
        $id = $this->input->post('id', true);
        $data = $this->M_AllFunction->Where('vw_kontrak_up3', "id = '$id'");
        echo json_encode($data);
    }

    function EditUP3() {
        $config['allowed_types'] = 'pdf';
        $config['remove_spaces'] = TRUE;
        $config['max_size']      = 10000;

        $filekontrakname = "";
        $file_kr_location = 'data_uploads/kontrak_up3/kr/';

        $config['upload_path'] = $file_kr_location;
        $config['file_name'] = "kr-" . bin2hex(random_bytes(24));
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('filekr')) {
            $data = array('upload_data' => $this->upload->data());
            $filekontrakname = $data['upload_data']['file_name'];
        }

        $data = array(
            "id_basket"      => $this->input->post('id_basket_edit', true),
            "tahun_anggaran" => $this->input->post('tahun_anggaran_edit', true),
            "is_murni"       => $this->input->post('is_murni_edit', true),
            "id_skki"        => $this->input->post('id_skki_edit', true),
            "no_prk"         => $this->input->post('no_prk_edit', true),
            "no_po"          => $this->input->post('nomor_po_edit', true),
            "no_khs"         => $this->input->post('no_khs_edit', true),
            "is_using_jm"    => $this->input->post('is_using_jm_edit', true),
            "updatedby"      => $this->session->userdata('username'),
            "updateddate"    => date('Y-m-d H:i:s')
        );

        if($filekontrakname != ""){
            $data['file_kr'] = $filekontrakname;
            $data['file_kr_location'] = $file_kr_location;
        }

        $this->M_AllFunction->Update('trn_kontrak_up3_hdr', $data, "id = '" . $this->input->post('id_edit', true) . "'");
        $this->session->set_flashdata('flash_success', 'Berhasil Update Data.');
        redirect("C_Kontrak/KontrakRinciUP3");
    }

    // // ========================= REGION BAE ================================

    function BAE(){
        $data['data'] = $this->M_AllFunction->CustomQuery('SELECT * FROM vw_kontrak ORDER BY id DESC');
        $this->template->display('kontrak/bae', $data);
    }

    function GetDataBAE() {
        $id = $_GET['id'];
        $data = $this->M_AllFunction->Where('vw_kontrak', "id = '$id'");
        echo json_encode($data);
    }

    function SaveBAE() {
        $config['allowed_types'] = 'pdf';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;

        $filebaename = "";
        $file_bae_location = 'data_uploads/kontrak/bae/';

        $config['upload_path'] = $file_bae_location;
        $config['file_name'] = "bae-" . bin2hex(random_bytes(24));
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('filebae')) {
            $data = array('upload_data' => $this->upload->data());
            $filebaename = $data['upload_data']['file_name'];
        }

        $filejmname = "";
        $file_jm_location = 'data_uploads/kontrak/jm/';

        $config['upload_path'] = $file_jm_location;
        $config['file_name'] = "jm-" . bin2hex(random_bytes(24));
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('filejm')) {
            $data = array('upload_data' => $this->upload->data());
            $filejmname = $data['upload_data']['file_name'];
        }

        $data = array(
            "nomor_bae"    => $this->input->post('nomor_bae', true),
            "bae_awal"     => $this->input->post('bae_awal', true),
            "bae_akhir"    => $this->input->post('bae_akhir', true),
            "updatedby"    => $this->session->userdata('username'),
            "updateddate"  => date('Y-m-d H:i:s')
        );

        if ($filebaename != ""){
            $data['file_bae'] = $filebaename;
            $data['file_bae_location'] = $file_bae_location;
        }

        if ($filejmname != "") {
            $data['file_jm'] = $filejmname;
            $data['file_jm_location'] = $file_jm_location;
        }

        if ($this->M_AllFunction->Update('trn_kontrak_hdr', $data, "id = '" . $this->input->post('id', true) . "'")) {
            $this->session->set_flashdata('flash_success', 'Berhasil Update Data.');
        } else {
            $this->session->set_flashdata('flash_failed', 'Gagal Update Data.');
        }
        redirect("C_Kontrak/BAE");
    }

    function ExportBAE() {
        $data = $this->M_AllFunction->CustomQuery('SELECT * FROM vw_kontrak ORDER BY id DESC');

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

        $sheet->getStyle('A1:K1')->applyFromArray($styleHeading);
        $sheet->getStyle('H')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NO KONTRAK');
        $sheet->setCellValue('C1', 'VENDOR');
        $sheet->setCellValue('D1', 'KATEGORI MATERIAL');
        $sheet->setCellValue('E1', 'MATERIAL');
        $sheet->setCellValue('F1', 'AWAL KONTRAK');
        $sheet->setCellValue('G1', 'AKHIR KONTRAK');
        $sheet->setCellValue('H1', 'NILAI JAMINAN');
        $sheet->setCellValue('I1', 'NOMOR BAE');
        $sheet->setCellValue('J1', 'AWAL BAE');
        $sheet->setCellValue('K1', 'AKHIR BAE');

        $i = 2;
        foreach ($data as $d) {
            $sheet->setCellValue('A'.$i, $i -1);
            $sheet->setCellValue('B'.$i, html_escape($d->no_kontrak));
            $sheet->setCellValue('C'.$i, html_escape($d->vendor));
            $sheet->setCellValue('D'.$i, html_escape($d->kategori));
            $sheet->setCellValue('E'.$i, html_escape($d->material));
            $sheet->setCellValue('F'.$i, html_escape($d->awal_kontrak));
            $sheet->setCellValue('G'.$i, html_escape($d->akhir_kontrak));
            $sheet->setCellValue('H'.$i, number_format(html_escape($d->nilai_kontrak) * 0.05, 0, ".", "."));
            if(html_escape($d->is_using_jm) == "0"){
                $sheet->setCellValue('I' . $i, 'TANPA BANK GARANSI');
            } else{
                $sheet->setCellValue('I' . $i, html_escape($d->nomor_bae));
                if(empty(html_escape($d->nomor_bae))){
                    $sheet->getStyle('I'.$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFCCCC');
                }
            }
            $sheet->setCellValue('J' . $i, html_escape($d->bae_awal));
            if(empty(html_escape($d->bae_awal))){
                $sheet->getStyle('J'.$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFCCCC');
            }
            $sheet->setCellValue('K' . $i, html_escape($d->bae_akhir));
            if(empty(html_escape($d->bae_akhir))){
                $sheet->getStyle('K'.$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFCCCC');
            }
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="BAE_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    // ========================= REGION RENCANA KONTRAK ================================

    function RencanaKontrak(){
        $data['basket'] = $this->M_AllFunction->Get('mst_basket');
        $data['material'] = $this->M_AllFunction->CustomQuery("SELECT id, material, satuan FROM vw_material");
        $this->template->display("kontrak/rencana_kontrak", $data);
    }

    function AjaxRencanaKontrak(){
        $awalbulan = explode('-', $this->input->post('start_month', true))[1];
        $awaltahun = explode('-', $this->input->post('start_month', true))[0];
        $akhirbulan = explode('-', $this->input->post('end_month', true))[1];
        $akhirtahun = explode('-', $this->input->post('end_month', true))[0];
        $basket     = $this->input->post('basket_filter', true) != "*" ? " AND basket = '" . $this->input->post('basket_filter', true) . "'" : "";
        $query = "SELECT * FROM vw_rencana_kontrak
            WHERE bulan BETWEEN $awalbulan AND $akhirbulan
            AND tahun BETWEEN $awaltahun AND $akhirtahun
            $basket";
        $hasil = $this->M_AllFunction->CustomQuery($query);

        $data['data'] = array();
        $data['total'] = 0;

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->basket);
            $row[] = html_escape($h->tahun) . "-" . (html_escape($h->bulan) < 10 ? "0" . html_escape($h->bulan) : html_escape($h->bulan));
            $row[] = html_escape($h->minggu);
            $row[] = html_escape($h->material);
            $row[] = number_format(html_escape($h->volume), 0 , ",", ".");
            $row[] = number_format(html_escape($h->harga), 0 , ",", ".");
            $row[] = number_format(html_escape($h->total), 0 , ",", ".");
            if(html_escape($h->is_selesai) == 0){
                $row[] = "<button onclick=\"updateRencanaKontrak(" . html_escape($h->id) . ")\" class='btn btn-danger btn-sm'>UPDATE</button>";
            } else {
                $row[] = "<button class='btn btn-primary btn-sm'>SELESAI</button>";
            }

            $data['data'][] = $row;
            $data['total'] += $h->total;
        }

        echo json_encode($data);
    }

    function ExportRencanaKontrak(){
        $awalbulan = explode('-', $this->input->post('start_month', true))[1];
        $awaltahun = explode('-', $this->input->post('start_month', true))[0];
        $akhirbulan = explode('-', $this->input->post('end_month', true))[1];
        $akhirtahun = explode('-', $this->input->post('end_month', true))[0];
        $basket     = $this->input->post('basket_filter', true) != "*" ? " AND basket = '" . $this->input->post('basket_filter', true) . "'" : "";
        $query = "SELECT * FROM vw_rencana_kontrak
            WHERE bulan BETWEEN $awalbulan AND $akhirbulan
            AND tahun BETWEEN $awaltahun AND $akhirtahun
            $basket";
        $data = $this->M_AllFunction->CustomQuery($query);

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

        $sheet->getStyle('A1:I1')->applyFromArray($styleHeading);
        $sheet->getStyle('F:H')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'BASKET');
        $sheet->setCellValue('C1', 'BULAN / TAHUN');
        $sheet->setCellValue('D1', 'MINGGU');
        $sheet->setCellValue('E1', 'MATERIAL');
        $sheet->setCellValue('F1', 'VOLUME');
        $sheet->setCellValue('G1', 'HARGA');
        $sheet->setCellValue('H1', 'TOTAL');
        $sheet->setCellValue('I1', 'STATUS');

        $i = 2;
        foreach ($data as $d) {

            $sheet->setCellValue('A'.$i, $i-1);
            $sheet->setCellValue('B'.$i, html_escape($d->basket));
            $sheet->setCellValue('C'.$i, html_escape($d->tahun) . "-" . (html_escape($d->bulan) < 10 ? "0" . html_escape($d->bulan) : html_escape($d->bulan)));
            $sheet->setCellValue('D'.$i, html_escape($d->minggu));
            $sheet->setCellValue('E'.$i, html_escape($d->material));
            $sheet->setCellValue('F'.$i, html_escape($d->volume));
            $sheet->setCellValue('G'.$i, html_escape($d->harga));
            $sheet->setCellValue('H'.$i, html_escape($d->total));
            if(html_escape($d->is_selesai) == 1){
                $sheet->setCellValue('I'.$i, "SELESAI");
            }
            $i++;
        }


        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Rencana_Kontrak_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function RencanaKontrakSave(){
        $bulan = explode('-', $this->input->post('bulantahun', true))[1];
        $tahun = explode('-', $this->input->post('bulantahun', true))[0];
        $cek = $this->M_AllFunction->Where("trn_rencana_kontrak_hdr", "bulan = '$bulan' AND tahun = '$tahun'");
        if(empty($cek)){
            $data = array(
                "bulan"        => $bulan,
                "tahun"        => $tahun,
                "basket"        => $this->input->post('basket', true),
                "created_by"   => $this->session->userdata('username'),
                "created_date" => date('Y-m-d H:i:s')
            );
            $id = $this->M_AllFunction->InsertGetId('trn_rencana_kontrak_hdr', $data);
        } else {
            $id = $cek[0]->id;
        }

        for ($i = 0; $i < count($this->input->post('material', true)); $i++) {
            $total = $this->input->post('volume', true)[$i] * ($this->input->post('harga', true)[$i]);
            $datadetail = array(
                "id_hdr"       => $id,
                "material_id"  => $this->input->post('material', true)[$i],
                "volume"       => $this->input->post('volume', true)[$i],
                "harga"        => $this->input->post('harga', true)[$i],
                "ongkir"       => $this->input->post('ongkir', true)[$i],
                "total"        => $total + ($total * 0.11),
                "created_by"   => $this->session->userdata('username'),
                "created_date" => date('Y-m-d H:i:s')
            );
            $cek_dtl = $this->M_AllFunction->Where("trn_rencana_kontrak_dtl", "id = '$id' AND material_id = '" . $this->input->post('material', true)[$i] . "'");
            if(empty($cek_dtl)){
                $this->M_AllFunction->Insert('trn_rencana_kontrak_dtl', $datadetail);
            } else {
                $this->M_AllFunction->Update('trn_rencana_kontrak_dtl', $datadetail, "id = '" . $cek_dtl[0]->id . "'");
            }
        }
        redirect("C_Kontrak/RencanaKontrak");
    }

    function updateSelesai(){
        $id = $this->input->post('id', true);
        $this->M_AllFunction->Update("trn_rencana_kontrak_dtl", array("is_selesai" => 1), "id = $id");
        echo "success";
    }

    // ========================= BASTB ================================


    function BASTB(){
        $data['pejabat_pln'] = $this->M_AllFunction->Get('trn_pejabat_pln');
        $data['vendor'] = $this->M_AllFunction->Get('mst_vendor');
        $this->template->display('kontrak/bastb', $data);
    }


    function bastbData(){
        $where = "";
        if($this->input->post('no_kontrak', true) != ""){
            $where .= " AND vw_kontrak.no_kontrak = '" . $this->input->post('no_kontrak', true) . "'";
        }
        if($this->input->post('nomor_khs', true) != ""){
            $where .= " AND vw_kontrak.nomor_khs = '" . $this->input->post('nomor_khs', true) . "'";
        }
        if($this->input->post('id_vendor', true) != "*"){
            $where .= " AND vw_kontrak.id_vendor = '" . $this->input->post('id_vendor', true) . "'";
        }
        $query = "SELECT
                vw_kontrak.no_kontrak,
                vw_kontrak.nomor_khs,
                vw_kontrak.vendor,
                trn_kontrak_bastb.no_bastb,
                trn_kontrak_bastb.tanggal,
                trn_kontrak_bastb.nama_manager,
                trn_kontrak_bastb.vendor_direktur
            FROM vw_kontrak
            LEFT JOIN trn_kontrak_bastb
            ON vw_kontrak.no_kontrak = trn_kontrak_bastb.no_kontrak
            WHERE status_kirim = 'SELESAI KIRIM'
            $where";
        $hasil = $this->M_AllFunction->CustomQuery($query);

        $data['data'] = array();
        $data['total'] = 0;

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->no_kontrak);
            $row[] = html_escape($h->nomor_khs);
            $row[] = html_escape($h->vendor);
            $row[] = html_escape($h->no_bastb);
            $row[] = html_escape($h->tanggal);
            $row[] = html_escape($h->nama_manager);
            $row[] = html_escape($h->vendor_direktur);
            if(empty($h->no_bastb)){
                $row[] = "<button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"cekKontrak('" . html_escape($h->no_kontrak) . "')\" data-bs-toggle=\"modal\" data-bs-target=\"#modal_edit\"><i class=\"fa fa-pencil\"></i></button>";
            } else {
                $row[] = "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">
                    <button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"cekKontrak('" . html_escape($h->no_kontrak) . "')\" data-bs-toggle=\"modal\" data-bs-target=\"#modal_edit\"><i class=\"fa fa-pencil\"></i></button>
                    <button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"printKontrak('" . html_escape($h->no_kontrak) . "')\"><i class=\"fa fa-print\"></i></button>
                </div>";
            }

            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    function cekKontrakBASTB(){
        $data = $this->M_AllFunction->Where("trn_kontrak_bastb", "no_kontrak = '" . $this->input->post('no_kontrak', true) . "'");
        echo json_encode($data);
    }

    function UpdateBastb(){
        $this->M_AllFunction->Delete("trn_kontrak_bastb", "no_kontrak = '" . $this->input->post("no_kontrak_update", true) . "'");
        $manager = explode(' - ', $this->input->post("nama_manager", true));
        $data = array(
            "no_kontrak"      => $this->input->post("no_kontrak_update", true),
            "no_bastb"        => $this->input->post("no_bastb", true),
            "tanggal"         => $this->input->post("tanggal", true),
            "nama_manager"    => $manager[0],
            "jabatan_manager" => $manager[1],
            "vendor_direktur" => $this->input->post("vendor_direktur", true),
            "vendor_jabatan"  => $this->input->post("vendor_jabatan", true),
            "created_by"      => $this->session->userdata('username')
        );
        $this->M_AllFunction->Insert("trn_kontrak_bastb", $data);
        redirect("C_Kontrak/BASTB");
    }

    function printBastb(){
        $data['data'] = $this->M_AllFunction->Where("vw_kontrak_bastb", "no_kontrak = '" . $this->input->post('no_kontrak', true) . "'");
        $data['material'] = $this->M_AllFunction->CustomQuery(
            "SELECT
                material,
                SUM(volume) AS volume,
                satuan,
                merk AS keterangan
            FROM vw_kontrak_dtl
            WHERE no_kontrak = '" . $this->input->post('no_kontrak', true) . "'
            GROUP BY material_id");
        $data['material_dtl'] = $this->M_AllFunction->CustomQuery("SELECT
                mst_unit.name,
                vw_material.material,
                trn_pengiriman_material.volume,
                trn_kontrak_hdr.akhir_kontrak,
                trn_pengiriman_material.tanggal_penerimaan,
                trn_pengiriman_material.slip_penerimaan,
                trn_pengiriman_material.tanggal_persediaan,
                trn_pengiriman_material.no_persediaan
            FROM trn_pengiriman_material
            LEFT JOIN mst_unit
            ON trn_pengiriman_material.unit_id = mst_unit.id
            LEFT JOIN vw_material
            ON trn_pengiriman_material.material_id = vw_material.id
            LEFT JOIN trn_kontrak_hdr
            ON trn_pengiriman_material.no_kontrak = trn_kontrak_hdr.no_kontrak
            WHERE trn_pengiriman_material.no_kontrak = '" . $this->input->post('no_kontrak', true) . "'
            ORDER BY mst_unit.name");
        $data['denda'] = $this->M_AllFunction->Where("vw_denda_bastb", "no_kontrak = '" . $this->input->post('no_kontrak', true) . "'");

        $data['tanggal'] = $this->tanggal_ke_kata(strtotime($data['data'][0]->tanggal));

        $this->load->view('word/bastb', $data);
    }

    function tanggal_ke_kata($timestamp = null) {
        if ($timestamp === null) {
            $timestamp = time();
        }

        $hari = date('l', $timestamp);
        $tanggal = date('j', $timestamp);
        $bulan = date('F', $timestamp);
        $tahun = date('Y', $timestamp);

        $hari_indonesia = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];

        $tanggal_kata = $this->angka_ke_kata_full($tanggal);
        $tahun_kata = $this->angka_ke_kata_full($tahun);

        return $hari_indonesia[$hari] . ' tanggal ' . $tanggal_kata . ' bulan ' . $bulan . ' tahun ' . $tahun_kata;
    }

    // Fungsi untuk mengubah angka menjadi kata
    function angka_ke_kata_full($angka) {
        $angka_ke_kata = [
            1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat', 5 => 'Lima',
            6 => 'Enam', 7 => 'Tujuh', 8 => 'Delapan', 9 => 'Sembilan', 10 => 'Sepuluh',
            11 => 'Sebelas', 12 => 'Dua Belas', 13 => 'Tiga Belas', 14 => 'Empat Belas',
            15 => 'Lima Belas', 16 => 'Enam Belas', 17 => 'Tujuh Belas', 18 => 'Delapan Belas',
            19 => 'Sembilan Belas', 20 => 'Dua Puluh', 30 => 'Tiga Puluh',
            40 => 'Empat Puluh', 50 => 'Lima Puluh', 60 => 'Enam Puluh', 70 => 'Tujuh Puluh',
            80 => 'Delapan Puluh', 90 => 'Sembilan Puluh'
        ];

        if ($angka < 20) {
            return $angka_ke_kata[$angka];
        } else if ($angka < 100) {
            $puluhan = floor($angka / 10) * 10;
            $satuan = $angka % 10;
            if($satuan == 0) {
                return $angka_ke_kata[$puluhan];
            }
            return $angka_ke_kata[$puluhan] . ' ' . $angka_ke_kata[$satuan];
        } else if ($angka < 1000) {
            $ratusan = floor($angka / 100);
            $sisanya = $angka % 100;
            return $angka_ke_kata[$ratusan] . ' ratus ' . $this->angka_ke_kata_full($sisanya);
        } else if ($angka < 1000000) {
            $ribuan = floor($angka / 1000);
            $sisanya = $angka % 1000;
            return $this->angka_ke_kata_full($ribuan) . ' ribu ' . $this->angka_ke_kata_full($sisanya);
        } else {
            return 'Angka terlalu besar';
        }
    }
}
