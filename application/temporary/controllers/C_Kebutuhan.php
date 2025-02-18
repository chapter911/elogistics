<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 * @property Upload $upload
 * @property Uri $uri
 */

class C_Kebutuhan extends CI_Controller
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
        $data['kategori'] = $this->M_AllFunction->Get('mst_material_hdr');
        $this->template->display('kebutuhan/index', $data);
    }

    function queryDashboard(){
        $where = "";
        if($this->input->post('kategori', true) != "*"){
            $where .= "WHERE kategori = '" . $this->input->post('kategori', true) . "' ";
        }
        if($this->input->post('kebutuhan', true) != "*"){
            $where .= $where == "" ? " WHERE " : " AND ";
            $where .= " kebutuhan = '" . $this->input->post('kebutuhan', true) . "' ";
        }
        $tampil = "volume";
        if($this->input->post('filter', true) != "*"){
            $tampil = $this->input->post('filter', true);
        }

        $query = "WITH data AS (
                SELECT * FROM vw_kebutuhan $where
            )
            SELECT
                material_id,
                material,
                kategori,
                satuan,
                SUM(volume) AS total,
                SUM(approved_volume) AS approved_volume,
                SUM(sisa) AS sisa,
                SUM( CASE WHEN singkatan = 'BDG' THEN $tampil ELSE 0 END ) AS BDG,
                SUM( CASE WHEN singkatan = 'BLG' THEN $tampil ELSE 0 END ) AS BLG,
                SUM( CASE WHEN singkatan = 'BTR' THEN $tampil ELSE 0 END ) AS BTR,
                SUM( CASE WHEN singkatan = 'CKG' THEN $tampil ELSE 0 END ) AS CKG,
                SUM( CASE WHEN singkatan = 'CPP' THEN $tampil ELSE 0 END ) AS CPP,
                SUM( CASE WHEN singkatan = 'CPT' THEN $tampil ELSE 0 END ) AS CPT,
                SUM( CASE WHEN singkatan = 'CRC' THEN $tampil ELSE 0 END ) AS CRC,
                SUM( CASE WHEN singkatan = 'JTN' THEN $tampil ELSE 0 END ) AS JTN,
                SUM( CASE WHEN singkatan = 'KBJ' THEN $tampil ELSE 0 END ) AS KBJ,
                SUM( CASE WHEN singkatan = 'KJT' THEN $tampil ELSE 0 END ) AS KJT,
                SUM( CASE WHEN singkatan = 'LTA' THEN $tampil ELSE 0 END ) AS LTA,
                SUM( CASE WHEN singkatan = 'MRD' THEN $tampil ELSE 0 END ) AS MRD,
                SUM( CASE WHEN singkatan = 'MTG' THEN $tampil ELSE 0 END ) AS MTG,
                SUM( CASE WHEN singkatan = 'PDG' THEN $tampil ELSE 0 END ) AS PDG,
                SUM( CASE WHEN singkatan = 'PDK' THEN $tampil ELSE 0 END ) AS PDK,
                SUM( CASE WHEN singkatan = 'TJP' THEN $tampil ELSE 0 END ) AS TJP,
                SUM( CASE WHEN singkatan = 'UID' THEN $tampil ELSE 0 END ) AS UID,
                SUM( CASE WHEN singkatan = 'UP2D' THEN $tampil ELSE 0 END ) AS UP2D
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
        $hasil = $this->queryDashboard();

        $i = 1;

        $data = null;

        foreach ($hasil as $h) {
            $row = array();

			$row[] = $i++;
			$row[] = "<button onclick='getDetailKebutuhanMaterial(" . html_escape(html_escape($h->material_id)) . ")' class='btn btn-secondary btn-sm'>" . html_escape(html_escape($h->material_id)) . "</button>";
			$row[] = html_escape(html_escape($h->kategori));
			$row[] = html_escape(html_escape($h->material));
			$row[] = html_escape(html_escape($h->satuan));
            $row[] = number_format(html_escape(html_escape($h->total)), 0, ",", ".");
            $row[] = number_format(html_escape(html_escape($h->approved_volume)), 0, ",", ".");
            $row[] = number_format(html_escape(html_escape($h->sisa)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->UID)) == 0 ? "" : number_format(html_escape(html_escape($h->UID)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->BDG)) == 0 ? "" : number_format(html_escape(html_escape($h->BDG)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->BLG)) == 0 ? "" : number_format(html_escape(html_escape($h->BLG)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->BTR)) == 0 ? "" : number_format(html_escape(html_escape($h->BTR)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->CKG)) == 0 ? "" : number_format(html_escape(html_escape($h->CKG)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->CPP)) == 0 ? "" : number_format(html_escape(html_escape($h->CPP)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->CPT)) == 0 ? "" : number_format(html_escape(html_escape($h->CPT)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->CRC)) == 0 ? "" : number_format(html_escape(html_escape($h->CRC)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->JTN)) == 0 ? "" : number_format(html_escape(html_escape($h->JTN)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->KBJ)) == 0 ? "" : number_format(html_escape(html_escape($h->KBJ)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->KJT)) == 0 ? "" : number_format(html_escape(html_escape($h->KJT)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->LTA)) == 0 ? "" : number_format(html_escape(html_escape($h->LTA)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->MRD)) == 0 ? "" : number_format(html_escape(html_escape($h->MRD)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->MTG)) == 0 ? "" : number_format(html_escape(html_escape($h->MTG)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->PDG)) == 0 ? "" : number_format(html_escape(html_escape($h->PDG)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->PDK)) == 0 ? "" : number_format(html_escape(html_escape($h->PDK)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->TJP)) == 0 ? "" : number_format(html_escape(html_escape($h->TJP)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->UP2D)) == 0 ? "" : number_format(html_escape(html_escape($h->UP2D)), 0, ",", ".");
            $data['data'][] = $row;
        }

		echo json_encode($data);
    }

    function ajaxDashboardRencana(){
        // $where = "(YEAR(created_date) = " . $this->input->post("tahun", true) . " OR YEAR(rencana_nyala) = " . $this->input->post("tahun", true) . ")";
        // $where = "YEAR(rencana_nyala) = " . $this->input->post("tahun", true);
        $where = "";
        $tahun = $this->input->post("tahun", true);
        $kebutuhan = $this->input->post("kebutuhan", true);
        if($kebutuhan != "*"){
            $where .= "WHERE kebutuhan = '" . $this->input->post("kebutuhan", true) . "'";
        }
        if($this->input->post("is_bayar", true) != "*"){
            $where .= $where == "" ? " WHERE " : " AND ";
            $where .= " (is_bayar = " . $this->input->post("is_bayar", true) . " OR is_bayar IS NULL)";
        }
        $tampil = $this->input->post("filter_rencana", true);
        $query = "SELECT
                id,
                material,
                kategori,
                satuan,
                SUM(CASE WHEN YEAR(rencana_nyala) = $tahun THEN volume ELSE 0 END) AS total,
                SUM(CASE WHEN YEAR(rencana_nyala) = $tahun THEN approved_volume ELSE 0 END) AS approved_volume,
                SUM(CASE WHEN YEAR(rencana_nyala) = $tahun THEN sisa ELSE 0 END) AS sisa,
                SUM(CASE WHEN rencana_nyala = '0000-00-00' THEN $tampil ELSE 0 END) AS non,
                SUM(CASE WHEN MONTH(rencana_nyala) = 1 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS jan,
                SUM(CASE WHEN MONTH(rencana_nyala) = 2 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS feb,
                SUM(CASE WHEN MONTH(rencana_nyala) = 3 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS mar,
                SUM(CASE WHEN MONTH(rencana_nyala) = 4 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS apr,
                SUM(CASE WHEN MONTH(rencana_nyala) = 5 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS mei,
                SUM(CASE WHEN MONTH(rencana_nyala) = 6 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS jun,
                SUM(CASE WHEN MONTH(rencana_nyala) = 7 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS jul,
                SUM(CASE WHEN MONTH(rencana_nyala) = 8 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS agts,
                SUM(CASE WHEN MONTH(rencana_nyala) = 9 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS sep,
                SUM(CASE WHEN MONTH(rencana_nyala) = 10 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS okt,
                SUM(CASE WHEN MONTH(rencana_nyala) = 11 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS nov,
                SUM(CASE WHEN MONTH(rencana_nyala) = 12 AND YEAR(rencana_nyala) = $tahun THEN $tampil ELSE 0 END) AS des
            FROM vw_kebutuhan_breakdown
            $where
            GROUP BY id";
        $hasil = $this->M_AllFunction->CustomQuery($query);

        $i = 1;
        $data['data'] = array();

        foreach ($hasil as $h) {
            $row = array();

			$row[] = $i++;
			$row[] = html_escape($h->id);
			$row[] = html_escape($h->kategori);
			$row[] = html_escape($h->material);
			$row[] = html_escape($h->satuan);
			$row[] = html_escape(number_format($h->total, 0, ",", "."));
            $row[] = html_escape(number_format($h->approved_volume, 0, ",", "."));
            $row[] = html_escape(number_format($h->sisa, 0, ",", "."));
            $row[] = html_escape(number_format($h->non, 0, ",", "."));
            $row[] = html_escape(number_format($h->jan, 0, ",", "."));
            $row[] = html_escape(number_format($h->feb, 0, ",", "."));
            $row[] = html_escape(number_format($h->mar, 0, ",", "."));
            $row[] = html_escape(number_format($h->apr, 0, ",", "."));
            $row[] = html_escape(number_format($h->mei, 0, ",", "."));
            $row[] = html_escape(number_format($h->jun, 0, ",", "."));
            $row[] = html_escape(number_format($h->jul, 0, ",", "."));
            $row[] = html_escape(number_format($h->agts, 0, ",", "."));
            $row[] = html_escape(number_format($h->sep, 0, ",", "."));
            $row[] = html_escape(number_format($h->okt, 0, ",", "."));
            $row[] = html_escape(number_format($h->nov, 0, ",", "."));
            $row[] = html_escape(number_format($h->des, 0, ",", "."));
            $data['data'][] = $row;
        }

		echo json_encode($data);
    }

    function exportDashboard(){
        $hasil = $this->queryDashboard();

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

        $sheet->getStyle('A1:Z1')->applyFromArray($styleHeading);
        $sheet->getStyle('F:Z')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NORMALISASI');
        $sheet->setCellValue('C1', 'KATEGORI');
        $sheet->setCellValue('D1', 'MATERIAL');
        $sheet->setCellValue('E1', 'SATUAN');
        $sheet->setCellValue('F1', 'TOTAL');
        $sheet->setCellValue('G1', 'SUDAH DIPENUHI');
        $sheet->setCellValue('H1', 'BELUM DIPENUHI');
        $sheet->setCellValue('I1', 'UID');
        $sheet->setCellValue('J1', 'BDG');
        $sheet->setCellValue('K1', 'BLG');
        $sheet->setCellValue('L1', 'BTR');
        $sheet->setCellValue('M1', 'CKG');
        $sheet->setCellValue('N1', 'CPP');
        $sheet->setCellValue('O1', 'CPT');
        $sheet->setCellValue('P1', 'CRC');
        $sheet->setCellValue('Q1', 'JTN');
        $sheet->setCellValue('R1', 'KBJ');
        $sheet->setCellValue('S1', 'KJT');
        $sheet->setCellValue('T1', 'LTA');
        $sheet->setCellValue('U1', 'MRD');
        $sheet->setCellValue('V1', 'MTG');
        $sheet->setCellValue('W1', 'PDG');
        $sheet->setCellValue('X1', 'PDK');
        $sheet->setCellValue('Y1', 'TJP');
        $sheet->setCellValue('Z1', 'UP2D');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->material_id));
            $sheet->setCellValue('C' . $i, html_escape($h->kategori));
            $sheet->setCellValue('D' . $i, html_escape($h->material));
            $sheet->setCellValue('E' . $i, html_escape($h->satuan));
            $sheet->setCellValue('F' . $i, html_escape($h->total));
            $sheet->setCellValue('G' . $i, html_escape($h->approved_volume));
            $sheet->setCellValue('H' . $i, html_escape($h->sisa));
            $sheet->setCellValue('I' . $i, html_escape($h->UID) == 0 ? "" : html_escape($h->UID));
            $sheet->setCellValue('J' . $i, html_escape($h->BDG) == 0 ? "" : html_escape($h->BDG));
            $sheet->setCellValue('K' . $i, html_escape($h->BLG) == 0 ? "" : html_escape($h->BLG));
            $sheet->setCellValue('L' . $i, html_escape($h->BTR) == 0 ? "" : html_escape($h->BTR));
            $sheet->setCellValue('M' . $i, html_escape($h->CKG) == 0 ? "" : html_escape($h->CKG));
            $sheet->setCellValue('N' . $i, html_escape($h->CPP) == 0 ? "" : html_escape($h->CPP));
            $sheet->setCellValue('O' . $i, html_escape($h->CPT) == 0 ? "" : html_escape($h->CPT));
            $sheet->setCellValue('P' . $i, html_escape($h->CRC) == 0 ? "" : html_escape($h->CRC));
            $sheet->setCellValue('Q' . $i, html_escape($h->JTN) == 0 ? "" : html_escape($h->JTN));
            $sheet->setCellValue('R' . $i, html_escape($h->KBJ) == 0 ? "" : html_escape($h->KBJ));
            $sheet->setCellValue('S' . $i, html_escape($h->KJT) == 0 ? "" : html_escape($h->KJT));
            $sheet->setCellValue('T' . $i, html_escape($h->LTA) == 0 ? "" : html_escape($h->LTA));
            $sheet->setCellValue('U' . $i, html_escape($h->MRD) == 0 ? "" : html_escape($h->MRD));
            $sheet->setCellValue('V' . $i, html_escape($h->MTG) == 0 ? "" : html_escape($h->MTG));
            $sheet->setCellValue('W' . $i, html_escape($h->PDG) == 0 ? "" : html_escape($h->PDG));
            $sheet->setCellValue('X' . $i, html_escape($h->PDK) == 0 ? "" : html_escape($h->PDK));
            $sheet->setCellValue('Y' . $i, html_escape($h->TJP) == 0 ? "" : html_escape($h->TJP));
            $sheet->setCellValue('Z' . $i, html_escape($h->UP2D) == 0 ? "" : html_escape($h->UP2D));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Dashboard_Kebutuhan_Material_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function detailKebutuhanMaterial(){
        $material_id = $this->input->post('material_id', true);
        if($material_id == ''){
            return null;
        }
        $where = "WHERE material_id = '$material_id' ";
        if($this->input->post('kebutuhan', true) != "*"){
            $where .= " AND kebutuhan = '" . $this->input->post('kebutuhan', true) . "' ";
        }
        $tampil = "volume";
        if($this->input->post('filter', true) != "*"){
            $tampil = $this->input->post('filter', true);
        }

        $query = "WITH data AS (
                SELECT * FROM vw_kebutuhan_material $where
            )
            SELECT
                material_id,
                material,
                kategori,
                satuan,
                rasio,
                SUM(volume) AS total,
                SUM(approved_volume) AS approved_volume,
                SUM(sisa) AS sisa,
                SUM( CASE WHEN singkatan = 'BDG' THEN $tampil ELSE 0 END ) AS BDG,
                SUM( CASE WHEN singkatan = 'BLG' THEN $tampil ELSE 0 END ) AS BLG,
                SUM( CASE WHEN singkatan = 'BTR' THEN $tampil ELSE 0 END ) AS BTR,
                SUM( CASE WHEN singkatan = 'CKG' THEN $tampil ELSE 0 END ) AS CKG,
                SUM( CASE WHEN singkatan = 'CPP' THEN $tampil ELSE 0 END ) AS CPP,
                SUM( CASE WHEN singkatan = 'CPT' THEN $tampil ELSE 0 END ) AS CPT,
                SUM( CASE WHEN singkatan = 'CRC' THEN $tampil ELSE 0 END ) AS CRC,
                SUM( CASE WHEN singkatan = 'JTN' THEN $tampil ELSE 0 END ) AS JTN,
                SUM( CASE WHEN singkatan = 'KBJ' THEN $tampil ELSE 0 END ) AS KBJ,
                SUM( CASE WHEN singkatan = 'KJT' THEN $tampil ELSE 0 END ) AS KJT,
                SUM( CASE WHEN singkatan = 'LTA' THEN $tampil ELSE 0 END ) AS LTA,
                SUM( CASE WHEN singkatan = 'MRD' THEN $tampil ELSE 0 END ) AS MRD,
                SUM( CASE WHEN singkatan = 'MTG' THEN $tampil ELSE 0 END ) AS MTG,
                SUM( CASE WHEN singkatan = 'PDG' THEN $tampil ELSE 0 END ) AS PDG,
                SUM( CASE WHEN singkatan = 'PDK' THEN $tampil ELSE 0 END ) AS PDK,
                SUM( CASE WHEN singkatan = 'TJP' THEN $tampil ELSE 0 END ) AS TJP,
                SUM( CASE WHEN singkatan = 'UID' THEN $tampil ELSE 0 END ) AS UID,
                SUM( CASE WHEN singkatan = 'UP2D' THEN $tampil ELSE 0 END ) AS UP2D
            FROM
                data
            GROUP BY
                material_id,
                material,
                kategori,
                satuan,
                rasio
            ORDER BY LENGTH(rasio), rasio, material_id";

        $hasil = $this->M_AllFunction->CustomQuery($query);

        $i = 1;

        $data = null;

        foreach ($hasil as $h) {
            $row = array();

			$row[] = $i++;
			$row[] = html_escape(html_escape($h->material_id));
			$row[] = html_escape(html_escape($h->material));
			$row[] = html_escape(html_escape($h->kategori));
			$row[] = html_escape(html_escape($h->satuan));
			$row[] = html_escape(html_escape($h->rasio));
            $row[] = number_format(html_escape(html_escape($h->total)), 0, ",", ".");
            $row[] = number_format(html_escape(html_escape($h->approved_volume)), 0, ",", ".");
            $row[] = number_format(html_escape(html_escape($h->sisa)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->UID)) == 0 ? "" : number_format(html_escape(html_escape($h->UID)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->BDG)) == 0 ? "" : number_format(html_escape(html_escape($h->BDG)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->BLG)) == 0 ? "" : number_format(html_escape(html_escape($h->BLG)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->BTR)) == 0 ? "" : number_format(html_escape(html_escape($h->BTR)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->CKG)) == 0 ? "" : number_format(html_escape(html_escape($h->CKG)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->CPP)) == 0 ? "" : number_format(html_escape(html_escape($h->CPP)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->CPT)) == 0 ? "" : number_format(html_escape(html_escape($h->CPT)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->CRC)) == 0 ? "" : number_format(html_escape(html_escape($h->CRC)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->JTN)) == 0 ? "" : number_format(html_escape(html_escape($h->JTN)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->KBJ)) == 0 ? "" : number_format(html_escape(html_escape($h->KBJ)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->KJT)) == 0 ? "" : number_format(html_escape(html_escape($h->KJT)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->LTA)) == 0 ? "" : number_format(html_escape(html_escape($h->LTA)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->MRD)) == 0 ? "" : number_format(html_escape(html_escape($h->MRD)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->MTG)) == 0 ? "" : number_format(html_escape(html_escape($h->MTG)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->PDG)) == 0 ? "" : number_format(html_escape(html_escape($h->PDG)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->PDK)) == 0 ? "" : number_format(html_escape(html_escape($h->PDK)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->TJP)) == 0 ? "" : number_format(html_escape(html_escape($h->TJP)), 0, ",", ".");
            $row[] = html_escape(html_escape($h->UP2D)) == 0 ? "" : number_format(html_escape(html_escape($h->UP2D)), 0, ",", ".");
            $data['data'][] = $row;
        }

		echo json_encode($data);
    }

    function detailKebutuhanMaterial2(){
        $kebutuhan = $this->input->post('kebutuhan', true);
        $tahun = $this->input->post('tahun', true);
        $material = $this->input->post('material', true);
        $query = "WITH efisiensi AS (
                    SELECT
                        trn_efesiensi_hdr.unit AS unit,
                        trn_efesiensi_dtl.material_id AS material_id,
                        sum( trn_efesiensi_dtl.volume ) AS volume,
                        trn_efesiensi_dtl.rasio,
                        YEAR ( trn_efesiensi_hdr.created_date ) AS tahun,
                        'efisiensi' AS kebutuhan
                    FROM
                        trn_efesiensi_hdr
                        JOIN trn_efesiensi_dtl ON trn_efesiensi_hdr.id = trn_efesiensi_dtl.efesiensi_id
                    WHERE
                        YEAR ( trn_efesiensi_hdr.created_date ) = $tahun
                        AND trn_efesiensi_dtl.material_id = $material
                    GROUP BY
                        trn_efesiensi_hdr.unit,
                        trn_efesiensi_dtl.material_id,
                        trn_efesiensi_dtl.rasio,
                        YEAR (trn_efesiensi_hdr.created_date)
                ), keandalan AS (
                    SELECT
                        trn_keandalan_hdr.unit AS unit,
                        trn_keandalan_dtl.material_id AS material_id,
                        sum( trn_keandalan_dtl.volume ) AS volume,
                        trn_keandalan_dtl.rasio,
                        YEAR ( trn_keandalan_hdr.created_date ) AS tahun,
                        'keandalan' AS kebutuhan
                    FROM
                        trn_keandalan_hdr
                        JOIN trn_keandalan_dtl ON trn_keandalan_hdr.id = trn_keandalan_dtl.keandalan_id
                    WHERE
                        YEAR ( trn_keandalan_hdr.created_date ) = $tahun
                        AND trn_keandalan_dtl.material_id = $material
                    GROUP BY
                        trn_keandalan_hdr.unit,
                        trn_keandalan_dtl.material_id,
                        trn_keandalan_dtl.rasio,
                        YEAR (trn_keandalan_hdr.created_date)
                ), pemasaran AS (
                    SELECT
                        trn_pemasaran_hdr.unit AS unit,
                        trn_pemasaran_dtl.material_id AS material_id,
                        sum( trn_pemasaran_dtl.volume ) AS volume,
                        trn_pemasaran_dtl.rasio,
                        YEAR ( trn_pemasaran_hdr.createddate ) AS tahun,
                        'pemasaran' AS kebutuhan
                    FROM
                        trn_pemasaran_hdr
                        JOIN trn_pemasaran_dtl ON trn_pemasaran_hdr.id = trn_pemasaran_dtl.pemasaran_id
                    WHERE
                        YEAR ( trn_pemasaran_hdr.createddate ) = $tahun
                        AND trn_pemasaran_dtl.material_id = $material
                    GROUP BY
                        trn_pemasaran_hdr.unit,
                        trn_pemasaran_dtl.material_id,
                        trn_pemasaran_dtl.rasio,
                        YEAR (trn_pemasaran_hdr.createddate)
                ), combine AS (
                    SELECT * FROM efisiensi
                    UNION
                    SELECT * FROM keandalan
                    UNION
                    SELECT * FROM pemasaran
                ), raw AS (
                    SELECT
                        mst_unit.singkatan,
                        combine.material_id,
                        vw_material.material,
                        vw_material.kategori,
                        vw_material.satuan,
                        SUM(combine.volume) AS volume,
                        combine.rasio,
                        combine.tahun
                    FROM combine
                    LEFT JOIN mst_unit
                    ON combine.unit = mst_unit.id
                    LEFT JOIN vw_material
                    ON vw_material.id = combine.material_id
                    GROUP BY
                        mst_unit.singkatan,
                        combine.material_id,
                        vw_material.material,
                        vw_material.kategori,
                        vw_material.satuan,
                        combine.rasio,
                        combine.tahun
                ), material AS (
                    SELECT material_id, material, kategori, satuan, rasio FROM raw GROUP BY rasio
                )
                SELECT
                    material.material_id,
                    material.material,
                    material.kategori,
                    material.satuan,
                    material.rasio,
                    SUM(raw.volume) AS total,
                    SUM( CASE WHEN raw.singkatan = 'BDG' THEN volume ELSE 0 END ) AS BDG,
                    SUM( CASE WHEN raw.singkatan = 'BLG' THEN volume ELSE 0 END ) AS BLG,
                    SUM( CASE WHEN raw.singkatan = 'BTR' THEN volume ELSE 0 END ) AS BTR,
                    SUM( CASE WHEN raw.singkatan = 'CKG' THEN volume ELSE 0 END ) AS CKG,
                    SUM( CASE WHEN raw.singkatan = 'CPP' THEN volume ELSE 0 END ) AS CPP,
                    SUM( CASE WHEN raw.singkatan = 'CPT' THEN volume ELSE 0 END ) AS CPT,
                    SUM( CASE WHEN raw.singkatan = 'CRC' THEN volume ELSE 0 END ) AS CRC,
                    SUM( CASE WHEN raw.singkatan = 'JTN' THEN volume ELSE 0 END ) AS JTN,
                    SUM( CASE WHEN raw.singkatan = 'KBJ' THEN volume ELSE 0 END ) AS KBJ,
                    SUM( CASE WHEN raw.singkatan = 'KJT' THEN volume ELSE 0 END ) AS KJT,
                    SUM( CASE WHEN raw.singkatan = 'LTA' THEN volume ELSE 0 END ) AS LTA,
                    SUM( CASE WHEN raw.singkatan = 'MRD' THEN volume ELSE 0 END ) AS MRD,
                    SUM( CASE WHEN raw.singkatan = 'MTG' THEN volume ELSE 0 END ) AS MTG,
                    SUM( CASE WHEN raw.singkatan = 'PDG' THEN volume ELSE 0 END ) AS PDG,
                    SUM( CASE WHEN raw.singkatan = 'PDK' THEN volume ELSE 0 END ) AS PDK,
                    SUM( CASE WHEN raw.singkatan = 'TJP' THEN volume ELSE 0 END ) AS TJP,
                    SUM( CASE WHEN raw.singkatan = 'UID' THEN volume ELSE 0 END ) AS UID,
                    SUM( CASE WHEN raw.singkatan = 'UP2D' THEN volume ELSE 0 END ) AS UP2D
                FROM material
                LEFT JOIN raw
                ON material.rasio = raw.rasio
                GROUP BY material.rasio
                ORDER BY LENGTH(material.rasio), material.rasio ASC, material.material_id";

        $data['data'] = $this->M_AllFunction->CustomQuery($query);

        $this->load->view('kebutuhan/detail_kebutuhan_material', $data);
    }
}
?>