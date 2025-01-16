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

class C_ITO extends CI_Controller {

	function __construct(){
		parent::__construct();

        $this->load->model(array("M_AllFunction"));

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
        $this->template->display('ito/index');
    }

    function ajaxIndex(){
        $data['data'] = $this->M_AllFunction->Where('vw_ito_gabungan', "periode_akhir = '" . html_escape($this->input->post('periode')) . "-01'");
        $this->load->view('ito/index_ajax', $data);
    }

	public function KertasKerja(){
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $this->template->display('ito/kertas_kerja', $data);
	}

    function getKertasKerjaData(){
        $unit = "";
        $field = "";
        $penjelasan = "";
        if($this->input->post('unit') != "*"){
            $unit .= "unit = '" . $this->input->post('unit') . "' AND ";
            $penjelasan .= "penjelasan";
        } else {
            $penjelasan .= "'-' AS penjelasan";
        }
        $periode = $this->input->post('periode') . '-01';
        $query = "SELECT
                    no,
                    YEAR(periode_awal) AS tahun,
                    DAY(LAST_DAY(CONCAT(YEAR(periode_awal), '-02-01'))) AS akhir,
                    uraian,
                    $field
                    SUM(saldo_awal) AS saldo_awal,
                    SUM(tbh_pembelian) AS tbh_pembelian,
                    SUM(tbh_relokasi) AS tbh_relokasi,
                    SUM(tbh_reklas) AS tbh_reklas,
                    SUM(tbh_mutasi_hapus) AS tbh_mutasi_hapus,
                    SUM(tbh_mutasi_bursa) AS tbh_mutasi_bursa,
                    SUM(tbh_lainnya) AS tbh_lainnya,
                    SUM(krg_ke_biaya) AS krg_ke_biaya,
                    SUM(krg_ke_at) AS krg_ke_at,
                    SUM(krg_ke_pdp) AS krg_ke_pdp,
                    SUM(krg_relokasi) AS krg_relokasi,
                    SUM(krg_reklas) AS krg_reklas,
                    SUM(krg_mutasi_hapus) AS krg_mutasi_hapus,
                    SUM(krg_mutasi_bursa) AS krg_mutasi_bursa,
                    SUM(krg_lainnya) AS krg_lainnya,
                    SUM(saldo_akhir) AS saldo_akhir,
                    SUM(reklas_qa) AS reklas_qa,
                    SUM(saldo_akhir2) AS saldo_akhir2,
                    $penjelasan
                FROM `trn_kertas_kerja_persediaan`
                WHERE $unit periode_akhir = '$periode' AND no IS NOT NULL
                GROUP BY no
                ORDER BY no";
        $data['unit'] = $this->M_AllFunction->Where('mst_unit', "kode_unit = '" . $this->input->post('unit') . "'");
        $data['data'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('ito/kertas_kerja_table', $data);
    }

    public function import(){
        $config['allowed_types'] = 'xls|xlsx';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;

        $filekontrakname = "";
        $file_ito_location = 'data_uploads/ito/';

        $config['upload_path'] = $file_ito_location;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('upload_file')) {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('flash_failed', $error['error']);
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
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
                $unit = $this->M_AllFunction->Get('mst_unit');

                $this->M_AllFunction->Delete('trn_kertas_kerja_persediaan', "periode_akhir = '" . $this->input->post('periode_upload', true) . "-01'");

                foreach ($unit as $u) {
                    if($spreadsheet->getSheetByName($u->kode_unit) == null){
                        continue;
                    }
                    for($i = 11; $i < 32; $i++){
                        if($spreadsheet->getSheetByName($u->kode_unit)->getCell('B' . $i)->getCalculatedValue() == null){
                            continue;
                        }
                        $data = array(
                            "periode_awal"     => explode("-", $this->input->post('periode_upload', true))[0] . "-01-01",
                            "periode_akhir"    => $this->input->post('periode_upload', true) . "-01",
                            "no"               => $spreadsheet->getSheetByName($u->kode_unit)->getCell('A' . $i)->getCalculatedValue(),
                            "uraian"           => $spreadsheet->getSheetByName($u->kode_unit)->getCell('B' . $i)->getCalculatedValue(),
                            "saldo_awal"       => $spreadsheet->getSheetByName($u->kode_unit)->getCell('C' . $i)->getCalculatedValue() ?? 0,
                            "tbh_pembelian"    => $spreadsheet->getSheetByName($u->kode_unit)->getCell('D' . $i)->getCalculatedValue() ?? 0,
                            "tbh_relokasi"     => $spreadsheet->getSheetByName($u->kode_unit)->getCell('E' . $i)->getCalculatedValue() ?? 0,
                            "tbh_reklas"       => $spreadsheet->getSheetByName($u->kode_unit)->getCell('F' . $i)->getCalculatedValue() ?? 0,
                            "tbh_mutasi_hapus" => $spreadsheet->getSheetByName($u->kode_unit)->getCell('G' . $i)->getCalculatedValue() ?? 0,
                            "tbh_mutasi_bursa" => $spreadsheet->getSheetByName($u->kode_unit)->getCell('H' . $i)->getCalculatedValue() ?? 0,
                            "tbh_lainnya"      => $spreadsheet->getSheetByName($u->kode_unit)->getCell('I' . $i)->getCalculatedValue() ?? 0,
                            "krg_ke_biaya"     => $spreadsheet->getSheetByName($u->kode_unit)->getCell('J' . $i)->getCalculatedValue() ?? 0,
                            "krg_ke_at"        => $spreadsheet->getSheetByName($u->kode_unit)->getCell('K' . $i)->getCalculatedValue() ?? 0,
                            "krg_ke_pdp"       => $spreadsheet->getSheetByName($u->kode_unit)->getCell('L' . $i)->getCalculatedValue() ?? 0,
                            "krg_relokasi"     => $spreadsheet->getSheetByName($u->kode_unit)->getCell('M' . $i)->getCalculatedValue() ?? 0,
                            "krg_reklas"       => $spreadsheet->getSheetByName($u->kode_unit)->getCell('N' . $i)->getCalculatedValue() ?? 0,
                            "krg_mutasi_hapus" => $spreadsheet->getSheetByName($u->kode_unit)->getCell('O' . $i)->getCalculatedValue() ?? 0,
                            "krg_mutasi_bursa" => $spreadsheet->getSheetByName($u->kode_unit)->getCell('P' . $i)->getCalculatedValue() ?? 0,
                            "krg_lainnya"      => $spreadsheet->getSheetByName($u->kode_unit)->getCell('Q' . $i)->getCalculatedValue() ?? 0,
                            "saldo_akhir"      => $spreadsheet->getSheetByName($u->kode_unit)->getCell('R' . $i)->getCalculatedValue() ?? 0,
                            "reklas_qa"        => $spreadsheet->getSheetByName($u->kode_unit)->getCell('S' . $i)->getCalculatedValue() ?? 0,
                            "saldo_akhir2"     => $spreadsheet->getSheetByName($u->kode_unit)->getCell('T' . $i)->getCalculatedValue() ?? 0,
                            "penjelasan"       => $spreadsheet->getSheetByName($u->kode_unit)->getCell('U' . $i)->getCalculatedValue(),
                            "unit"             => $u->kode_unit,
                            "created_by"       => $this->session->userdata('username'),
                        );
                        $this->M_AllFunction->Insert('trn_kertas_kerja_persediaan', $data);
                    }
                }
                $this->session->set_flashdata('flash_success', "data uploaded");
            } else {
                $this->session->set_flashdata('flash_failed', "there's an error when uploading data");
            }
        }
        redirect("C_ITO/KertasKerja");
    }

    function Dashboard(){
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $this->template->display('ito/dashboard_ito', $data);
    }

    function realisasi_ito(){
        $unit = $this->input->post('unit', true);
        $periode = $this->input->post('periode', true) . "-01";
        if($this->input->post('tampilan', true) == "GRAFIK"){
            if($unit == "*"){
                $query = "SELECT
                    vw_ito_gabungan.unit,
                    mst_unit.singkatan,
                    vw_ito_gabungan.periode_akhir,
                    vw_ito_gabungan.ito
                FROM vw_ito_gabungan
                LEFT JOIN mst_unit
                ON vw_ito_gabungan.unit = mst_unit.kode_unit
                WHERE vw_ito_gabungan.periode_akhir = '$periode' -- AND vw_ito_gabungan.unit <> 5401
                ORDER BY mst_unit.singkatan, vw_ito_gabungan.periode_akhir";
                $data['non_uid'] = $this->M_AllFunction->CustomQuery($query);
                $query = "SELECT periode_akhir, (SUM(krg_ke_biaya) + SUM(krg_ke_at) + SUM(krg_ke_pdp)) / ((SUM(saldo_awal) + SUM(saldo_akhir)) / 2) AS hasil
                        FROM vw_ito_gabungan
                        WHERE periode_akhir = '$periode'";
                $data['uid'] = $this->M_AllFunction->CustomQuery($query);
                // $data['unit'] = $this->M_AllFunction->CustomQuery("SELECT * FROM mst_unit WHERE kode_unit <> 5401 ORDER BY singkatan");
                $data['unit'] = $this->M_AllFunction->CustomQuery("SELECT * FROM mst_unit ORDER BY singkatan");
                $query_target = "SELECT kode_unit,
                                    CASE
                                        WHEN MONTH('$periode') = 1 THEN jan
                                        WHEN MONTH('$periode') = 2 THEN feb
                                        WHEN MONTH('$periode') = 3 THEN mar
                                        WHEN MONTH('$periode') = 4 THEN apr
                                        WHEN MONTH('$periode') = 5 THEN mei
                                        WHEN MONTH('$periode') = 6 THEN jun
                                        WHEN MONTH('$periode') = 7 THEN jul
                                        WHEN MONTH('$periode') = 8 THEN agts
                                        WHEN MONTH('$periode') = 9 THEN sep
                                        WHEN MONTH('$periode') = 10 THEN okt
                                        WHEN MONTH('$periode') = 11 THEN nov
                                        WHEN MONTH('$periode') = 12 THEN des
                                    END AS target
                                FROM trn_ito_target
                                WHERE periode = YEAR('$periode');";
                $data['target'] = $this->M_AllFunction->CustomQuery($query_target);
                $this->load->view('ito/dashboard_realisasi_ito', $data);
            } else {
                $query = "SELECT
                        mst_bulan.singkatan,
                        IFNULL(vw_ito_gabungan.ito, 0) AS ito,
                        IFNULL(CASE
                            WHEN LOWER(mst_bulan.singkatan) = 'jan' THEN trn_ito_target.jan
                            WHEN LOWER(mst_bulan.singkatan) = 'feb' THEN trn_ito_target.feb
                            WHEN LOWER(mst_bulan.singkatan) = 'mar' THEN trn_ito_target.mar
                            WHEN LOWER(mst_bulan.singkatan) = 'apr' THEN trn_ito_target.apr
                            WHEN LOWER(mst_bulan.singkatan) = 'mei' THEN trn_ito_target.mei
                            WHEN LOWER(mst_bulan.singkatan) = 'jun' THEN trn_ito_target.jun
                            WHEN LOWER(mst_bulan.singkatan) = 'jul' THEN trn_ito_target.jul
                            WHEN LOWER(mst_bulan.singkatan) = 'agts' THEN trn_ito_target.agts
                            WHEN LOWER(mst_bulan.singkatan) = 'sep' THEN trn_ito_target.sep
                            WHEN LOWER(mst_bulan.singkatan) = 'okt' THEN trn_ito_target.okt
                            WHEN LOWER(mst_bulan.singkatan) = 'nov' THEN trn_ito_target.nov
                            WHEN LOWER(mst_bulan.singkatan) = 'des' THEN trn_ito_target.des
                        END, 0) AS target
                FROM mst_bulan
                LEFT JOIN vw_ito_gabungan
                ON mst_bulan.bulan = MONTH(vw_ito_gabungan.periode_akhir) AND unit = $unit
                LEFT JOIN trn_ito_target
                ON trn_ito_target.periode = YEAR('$periode') AND trn_ito_target.kode_unit = $unit";
                $data['data'] = $this->M_AllFunction->CustomQuery($query);
                $this->load->view('ito/dashboard_realisasi_ito_unit', $data);
            }
        } else {
            $query = "SELECT
                        mst_unit.kode_unit AS unit,
                        mst_unit.name,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 1 AND mst_unit.kode_unit = unit) AS jan,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 2 AND mst_unit.kode_unit = unit) AS feb,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 3 AND mst_unit.kode_unit = unit) AS mar,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 4 AND mst_unit.kode_unit = unit) AS apr,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 5 AND mst_unit.kode_unit = unit) AS mei,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 6 AND mst_unit.kode_unit = unit) AS jun,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 7 AND mst_unit.kode_unit = unit) AS jul,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 8 AND mst_unit.kode_unit = unit) AS agts,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 9 AND mst_unit.kode_unit = unit) AS sep,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 10 AND mst_unit.kode_unit = unit) AS okt,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 11 AND mst_unit.kode_unit = unit) AS nov,
                        (SELECT ito FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 12 AND mst_unit.kode_unit = unit) AS des
                    FROM mst_unit
                    ORDER BY mst_unit.urutan, mst_unit.singkatan";
            $data['data'] = $this->M_AllFunction->CustomQuery($query);
            $this->load->view('ito/dashboard_realisasi_ito_tabel', $data);
        }
    }

    function saldo_persediaan_material_pie(){
        $unit = $this->input->post('unit', true) !== "*" ? " AND unit = " . $this->input->post('unit', true) : "";
        $periode = $this->input->post('periode', true) . "-01";
        $query = "SELECT
                    no,
                    uraian,
                    SUM(saldo_akhir) AS saldo_akhir
                FROM trn_kertas_kerja_persediaan
                WHERE periode_akhir = '$periode' AND no >= 7 AND saldo_akhir <> 0 $unit
                GROUP BY no";
        $data['data'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('ito/dashboard_saldo_persediaan_material_pie', $data);
    }

    function saldo_persediaan_material_bar(){
        $unit = $this->input->post('unit', true);
        $periode = $this->input->post('periode', true) . "-01";
        if($this->input->post('tampilan', true) == "GRAFIK"){
            if($unit == "*"){
                $query = "SELECT
                            vw_ito_gabungan.unit,
                            mst_unit.singkatan,
                            vw_ito_gabungan.periode_akhir,
                            vw_ito_gabungan.saldo_akhir / 1000000000 AS saldo_akhir
                        FROM vw_ito_gabungan
                        LEFT JOIN mst_unit
                        ON vw_ito_gabungan.unit = mst_unit.kode_unit
                        WHERE vw_ito_gabungan.periode_akhir = '$periode'";
                $data['non_uid'] = $this->M_AllFunction->CustomQuery($query);
                $query = "SELECT SUM(saldo_akhir) / 1000000000 AS saldo_akhir
                        FROM `vw_ito_gabungan`
                        WHERE periode_akhir = '$periode'";
                $data['uid'] = $this->M_AllFunction->CustomQuery($query);
                $query_target = "SELECT kode_unit,
                                    CASE
                                        WHEN MONTH('$periode') = 1 THEN jan / 1000000000
                                        WHEN MONTH('$periode') = 2 THEN feb / 1000000000
                                        WHEN MONTH('$periode') = 3 THEN mar / 1000000000
                                        WHEN MONTH('$periode') = 4 THEN apr / 1000000000
                                        WHEN MONTH('$periode') = 5 THEN mei / 1000000000
                                        WHEN MONTH('$periode') = 6 THEN jun / 1000000000
                                        WHEN MONTH('$periode') = 7 THEN jul / 1000000000
                                        WHEN MONTH('$periode') = 8 THEN agts / 1000000000
                                        WHEN MONTH('$periode') = 9 THEN sep / 1000000000
                                        WHEN MONTH('$periode') = 10 THEN okt / 1000000000
                                        WHEN MONTH('$periode') = 11 THEN nov / 1000000000
                                        WHEN MONTH('$periode') = 12 THEN des / 1000000000
                                    END AS target
                                FROM trn_ito_target_saldo
                                WHERE periode = YEAR('$periode');";
                $data['target'] = $this->M_AllFunction->CustomQuery($query_target);
                // $data['unit'] = $this->M_AllFunction->CustomQuery("SELECT * FROM mst_unit WHERE kode_unit <> 5401 ORDER BY singkatan");
                $data['unit'] = $this->M_AllFunction->CustomQuery("SELECT * FROM mst_unit ORDER BY singkatan");
                $this->load->view('ito/dashboard_saldo_persediaan_material_bar', $data);
            } else {
                $query = "SELECT
                        mst_bulan.singkatan,
                        IFNULL(vw_ito_gabungan.saldo_akhir / 1000000000, 0) AS saldo_akhir,
                        IFNULL(CASE
                            WHEN LOWER(mst_bulan.singkatan) = 'jan' THEN trn_ito_target_saldo.jan / 1000000000
                            WHEN LOWER(mst_bulan.singkatan) = 'feb' THEN trn_ito_target_saldo.feb / 1000000000
                            WHEN LOWER(mst_bulan.singkatan) = 'mar' THEN trn_ito_target_saldo.mar / 1000000000
                            WHEN LOWER(mst_bulan.singkatan) = 'apr' THEN trn_ito_target_saldo.apr / 1000000000
                            WHEN LOWER(mst_bulan.singkatan) = 'mei' THEN trn_ito_target_saldo.mei / 1000000000
                            WHEN LOWER(mst_bulan.singkatan) = 'jun' THEN trn_ito_target_saldo.jun / 1000000000
                            WHEN LOWER(mst_bulan.singkatan) = 'jul' THEN trn_ito_target_saldo.jul / 1000000000
                            WHEN LOWER(mst_bulan.singkatan) = 'agts' THEN trn_ito_target_saldo.agts / 1000000000
                            WHEN LOWER(mst_bulan.singkatan) = 'sep' THEN trn_ito_target_saldo.sep / 1000000000
                            WHEN LOWER(mst_bulan.singkatan) = 'okt' THEN trn_ito_target_saldo.okt / 1000000000
                            WHEN LOWER(mst_bulan.singkatan) = 'nov' THEN trn_ito_target_saldo.nov / 1000000000
                            WHEN LOWER(mst_bulan.singkatan) = 'des' THEN trn_ito_target_saldo.des / 1000000000
                        END, 0) AS target
                    FROM mst_bulan
                    LEFT JOIN vw_ito_gabungan
                    ON mst_bulan.bulan = MONTH(vw_ito_gabungan.periode_akhir) AND unit = $unit
                    LEFT JOIN trn_ito_target_saldo
                    ON trn_ito_target_saldo.periode = YEAR('$periode') AND trn_ito_target_saldo.kode_unit = $unit";
                $data['data'] = $this->M_AllFunction->CustomQuery($query);
                $this->load->view('ito/dashboard_saldo_persediaan_material_bar_unit', $data);
            }
        } else {
            $query = "SELECT
                        mst_unit.kode_unit AS unit,
                        mst_unit.name,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 1 AND mst_unit.kode_unit = unit) AS jan,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 2 AND mst_unit.kode_unit = unit) AS feb,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 3 AND mst_unit.kode_unit = unit) AS mar,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 4 AND mst_unit.kode_unit = unit) AS apr,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 5 AND mst_unit.kode_unit = unit) AS mei,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 6 AND mst_unit.kode_unit = unit) AS jun,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 7 AND mst_unit.kode_unit = unit) AS jul,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 8 AND mst_unit.kode_unit = unit) AS agts,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 9 AND mst_unit.kode_unit = unit) AS sep,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 10 AND mst_unit.kode_unit = unit) AS okt,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 11 AND mst_unit.kode_unit = unit) AS nov,
                        (SELECT saldo_akhir FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 12 AND mst_unit.kode_unit = unit) AS des
                    FROM mst_unit
                    ORDER BY mst_unit.urutan, mst_unit.singkatan";
            $data['data'] = $this->M_AllFunction->CustomQuery($query);
            $data['judul'] = "Tabel Saldo Persediaan Material";
            $data['tabel_id'] = "saldo";
            $this->load->view('ito/dashboard_saldo_persediaan_material_bar_tabel', $data);
        }
    }

    function ajax_rencanapemakaian(){
        $unit = $this->input->post('unit', true);
        $periode = $this->input->post('periode', true) . "-01";
        if($this->input->post('tampilan', true) == "GRAFIK"){
            if($unit == "*"){
                $query = "SELECT
                            mst_unit.singkatan,
                            (krg_ke_biaya + krg_ke_at + krg_ke_pdp) / 1000000000 AS rencana
                        FROM vw_ito_gabungan
                        LEFT JOIN mst_unit
                        ON vw_ito_gabungan.unit = mst_unit.kode_unit
                        WHERE vw_ito_gabungan.periode_akhir = '$periode'
                        ORDER BY mst_unit.singkatan";
                $data['data'] = $this->M_AllFunction->CustomQuery($query);
                $this->load->view('ito/dashboard_rencana_pemakaian', $data);
            } else {
                //ini belum dirubah ke bulanan
                $query = "SELECT
                            mst_bulan.singkatan,
                            (krg_ke_biaya + krg_ke_at + krg_ke_pdp) / 1000000000 AS rencana
                        FROM mst_bulan
                        LEFT JOIN vw_ito_gabungan
                        ON vw_ito_gabungan.bulan = mst_bulan.bulan
                        WHERE unit = '$unit' AND vw_ito_gabungan.tahun = YEAR('$periode')";
                $data['data'] = $this->M_AllFunction->CustomQuery($query);
                $this->load->view('ito/dashboard_rencana_pemakaian_unit', $data);
            }
        } else {
            $query = "SELECT
                        mst_unit.kode_unit AS unit,
                        mst_unit.name,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 1 AND mst_unit.kode_unit = unit) AS jan,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 2 AND mst_unit.kode_unit = unit) AS feb,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 3 AND mst_unit.kode_unit = unit) AS mar,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 4 AND mst_unit.kode_unit = unit) AS apr,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 5 AND mst_unit.kode_unit = unit) AS mei,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 6 AND mst_unit.kode_unit = unit) AS jun,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 7 AND mst_unit.kode_unit = unit) AS jul,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 8 AND mst_unit.kode_unit = unit) AS agts,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 9 AND mst_unit.kode_unit = unit) AS sep,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 10 AND mst_unit.kode_unit = unit) AS okt,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 11 AND mst_unit.kode_unit = unit) AS nov,
                        (SELECT (krg_ke_biaya + krg_ke_at + krg_ke_pdp) FROM vw_ito_gabungan AS B WHERE tahun = YEAR('$periode') AND bulan = 12 AND mst_unit.kode_unit = unit) AS des
                    FROM mst_unit
                    ORDER BY mst_unit.urutan, mst_unit.singkatan";
            $data['data'] = $this->M_AllFunction->CustomQuery($query);
            $data['judul'] = "Tabel Pemakaian Material";
            $data['tabel_id'] = "rencana";
            $this->load->view('ito/dashboard_saldo_persediaan_material_bar_tabel', $data);
        }
    }

    function TargetRealisasi(){
        $this->template->display('ito/target_realisasi');
    }

    function AjaxTargetRealisasi(){
        $query = "SELECT mst_unit.name, trn_ito_target.*
            FROM mst_unit
            LEFT JOIN trn_ito_target
            ON mst_unit.kode_unit = trn_ito_target.kode_unit
            WHERE periode = '" . html_escape($this->input->post('periode')) . "'";

        $hasil = $this->M_AllFunction->CustomQuery($query);

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = html_escape($h->name);
            $row[] = html_escape(number_format($h->jan, 2, ",", "."));
            $row[] = html_escape(number_format($h->feb, 2, ",", "."));
            $row[] = html_escape(number_format($h->mar, 2, ",", "."));
            $row[] = html_escape(number_format($h->apr, 2, ",", "."));
            $row[] = html_escape(number_format($h->mei, 2, ",", "."));
            $row[] = html_escape(number_format($h->jun, 2, ",", "."));
            $row[] = html_escape(number_format($h->jul, 2, ",", "."));
            $row[] = html_escape(number_format($h->agts, 2, ",", "."));
            $row[] = html_escape(number_format($h->sep, 2, ",", "."));
            $row[] = html_escape(number_format($h->okt, 2, ",", "."));
            $row[] = html_escape(number_format($h->nov, 2, ",", "."));
            $row[] = html_escape(number_format($h->des, 2, ",", "."));
            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    function upload_target(){
        $username = str_replace(".", "_", $this->session->userdata('username'));
        $config['allowed_types'] = 'xls|xlsx';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;
        $config['upload_path'] = "data_uploads/ito/target";
        $config['file_name'] = "target-" . $username . " " . date('Y-m-d H-i-s');

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('upload_file')) {
            $this->session->set_flashdata('flash_failed', 'Format File Tidak Sesuai');
            redirect('C_ITO/TargetRealisasi');
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
                $this->M_AllFunction->Delete('trn_ito_target', "periode = '" . $this->input->post('periode', true) . "'");
                for ($i = 1; $i < count($sheetData); $i++) {
                    if ($sheetData[$i][0] != '') {
                        $data = array(
                            "kode_unit"  => $sheetData[$i][0],
                            "periode"    => $this->input->post('periode', true),
                            "jan"        => $sheetData[$i][2],
                            "feb"        => $sheetData[$i][3],
                            "mar"        => $sheetData[$i][4],
                            "apr"        => $sheetData[$i][5],
                            "mei"        => $sheetData[$i][6],
                            "jun"        => $sheetData[$i][7],
                            "jul"        => $sheetData[$i][8],
                            "agts"       => $sheetData[$i][9],
                            "sep"        => $sheetData[$i][10],
                            "okt"        => $sheetData[$i][11],
                            "nov"        => $sheetData[$i][12],
                            "des"        => $sheetData[$i][13],
                            "created_by" => $this->session->userdata('username'),
                        );
                        $this->M_AllFunction->Insert('trn_ito_target', $data);
                    }
                }
            }
            redirect('C_ITO/TargetRealisasi');
        }
    }

    function TargetSaldoPersediaan(){
        $this->template->display('ito/target_saldo_persediaan');
    }

    function AjaxTargetSaldoPersediaan(){
        $query = "SELECT mst_unit.name, trn_ito_target_saldo.*
            FROM mst_unit
            LEFT JOIN trn_ito_target_saldo
            ON mst_unit.kode_unit = trn_ito_target_saldo.kode_unit
            WHERE periode = '" . html_escape($this->input->post('periode')) . "'";

        $hasil = $this->M_AllFunction->CustomQuery($query);

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = html_escape($h->name);
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

    function upload_saldo_persediaan(){
        $username = str_replace(".", "_", $this->session->userdata('username'));
        $config['allowed_types'] = 'xls|xlsx';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;
        $config['upload_path'] = "data_uploads/ito/target";
        $config['file_name'] = "target-" . $username . " " . date('Y-m-d H-i-s');

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('upload_file')) {
            $this->session->set_flashdata('flash_failed', 'Format File Tidak Sesuai');
            redirect('C_ITO/TargetRealisasi');
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
                $this->M_AllFunction->Delete('trn_ito_target_saldo', "periode = '" . $this->input->post('periode', true) . "'");
                for ($i = 1; $i < count($sheetData); $i++) {
                    if ($sheetData[$i][0] != '') {
                        $data = array(
                            "kode_unit"  => $sheetData[$i][0],
                            "periode"    => $this->input->post('periode', true),
                            "jan"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][2])),
                            "feb"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][3])),
                            "mar"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][4])),
                            "apr"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][5])),
                            "mei"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][6])),
                            "jun"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][7])),
                            "jul"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][8])),
                            "agts"       => str_replace('.', '', str_replace(',', '', $sheetData[$i][9])),
                            "sep"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][10])),
                            "okt"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][11])),
                            "nov"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][12])),
                            "des"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][13])),
                            "created_by" => $this->session->userdata('username'),
                        );
                        $this->M_AllFunction->Insert('trn_ito_target_saldo', $data);
                    }
                }
            }
            redirect('C_ITO/TargetSaldoPersediaan');
        }
    }

    function TargetRencanaPemakaian(){
        $this->template->display('ito/target_rencana_pemakaian');
    }

    function AjaxTargetRencanaPemakaian(){
        $query = "SELECT mst_unit.name, trn_ito_target_rencana_pemakaian.*
            FROM mst_unit
            LEFT JOIN trn_ito_target_rencana_pemakaian
            ON mst_unit.kode_unit = trn_ito_target_rencana_pemakaian.kode_unit
            WHERE periode = '" . html_escape($this->input->post('periode')) . "'";

        $hasil = $this->M_AllFunction->CustomQuery($query);

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = html_escape($h->name);
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

    function upload_rencana_pemakaian(){
        $username = str_replace(".", "_", $this->session->userdata('username'));
        $config['allowed_types'] = 'xls|xlsx';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;
        $config['upload_path'] = "data_uploads/ito/target";
        $config['file_name'] = "target-" . $username . " " . date('Y-m-d H-i-s');

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('upload_file')) {
            $this->session->set_flashdata('flash_failed', 'Format File Tidak Sesuai');
            redirect('C_ITO/TargetRencanaPemakaian');
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
                $this->M_AllFunction->Delete('trn_ito_target_rencana_pemakaian', "periode = '" . $this->input->post('periode', true) . "'");
                for ($i = 1; $i < count($sheetData); $i++) {
                    if ($sheetData[$i][0] != '') {
                        $data = array(
                            "kode_unit"  => $sheetData[$i][0],
                            "periode"    => $this->input->post('periode', true),
                            "jan"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][2])),
                            "feb"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][3])),
                            "mar"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][4])),
                            "apr"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][5])),
                            "mei"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][6])),
                            "jun"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][7])),
                            "jul"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][8])),
                            "agts"       => str_replace('.', '', str_replace(',', '', $sheetData[$i][9])),
                            "sep"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][10])),
                            "okt"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][11])),
                            "nov"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][12])),
                            "des"        => str_replace('.', '', str_replace(',', '', $sheetData[$i][13])),
                            "created_by" => $this->session->userdata('username'),
                        );
                        $this->M_AllFunction->Insert('trn_ito_target_rencana_pemakaian', $data);
                    }
                }
            }
            redirect('C_ITO/TargetRencanaPemakaian');
        }
    }
}