<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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

class C_Pengiriman extends CI_Controller {

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
        $this->user_unit_id = $this->session->userdata('unit_id');
	}

    function Dashboard(){
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $data['kategori'] = $this->M_AllFunction->Get('mst_material_hdr');
        $this->template->display('pengiriman/dashboard', $data);
    }

    function queryDashboard(){
        $where = "";
        if($this->input->post('status_kirim', true) != "*"){
            $where .= "WHERE status_kirim = '" . $this->input->post('status_kirim', true) . "'";
        }
        if($this->input->post('kategori', true) != "*"){
            $where .= $where == "" ? "WHERE " : "AND ";
            $where .= "kategori = '" . $this->input->post('kategori', true) . "'";
        }

        $query = "SELECT * FROM vw_pengiriman $where ORDER BY awal_kontrak DESC";
        return $this->M_AllFunction->CustomQuery($query);
    }

    function ajaxDashboard(){
        $hasil = $this->queryDashboard();

        $data['data'] = array();

        $i = 1;
        $data['total'] = 0;
        $avg_durasi = 0;
        $count_durasi = 0;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->no_kontrak);
            $row[] = html_escape($h->basket);
            $row[] = html_escape($h->vendor);
            $row[] = html_escape($h->kategori);
            $row[] = "<button onclick=\"getMaterialKontrak('" . html_escape($h->no_kontrak) . "')\" class=\"btn btn-secondary btn-sm w-100\">" . html_escape($h->material) . "</button>";
            $row[] = html_escape($h->satuan);
            $row[] = number_format(html_escape($h->volume_kontrak), 0, ",", ".");
            $row[] = number_format(html_escape($h->volume_kirim), 0, ",", ".");
            $row[] = html_escape($h->awal_kontrak);
            $row[] = html_escape($h->tanggal_penerimaan);
            $avg_durasi += html_escape($h->durasi);
            $count_durasi += 1;
            $row[] = html_escape($h->durasi);
            if(html_escape($h->status_kirim) == "BELUM KIRIM"){
                $row[] = "<button class=\"btn btn-danger btn-sm\">BELUM KIRIM</button>";
            } else if(html_escape($h->status_kirim) == "PROSES KIRIM"){
                $row[] = "<button class=\"btn btn-warning btn-sm\">PROSES KIRIM</button>";
            } else {
                $row[] = "<button class=\"btn btn-primary btn-sm\">SELESAI KIRIM</button>";
            }
            $row[] = "<button type='button' class='btn btn-outline-secondary btn-sm waves-effect waves-light' onclick=\"detailPengirimanDashboard('" . html_escape($h->no_kontrak) . "')\"><i class='fa-regular fa-eye'></i></button>";
            $data['data'][] = $row;
        }
        $data['avg_durasi'] = number_format($avg_durasi / $count_durasi, 2, ",", ".");

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

        $sheet->getStyle('A1:M1')->applyFromArray($styleHeading);
        $sheet->getStyle('H:I')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NO KONTRAK');
        $sheet->setCellValue('C1', 'ANGGARAN');
        $sheet->setCellValue('D1', 'VENDOR');
        $sheet->setCellValue('E1', 'KATEGORI MATERIAL');
        $sheet->setCellValue('F1', 'MATERIAL');
        $sheet->setCellValue('G1', 'SATUAN');
        $sheet->setCellValue('H1', 'VOLUME KONTRAK');
        $sheet->setCellValue('I1', 'VOLUME KIRIM');
        $sheet->setCellValue('J1', 'AWAL KONTRAK');
        $sheet->setCellValue('K1', 'TANGGAL TERIMA');
        $sheet->setCellValue('L1', 'DURASI');
        $sheet->setCellValue('M1', 'PENGIRIMAN');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->no_kontrak));
            $sheet->setCellValue('C' . $i, html_escape($h->basket));
            $sheet->setCellValue('D' . $i, html_escape($h->vendor));
            $sheet->setCellValue('E' . $i, html_escape($h->kategori));
            $sheet->setCellValue('F' . $i, html_escape($h->material));
            $sheet->setCellValue('G' . $i, html_escape($h->satuan));
            $sheet->setCellValue('H' . $i, html_escape($h->volume_kontrak));
            $sheet->setCellValue('I' . $i, html_escape($h->volume_kirim));
            $sheet->setCellValue('J' . $i, html_escape($h->awal_kontrak));
            $sheet->setCellValue('K' . $i, html_escape($h->tanggal_penerimaan));
            $sheet->setCellValue('L' . $i, html_escape($h->durasi));
            $sheet->setCellValue('M' . $i, html_escape($h->status_kirim));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Dashboard_Pengiriman_Material_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function detailPengirimanDashboard(){
        $no_kontrak = $this->input->post('no_kontrak', true);
        $query = "SELECT
                    A.no_kontrak,
                    B.id,
                    B.name,
                    SUM(A.volume) AS volume_kontrak,
                    IFNULL((SELECT SUM(volume) FROM trn_pengiriman_material WHERE unit_id = A.unit_tujuan_id AND no_kontrak = A.no_kontrak), 0) AS volume_kirim
                FROM
                    trn_kontrak_dtl AS A
                    JOIN mst_unit AS B
                    ON A.unit_tujuan_id = B.id
                WHERE
                    A.no_kontrak = '$no_kontrak'
                    GROUP BY A.no_kontrak, A.unit_tujuan_id";
        $data['data'] = $this->M_AllFunction->CustomQuery($query);
        $data['vendor'] = $this->M_AllFunction->Where("vw_pengiriman", "no_kontrak = '$no_kontrak'");
        $this->load->view('pengiriman/detail_dashboard', $data);
    }

    function queryKarantina(){
        $where = "";
        if($this->input->post('unit_karantina', true) != "*"){
            $where .= "AND C.unit_id = '" . $this->input->post('unit_karantina', true) . "'";
        }
        $query = "SELECT
                A.no_kontrak,
                F.vendor,
                D.material,
                D.satuan,
                C.volume,
                E.name,
                C.tanggal_penerimaan,
                C.status
            FROM
                trn_pengiriman_hdr AS A
                JOIN trn_pengiriman_dtl AS B
                ON A.no_kontrak = B.no_kontrak
                JOIN trn_pengiriman_material AS C
                ON A.no_kontrak = C.no_kontrak AND B.id = C.pengiriman_dtl_id
                JOIN vw_material AS D
                ON C.material_id = D.id
                JOIN mst_unit AS E
                ON E.id = C.unit_id
                JOIN vw_kontrak AS F
                ON A.no_kontrak = F.no_kontrak
            WHERE C.volume <> 0 AND C.status = 'KARANTINA' $where
            ORDER BY B.tanggal_penerimaan";
        return $this->M_AllFunction->CustomQuery($query);
    }

    function ajaxKarantina(){
        $hasil = $this->queryKarantina();

        $data['data'] = array();

        $i = 1;

        $data['total'] = 0;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->no_kontrak);
            $row[] = html_escape($h->vendor);
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->satuan);
            $row[] = html_escape($h->volume);
            $row[] = html_escape($h->name);
            $row[] = html_escape($h->tanggal_penerimaan);
            $row[] = html_escape($h->status);
            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    function exportKarantina(){
        $hasil = $this->queryKarantina();

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

        foreach(range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NO KONTRAK');
        $sheet->setCellValue('C1', 'VENDOR');
        $sheet->setCellValue('D1', 'MATERIAL');
        $sheet->setCellValue('E1', 'SATUAN');
        $sheet->setCellValue('F1', 'VOLUME');
        $sheet->setCellValue('G1', 'UNIT');
        $sheet->setCellValue('H1', 'TANGGAL TERIMA');
        $sheet->setCellValue('I1', 'STATUS');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->no_kontrak));
            $sheet->setCellValue('C' . $i, html_escape($h->vendor));
            $sheet->setCellValue('D' . $i, html_escape($h->material));
            $sheet->setCellValue('E' . $i, html_escape($h->satuan));
            $sheet->setCellValue('F' . $i, html_escape($h->volume));
            $sheet->setCellValue('G' . $i, html_escape($h->name));
            $sheet->setCellValue('H' . $i, html_escape($h->tanggal_penerimaan));
            $sheet->setCellValue('I' . $i, html_escape($h->status));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Daftar_Material_Belum_Masuk_Persediaan_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function index(){
        $this->template->display('pengiriman/index');
    }

    function queryIndex(){
        $where = "";
        if($this->input->post('status_kirim', true) != "*"){
            $where .= "WHERE status_kirim = '" . $this->input->post('status_kirim', true) . "'";
        }

        $query = "WITH vol_kontrak AS (
                SELECT no_kontrak, IFNULL(SUM(volume), 0) AS volume FROM trn_kontrak_dtl WHERE unit_tujuan_id = '$this->user_unit_id' GROUP BY no_kontrak
            ), vol_kirim AS (
                SELECT no_kontrak, IFNULL(SUM(volume), 0) AS volume FROM trn_pengiriman_material WHERE unit_id = '$this->user_unit_id' GROUP BY no_kontrak
            ), tpengiriman AS (
                -- SELECT no_kontrak, MAX(tanggal_penerimaan) AS tanggal_penerimaan FROM trn_pengiriman_dtl GROUP BY no_kontrak
                SELECT no_kontrak, MAX(tanggal_penerimaan) AS tanggal_penerimaan FROM trn_pengiriman_material WHERE unit_id = '$this->user_unit_id' GROUP BY no_kontrak
            ), rpengiriman AS (
                SELECT no_kontrak, rencana_kirim FROM trn_pengiriman_hdr WHERE unit_id = '$this->user_unit_id' GROUP BY no_kontrak
            ), result AS (
                SELECT
                    A.id,
                    A.no_kontrak,
                    A.no_po,
                    B.basket,
                    C.vendor,
                    A.kategori,
                    A.material,
                    IFNULL(D.volume, 0) AS volume_kontrak,
                    IFNULL(E.volume, 0) AS volume_kirim,
                    CASE
                        WHEN IFNULL(E.volume, 0) = 0 THEN 'BELUM KIRIM'
                        WHEN IFNULL(D.volume, 0) > IFNULL(E.volume, 0) THEN 'PROSES KIRIM'
                    ELSE 'SELESAI KIRIM' END AS status_kirim,
                    A.awal_kontrak,
                    A.akhir_kontrak,
                    A.bae_awal,
                    A.bae_akhir,
                    IFNULL(G.rencana_kirim, '-') AS rencana_kirim,
                    IFNULL(F.tanggal_penerimaan, '-') AS tanggal_penerimaan
                FROM vw_kontrak AS A
                JOIN mst_basket AS B ON A.id_basket = B.id
                JOIN mst_vendor AS C ON A.id_vendor = C.id
                JOIN vol_kontrak AS D ON A.no_kontrak = D.no_kontrak
                LEFT JOIN vol_kirim AS E ON A.no_kontrak = E.no_kontrak
                LEFT JOIN tpengiriman AS F ON A.no_kontrak = F.no_kontrak
                LEFT JOIN rpengiriman AS G ON A.no_kontrak = G.no_kontrak
            )
            SELECT * FROM result $where ORDER BY awal_kontrak DESC";
        return $this->M_AllFunction->CustomQuery($query);
    }

    function ajaxIndex(){
        $hasil = $this->queryIndex();

        $data['data'] = array();

        $i = 1;
        $data['total'] = 0;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->no_kontrak);
            // $row[] = html_escape($h->no_po);
            $row[] = html_escape($h->basket);
            $row[] = html_escape($h->vendor);
            $row[] = html_escape($h->kategori);
            $row[] = "<button onclick=\"getMaterialKontrak('" . html_escape($h->no_kontrak) . "')\" class=\"btn btn-secondary btn-sm w-100\" data-bs-toggle=\"modal\" data-bs-target=\"#kt_modal_trafo\">" . html_escape($h->material) . "</button>";
            $row[] = html_escape($h->awal_kontrak);
            $row[] = html_escape($h->akhir_kontrak);
            $row[] = html_escape($h->bae_awal);
            $row[] = html_escape($h->bae_akhir);
            $row[] = html_escape($h->rencana_kirim);
            $row[] = html_escape($h->tanggal_penerimaan);
            $row[] = html_escape($h->volume_kontrak);
            $row[] = html_escape($h->volume_kirim);
            if(html_escape($h->status_kirim) == "BELUM KIRIM"){
                $row[] = "<span class=\"badge bg-danger\">BELUM KIRIM</span>";
            } else if(html_escape($h->status_kirim) == "PROSES KIRIM"){
                $row[] = "<span class=\"badge bg-warning\">PROSES KIRIM</span>";
            } else {
                $row[] = "<span class=\"badge bg-primary\">SELESAI KIRIM</span>";
            }
            if(html_escape($h->status_kirim) == "SELESAI KIRIM"){
                $row[] = "<button type='button' class='btn btn-outline-secondary btn-sm waves-effect waves-light' onclick=\"detailPengiriman('" . html_escape($h->no_kontrak) . "')\"><i class='fa-regular fa-eye'></i></button>";
            } else {
                $row[] = "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">
                    <button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"inputPengiriman('" . html_escape($h->id) . "')\"><i class=\"fa fa-pencil\"></i></button>
                </div>";
            }

            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    function exportIndex(){
        $hasil = $this->queryIndex();

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

        $sheet->getStyle('A1:P1')->applyFromArray($styleHeading);

        foreach(range('A', 'P') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NO KONTRAK');
        $sheet->setCellValue('C1', 'ANGGARAN');
        $sheet->setCellValue('D1', 'VENDOR');
        $sheet->setCellValue('E1', 'KATEGORI MATERIAL');
        $sheet->setCellValue('F1', 'MATERIAL');
        $sheet->setCellValue('G1', 'AWAL KONTRAK');
        $sheet->setCellValue('H1', 'AKHIR KONTRAK');
        $sheet->setCellValue('I1', 'AWAL BAE');
        $sheet->setCellValue('J1', 'AKHIR BAE');
        $sheet->setCellValue('K1', 'RENCANA KIRIM');
        $sheet->setCellValue('L1', 'TANGGAL TERIMA');
        $sheet->setCellValue('M1', 'VOLUME KONTRAK');
        $sheet->setCellValue('N1', 'VOLUME KIRIM');
        $sheet->setCellValue('P1', 'PENGIRIMAN');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->no_kontrak));
            $sheet->setCellValue('C' . $i, html_escape($h->basket));
            $sheet->setCellValue('D' . $i, html_escape($h->vendor));
            $sheet->setCellValue('E' . $i, html_escape($h->kategori));
            $sheet->setCellValue('F' . $i, html_escape($h->material));
            $sheet->setCellValue('G' . $i, html_escape($h->awal_kontrak));
            $sheet->setCellValue('H' . $i, html_escape($h->akhir_kontrak));
            $sheet->setCellValue('I' . $i, html_escape($h->bae_awal));
            $sheet->setCellValue('J' . $i, html_escape($h->bae_akhir));
            $sheet->setCellValue('K' . $i, html_escape($h->rencana_kirim));
            $sheet->setCellValue('L' . $i, html_escape($h->tanggal_penerimaan));
            $sheet->setCellValue('M' . $i, html_escape($h->volume_kontrak));
            $sheet->setCellValue('N' . $i, html_escape($h->volume_kirim));
            $sheet->setCellValue('P' . $i, html_escape($h->status_kirim));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Daftar_Pengiriman_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function getDetailInputPengiriman(){
        $id = $this->input->post('id', true);
        $data['kontrak'] = $this->M_AllFunction->CustomQuery("SELECT
                A.id,
                A.no_kontrak,
                A.vendor,
                A.basket,
                IFNULL(MAX(B.rencana_kirim), '') AS rencana_kirim
            FROM
                vw_kontrak AS A
            LEFT JOIN trn_pengiriman_hdr AS B
            ON A.no_kontrak = B.no_kontrak WHERE A.id = $id");

        $query = "WITH data AS (
                        SELECT
                                A.tujuan,
                                A.material_id,
                                A.material,
                                A.volume - IFNULL(SUM(B.volume), 0) AS volume
                        FROM
                                vw_kontrak_dtl AS A
                                LEFT JOIN trn_pengiriman_material AS B
                                ON A.no_kontrak = B.no_kontrak AND A.material_id = B.material_id AND B.unit_id = '$this->user_unit_id'
                        WHERE
                            A.tujuan_id = '$this->user_unit_id'
                            AND A.id_hdr = '$id'
                        GROUP BY A.material_id
                    )
                    SELECT * FROM data WHERE volume > 0";

        $data['query'] = $query;

        $hasil = $this->M_AllFunction->CustomQuery($query);

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();
            $row[] = "<input name='tujuan[]' type='text' class='form-control' value='$h->tujuan'/>";
            $row[] = "<input name='material_id[]' type='number' class='form-control' value='$h->material_id'/>";
            $row[] = "<input type='text' class='form-control' value='$h->material' readonly/>";
            $row[] = "<input type='text' class='form-control' value='$h->volume' readonly/>";
            $row[] = "<input name='volume[]' type='number' class='form-control' value='$h->volume' max-value='$h->volume'/>";
            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    function getDetailPengiriman(){
        $no_kontrak = $this->input->post('no_kontrak', true);
        $unit = $this->input->post('unit', true);

        $query = "SELECT
                        A.id,
                        E.name,
                        (SELECT COUNT(DISTINCT(material_id)) FROM trn_pengiriman_material WHERE no_kontrak = A.no_kontrak AND volume <> 0) AS row_span,
                        C.tanggal_penerimaan,
                        A.rencana_kirim,
                        D.id AS id_material,
                        D.material,
                        C.id AS id_pengiriman_material,
                        C.volume,
                        C.status,
                        C.slip_penerimaan,
                        C.no_persediaan,
                        C.tanggal_persediaan,
                        CONCAT(B.directory, '/', B.filename) AS pdf,
                        CONCAT(B.directory, '/', B.foto1) AS foto1,
                        CONCAT(B.directory, '/', B.foto2) AS foto2
                    FROM
                        trn_pengiriman_hdr AS A
                        JOIN trn_pengiriman_dtl AS B
                        ON A.no_kontrak = B.no_kontrak
                        JOIN trn_pengiriman_material AS C
                        ON A.no_kontrak = C.no_kontrak AND B.id = C.pengiriman_dtl_id
                        JOIN vw_material AS D
                        ON C.material_id = D.id
                        JOIN mst_unit AS E
                        ON E.id = C.unit_id
                    WHERE
                        A.no_kontrak = '$no_kontrak'
                        AND C.volume <> 0 AND C.unit_id = $unit
                    ORDER BY B.tanggal_penerimaan";

        $data['data'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('pengiriman/detail_pengiriman', $data);
    }

    function Save() {
        $cek = $this->M_AllFunction->Where('trn_pengiriman_hdr', "no_kontrak = '" . $this->input->post('no_kontrak', true) . "'");
        if(count($cek) == 0){
            $this->session->set_flashdata('flash_failed', 'Harap Update Rencana Kirim Terlebih Dahulu.' . $errornya);
            redirect('C_Pengiriman');
        } else {
            $directory = 'data_uploads/pengiriman/' . date('Y-m');

            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['remove_spaces'] = TRUE;
            $config['max_size'] = 10000;
            $config['upload_path'] = $directory;

            $this->load->library('upload', $config);

            $foto1 = $this->session->userdata('unit_id') . '-' . bin2hex(random_bytes(24));
            $config['file_name'] = $foto1;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('foto1')) {
                $errornya = $this->upload->display_errors();
                $this->session->set_flashdata('flash_failed', 'Maaf Error Ketika Upload Foto1.' . $errornya);
                return redirect('C_Pengiriman');
            } else {
                $extension = end(explode(".", $_FILES['foto1']['name']));
                $foto1 .= "." . $extension;
            }

            $foto2 = $this->session->userdata('unit_id') . '-' . bin2hex(random_bytes(24));
            $config['file_name'] = $foto2;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('foto2')) {
                $errornya = $this->upload->display_errors();
                $this->session->set_flashdata('flash_failed', 'Maaf Error Ketika Upload Foto2.' . $errornya);
                return redirect('C_Pengiriman');
            } else {
                $extension = end(explode(".", $_FILES['foto2']['name']));
                $foto2 .= "." . $extension;
            }

            $filename = $this->session->userdata('unit_id') . '-' . bin2hex(random_bytes(24));
            $config['file_name'] = $filename;
            $this->upload->initialize($config);
            if ($this->upload->do_upload('surat_jalan')) {
                $data = array('upload_data' => $this->upload->data());
                $data = array(
                    'status'             => $this->input->post('status', true)
                );
                $this->M_AllFunction->Update('trn_pengiriman_hdr', $data, "no_kontrak = '" . $this->input->post('no_kontrak', true) . "'");
            } else {
                $errornya = $this->upload->display_errors();
                $this->session->set_flashdata('flash_failed', 'Maaf Error Ketika Upload Surat Jalan.' . $errornya);
                return redirect('C_Pengiriman');
            }

            $data = array(
                'no_kontrak'         => $this->input->post('no_kontrak', true),
                'slip_penerimaan'    => str_replace("'", "", $this->input->post('slip_penerimaan', true)),
                'tanggal_penerimaan' => $this->input->post('tanggal_penerimaan', true),
                "directory"          => $directory,
                "filename"           => $filename,
                "foto1"              => $foto1,
                "foto2"              => $foto2,
                "createdby"          => $this->session->userdata('username'),
                "createddate"        => date('Y-m-d H:i:s')
            );

            $id = $this->M_AllFunction->InsertGetId('trn_pengiriman_dtl', $data);

            for($i = 0; $i < count($this->input->post('volume', true)); $i++){
                $detail = array(
                    'pengiriman_dtl_id'  => $id,
                    'material_id'        => $this->input->post('material_id', true)[$i],
                    'no_kontrak'         => $this->input->post('no_kontrak', true),
                    'volume'             => $this->input->post('volume', true)[$i],
                    'slip_penerimaan'    => $this->input->post('slip_penerimaan', true),
                    'tanggal_penerimaan' => $this->input->post('tanggal_penerimaan', true),
                    "unit_id"            => $this->session->userdata("unit_id"),
                    "createdby"          => $this->session->userdata('username'),
                    "createddate"        => date('Y-m-d H:i:s')
                );
                $this->M_AllFunction->Insert('trn_pengiriman_material', $detail);
            }
            $this->session->set_flashdata('flash_success', 'Berhasil Menginputkan Data.');
            redirect('C_Pengiriman');
        }
    }

    function updateRencanaKirim(){
        $data = array(
            "rencana_kirim" => $this->input->post('rencana_kirim', true),
            'no_kontrak'    => $this->input->post('no_kontrak', true),
            "unit_id"       => $this->session->userdata("unit_id"),
            "createdby"     => $this->session->userdata('username'),
            "createddate"   => date('Y-m-d H:i:s')
        );

        $cek = $this->M_AllFunction->Where('trn_pengiriman_hdr', "no_kontrak = '" . $this->input->post('no_kontrak', true) . "'");
        if(count($cek) > 0){
            if($this->M_AllFunction->Update('trn_pengiriman_hdr', $data, "no_kontrak = '" . $this->input->post('no_kontrak', true) . "'")){
                $this->session->set_flashdata('flash_success', 'Berhasil Update Rencana Pengiriman.');
            } else {
                $this->session->set_flashdata('flash_failed', 'Gagal Update Rencana Pengiriman.');
            }
        } else {
            if($this->M_AllFunction->Insert('trn_pengiriman_hdr', $data)){
                $this->session->set_flashdata('flash_success', 'Berhasil Menginput Rencana Pengiriman.');
            } else {
                $this->session->set_flashdata('flash_failed', 'Gagal Menginput Rencana Pengiriman.');
            }
        }
        redirect('C_Pengiriman');
    }

    function updateStatus(){
        $slip_penerimaan = $this->input->post('slip_penerimaan', true);
        $id_material = $this->input->post('id_material', true);
        $data = array(
            "no_persediaan"      => $this->input->post('no_persediaan', true),
            "tanggal_persediaan" => $this->input->post('tanggal_persediaan', true),
            "status"             => "PERSEDIAAN"
        );
        if($this->M_AllFunction->Update('trn_pengiriman_material', $data, "slip_penerimaan = '$slip_penerimaan' AND material_id = '$id_material'")){
            $this->session->set_flashdata('flash_success', 'Berhasil Update Status Persediaan.');
        } else {
            $this->session->set_flashdata('flash_failed', 'Gagal Update Status Persediaan.');
        }
        redirect('C_Pengiriman');
    }

    function updateSlip(){
        $data = array(
            "slip_penerimaan" => $this->input->post('slip_penerimaan_edit', true)
        );
        if($this->M_AllFunction->Update('trn_pengiriman_material', $data, "id = '" . $this->input->post('id_pengiriman_material', true) . "'")){
            $this->session->set_flashdata('flash_success', 'Berhasil Update Slip Persediaan.');
        } else {
            $this->session->set_flashdata('flash_failed', 'Gagal Update Slip Persediaan.');
        }
        redirect('C_Pengiriman');
    }
}