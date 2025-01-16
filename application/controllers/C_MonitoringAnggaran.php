<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 * @property Uri $uri
 */

class C_MonitoringAnggaran extends CI_Controller
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
                $this->session->set_flashdata('flash_failed', 'User Di Non Aktifkan.');
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

    function index(){
        $data['basket'] = $this->M_AllFunction->Where("mst_basket", "id <> 4");
        $this->template->display('monitoring_anggaran/index', $data);
    }

    function queryIndex(){
        $where = "";
        if($this->input->post('basket_id', true) != "*"){
            $where .= "WHERE trn_skki_hdr.basket_id = '" . $this->input->post('basket_id', true) . "'";
        }
        if($this->input->post('jenis', true) != "*"){
            $where .= $where != "" ? " AND " : " WHERE ";
            $where .= "trn_skki_hdr.jenis = '" . $this->input->post('jenis', true) . "'";
        }
        if($this->input->post('tahun', true) != "*"){
            $where .= $where != "" ? " AND " : " WHERE ";
            $where .= "trn_skki_hdr.tahun = '" . $this->input->post('tahun', true) . "'";
        }
        $where .= $where != "" ? " AND " : " WHERE ";
        $where .= " unit = " . $this->session->userdata('unit_id');

        $query = "SELECT trn_skki_hdr.*, mst_unit.name FROM trn_skki_hdr
            LEFT JOIN mst_unit
            ON trn_skki_hdr.unit = mst_unit.id
            $where
            ORDER BY tahun DESC, jenis DESC, basket_id";
        return $this->M_AllFunction->CustomQuery($query);
    }

    function getIndexAjax(){
        $hasil = $this->queryIndex();

        $data['data'] = array();

        $i = 1;
        $data['total'] = 0;
        $total_durasi = 0;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->name);
            $row[] = html_escape($h->basket_id) < 5 ? 'B' . html_escape($h->basket_id) : "Sarana / Prasarana";
            $row[] = html_escape($h->no_skki);
            $row[] = html_escape($h->tanggal);
            $row[] = html_escape($h->jenis) == "M" ? "MURNI" : "LUNCURAN";
            $row[] = number_format(html_escape($h->nilai_aki), 0, ".", ".");
            $row[] = html_escape($h->tahun);
            $row[] = "<button class='btn btn-text-danger btn-hover-light-danger btn-sm' onclick=\"showDokumen('" .html_escape($h->id) . "')\"><i class=\"fa fa-file-pdf\"></i> PDF</button>";
            $row[] = "<button class='btn btn-outline-secondary btn-sm' onclick=\"editForm('" . html_escape($h->id) ."')\"><i class=\"fa fa-pencil\"></i></button>";
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

        $sheet->getStyle('A1:G1')->applyFromArray($styleHeading);
        $sheet->getStyle('F')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'BASKET');
        $sheet->setCellValue('C1', 'NOMOR SKKI');
        $sheet->setCellValue('D1', 'TANGGAL');
        $sheet->setCellValue('E1', 'JENIS');
        $sheet->setCellValue('F1', 'AKI');
        $sheet->setCellValue('G1', 'TAHUN');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->basket_id));
            $sheet->setCellValue('C' . $i, html_escape($h->no_skki));
            $sheet->setCellValue('D' . $i, html_escape($h->tanggal));
            $sheet->setCellValue('E' . $i, html_escape($h->jenis) == "M" ? "MURNI" : "LUNCURAN");
            $sheet->setCellValue('F' . $i, html_escape($h->nilai_aki));
            $sheet->setCellValue('G' . $i, html_escape($h->tahun));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Daftar_SKKI_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function getSKKIHDR(){
        $data = $this->M_AllFunction->Where("trn_skki_hdr", "id = '" . $this->input->post('id', true) . "'");
        echo json_encode($data);
    }

    function getDokumen(){
        $data['id_skki'] = $this->input->post('id', true);
        $data['data'] = $this->M_AllFunction->Where("trn_skki_document", "id_skki = '" . $this->input->post('id', true) . "'");
        $this->load->view('monitoring_anggaran/dokumen', $data);
    }

    function SaveDocument(){
        $id_skki   = $this->input->post('id_skki');
        $revisi    = $this->input->post('revisi');
        $file_name = wordwrap(strtoupper(bin2hex(random_bytes(32))), 8, '-', true);
        $path      = "data_uploads/skki/";

        $config['allowed_types']    = 'pdf';
        $config['remove_spaces']    = TRUE;
        $config['max_size']         = 2000;
        $config['upload_path']      = $path;
        $config['file_name']        = $file_name;
        $config['overwrite']        = TRUE;
        $config['file_ext_tolower'] = TRUE;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_dokumen')) {
            $this->session->set_flashdata('flash_failed', 'Format File Tidak Sesuai');
            redirect('C_Locator/layout');
        } else {
            $data = array(
                'id_skki'     => $id_skki,
                'revisi'      => $revisi,
                'folder_path' => $path,
                'file_name'   => $file_name . "." . explode('/', $_FILES['file_dokumen']['type'])[1],
                'created_by'  => $this->session->userdata('username')
            );
            $this->M_AllFunction->Insert('trn_skki_document', $data);
            redirect('C_MonitoringAnggaran');
        }
    }

    function save_skkihdr(){
        $data = array(
            "id"         => $this->input->post('id_skki', true),
            "basket_id"  => $this->input->post('basket_id', true),
            "no_skki"    => $this->input->post('no_skki', true),
            "unit"       => $this->session->userdata('unit_id'),
            "tanggal"    => $this->input->post('tanggal', true),
            "tahun"      => $this->input->post('tahun', true),
            "jenis"      => $this->input->post('jenis', true),
            "nilai_aki"  => $this->input->post('nilai_aki', true),
            "created_by" => $this->session->userdata('username')
        );
        $this->M_AllFunction->Replace("trn_skki_hdr", $data);
        redirect("C_MonitoringAnggaran");
    }

    function Dashboard(){
        $this->template->display('monitoring_anggaran/dashboard');
    }

    function DashboardPRK(){
        $this->template->display('monitoring_anggaran/dashboard_prk');
    }

    function queryDashboardPRK(){
        $tahun = $this->input->post('tahun', true);

        return $this->M_AllFunction->Where('vw_dashboard_prk', "tahun = '$tahun'");
    }

    function ajaxDashboardPrk(){
        $hasil = $this->queryDashboardPRK();

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = html_escape($h->tahun);
            $row[] = html_escape($h->status);
            $row[] = html_escape($h->basket);
            $row[] = html_escape($h->no_prk);
            $row[] = html_escape($h->uraian_prk);
            $row[] = number_format(html_escape($h->material), 0, ',', '.');
            $row[] = number_format(html_escape($h->jasa), 0, ',', '.');
            $row[] = number_format(html_escape($h->total), 0, ',', '.');
            $row[] = number_format(html_escape($h->nilai_kontrak), 0, ',', '.');
            $row[] = html_escape($h->total) == 0 ? "0%" : number_format(html_escape($h->nilai_kontrak) * 100 / html_escape($h->total), 2, ',', '.');
            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    function exportDashboardPRK(){
        $hasil = $this->queryDashboardPRK();

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
        $sheet->getStyle('G:K')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'TAHUN');
        $sheet->setCellValue('C1', 'STATUS');
        $sheet->setCellValue('D1', 'BASKET');
        $sheet->setCellValue('E1', 'NO PRK');
        $sheet->setCellValue('F1', 'URAIAN PRK');
        $sheet->setCellValue('G1', 'MATERIAL');
        $sheet->setCellValue('H1', 'JASA');
        $sheet->setCellValue('I1', 'TOTAL');
        $sheet->setCellValue('J1', 'NILAI KONTRAK');
        $sheet->setCellValue('K1', 'PROSENTASE');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);

            $sheet->setCellValue('B' . $i, html_escape($h->tahun));
            $sheet->setCellValue('C' . $i, html_escape($h->status));
            $sheet->setCellValue('D' . $i, html_escape($h->basket));
            $sheet->setCellValue('E' . $i, html_escape($h->no_prk));
            $sheet->setCellValue('F' . $i, html_escape($h->uraian_prk));
            $sheet->setCellValue('G' . $i, html_escape($h->material));
            $sheet->setCellValue('H' . $i, html_escape($h->jasa));
            $sheet->setCellValue('I' . $i, html_escape($h->total));
            $sheet->setCellValue('J' . $i, html_escape($h->nilai_kontrak));
            $sheet->setCellValue('K' . $i, html_escape($h->total) == 0 ? "0%" : html_escape($h->nilai_kontrak) * 100 / html_escape($h->total));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Dashboard_PRK_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function getDataSKKI(){
        $tahun = $this->input->post('tahun', true);
        $data['data'] = $this->M_AllFunction->CustomQuery("CALL proc_rekap_skki($tahun)");
        $this->load->view('monitoring_anggaran/data_skki', $data);
    }

    function Murni(){
        $data['no_skki'] = $this->M_AllFunction->Where("trn_skki_hdr", "basket_id = '" . $this->uri->segment(3) . "' AND jenis = 'M' AND unit = '" . $this->session->userdata('unit_id') . "' ORDER BY tahun DESC");
        $data['material'] = $this->M_AllFunction->CustomQuery("SELECT id, material, satuan FROM vw_material");
        $this->template->display('monitoring_anggaran/monitoring', $data);
    }

    function Luncuran(){
        $data['no_skki'] = $this->M_AllFunction->Where("trn_skki_hdr", "basket_id = '" . $this->uri->segment(3) . "' AND jenis = 'L' AND unit = '" . $this->session->userdata('unit_id') . "' ORDER BY tahun DESC");
        $data['material'] = $this->M_AllFunction->CustomQuery("SELECT id, material, satuan FROM vw_material");
        $this->template->display('monitoring_anggaran/monitoring', $data);
    }

    function skki_query($id_skki){
        return "WITH registered AS (
                SELECT
                    *,
                    true AS is_registered
                FROM vw_skki WHERE id_skki = '$id_skki'
            ), not_registered AS (
                SELECT
                    0 AS id_skki,
                    0 AS no_skki,
                    '-' AS jenis,
                    id_basket AS basket_id,
                    basket,
                    material_id,
                    material,
                    kategori,
                    '-' AS is_mdu,
                    satuan,
                    0 AS tahun,
                    0 AS volume_skki,
                    0 AS harga_skki,
                    0 AS total_skki,
                    SUM(volume) AS volume_kontrak,
                    SUM(total / 1.11) AS total_kontrak,
                    SUM(volume) AS selisih_skki,
                    SUM(total / 1.11) AS selisih_harga,
                    false AS is_registered
                FROM vw_kontrak_dtl
                WHERE id_skki = '$id_skki' AND material_id NOT IN (SELECT material_id FROM registered)
                GROUP BY material_id
            )
            SELECT * FROM registered
            UNION ALL
            SELECT * FROM not_registered";
    }

    function getSKKITable(){
        $id_skki = $this->input->post('id_skki', true);
        $query = "SELECT
            trn_skki_hdr.*,
            (SELECT
                CONCAT(trn_skki_document.folder_path, trn_skki_document.file_name) AS dokumen
                FROM trn_skki_document
                WHERE trn_skki_document.id_skki = '$id_skki'
                ORDER BY id DESC LIMIT 1) AS dokumen
            FROM trn_skki_hdr
            WHERE trn_skki_hdr.id = '$id_skki'";
        $data['header'] = $this->M_AllFunction->CustomQuery($query);

        $query = $this->skki_query($this->input->post('id_skki', true));
        $data['data'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('monitoring_anggaran/monitoring_table', $data);
    }

    function exportMonitoring(){
        $hasil = $this->M_AllFunction->CustomQuery($this->skki_query($this->input->post('id_skki', true)));

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

        $sheet->getStyle('A1:L1')->applyFromArray($styleHeading);
        $sheet->getStyle('A2:L2')->applyFromArray($styleHeading);
        $sheet->getStyle('F:L')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->mergeCells('A1:A2');
        $sheet->setCellValue('A1', 'NO');
        $sheet->mergeCells('B1:B2');
        $sheet->setCellValue('B1', 'NORMALISASI');
        $sheet->mergeCells('C1:C2');
        $sheet->setCellValue('C1', 'MATERIAL');
        $sheet->mergeCells('D1:D2');
        $sheet->setCellValue('D1', 'SATUAN');
        $sheet->mergeCells('E1:E2');
        $sheet->setCellValue('E1', 'JENIS');
        $sheet->mergeCells('F1:H1');
        $sheet->setCellValue('F1', 'SKKI TERBIT');
        $sheet->setCellValue('F2', 'VOLUME');
        $sheet->setCellValue('G2', 'HARGA');
        $sheet->setCellValue('H2', 'TOTAL');
        $sheet->mergeCells('I1:J2');
        $sheet->setCellValue('I1', 'KONTRAK RINCI');
        $sheet->setCellValue('I2', 'VOLUME');
        $sheet->setCellValue('J2', 'TOTAL');
        $sheet->mergeCells('K1:L1');
        $sheet->setCellValue('K1', '+/- SKKI');
        $sheet->setCellValue('K2', 'VOLUME');
        $sheet->setCellValue('L2', 'RUPIAH');

        $i = 3;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-2);
            $sheet->setCellValue('B' . $i, html_escape($h->material_id));
            $sheet->setCellValue('C' . $i, html_escape($h->material));
            $sheet->setCellValue('D' . $i, html_escape($h->satuan));
            $sheet->setCellValue('E' . $i, html_escape($h->is_mdu));
            $sheet->setCellValue('F' . $i, html_escape($h->volume_skki));
            $sheet->setCellValue('G' . $i, html_escape($h->harga_skki));
            $sheet->setCellValue('H' . $i, html_escape($h->total_skki));
            $sheet->setCellValue('I' . $i, html_escape($h->volume_kontrak));
            $sheet->setCellValue('J' . $i, html_escape($h->total_kontrak));
            $sheet->setCellValue('K' . $i, html_escape($h->selisih_skki));
            $sheet->setCellValue('L' . $i, html_escape($h->selisih_harga));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="SKKI_'.$this->input->post('id_skki', true).'_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function getMaterialSKKI(){
        $data = $this->M_AllFunction->Where("vw_skki", "id_skki = '" . $this->input->post('id_skki', true) . "' AND material_id = '" . $this->input->post('material', true) . "'");
        echo json_encode($data);
    }

    function Save(){
        $is_success = true;
        for($i = 0; $i < count($this->input->post('material', true)); $i++){
            $data = array(
                "material_id"  => $this->input->post("material", true)[$i],
                "id_skki"      => $this->input->post("id_skki", true),
                "volume"       => $this->input->post("volume", true)[$i],
                "harga_satuan" => $this->input->post("harga", true)[$i],
                "is_mdu"       => $this->input->post("is_mdu", true)[$i],
                "keterangan"   => $this->input->post("keterangan", true)[$i],
                "created_by"   => $this->session->userdata('username')
            );
            if(!$this->M_AllFunction->Replace('trn_skki', $data)){
                $is_success = false;
            }
        }

        if($is_success){
            $this->session->set_flashdata('flash_success', 'Berhasil mengupdate Anggaran.');
        } else {
            $this->session->set_flashdata('flash_failed', 'Gagal mengupdate Anggaran.');
        }

        $kembali = $this->M_AllFunction->Where("trn_skki_hdr", "id = '" . $this->input->post("id_skki", true) . "'");
        $jenis = $kembali[0]->jenis == "M" ? "Murni" : "Luncuran";
        $bakset = $kembali[0]->basket_id;
        redirect("C_MonitoringAnggaran/" . $jenis . "/" . $bakset);
    }

    function Update(){
        $data = array(
            "id_skki"      => $this->input->post("id_skki", true),
            "material_id"  => $this->input->post('material_id', true),
            "is_mdu"       => $this->input->post('is_mdu', true),
            "volume"       => $this->input->post('volume', true),
            "harga_satuan" => $this->input->post('harga', true),
            "updated_by"   => $this->session->userdata('username'),
            "updated_date" => date('Y-m-d H:i:s')
        );
        if($this->M_AllFunction->Replace('trn_skki', $data)){
            $this->session->set_flashdata('flash_success', 'Berhasil mengupdate Anggaran.');
        } else {
            $this->session->set_flashdata('flash_failed', 'Gagal mengupdate Anggaran.');
        }

        $kembali = $this->M_AllFunction->Where("trn_skki_hdr", "id = '" . $this->input->post("id_skki", true) . "'");
        $jenis = $kembali[0]->jenis == "M" ? "Murni" : "Luncuran";
        $bakset = $kembali[0]->basket_id;
        redirect("C_MonitoringAnggaran/" . $jenis . "/" . $bakset);
    }

    function hapusMaterialSKKI(){
        if($this->M_AllFunction->Delete("trn_skki", "material_id  = '" . $this->input->post('material', true) . "' AND id_skki = '" . $this->input->post('id_skki', true) . "'")){
            $this->session->set_flashdata('flash_success', 'Berhasil menghapus Material.');
        } else {
            $this->session->set_flashdata('flash_success', 'Gagal menghapus Material.');
        }
        echo "success";
    }

    function Pembayaran(){
        $data['basket'] = $this->M_AllFunction->CustomQuery('SELECT * FROM mst_basket');
        $this->template->display('monitoring_anggaran/pembayaran', $data);
    }

    function getPembayaran(){
        $where = "";
        if($this->input->post('basket', true) != "*"){
            $where .= "WHERE id_basket = '" . $this->input->post('basket', true) . "'";
        }
        if($this->input->post('status_kirim', true) != "*"){
            $where .= $where != "" ? " AND " : " WHERE ";
            $where .= "status_kirim = '" . $this->input->post('status_kirim', true) . "'";
        }
        if($this->input->post('status_bayar', true) != "*"){
            $where .= $where != "" ? " AND " : " WHERE ";
            $where .= "status_bayar = '" . $this->input->post('status_bayar', true) . "'";
        }
        if($this->input->post('filter_rencana_bayar', true) != ""){
            $where .= $where != "" ? " AND " : " WHERE ";
            $where .= "DATE_FORMAT(rencana_bayar, '%Y-%m') = '" . $this->input->post('filter_rencana_bayar', true) . "'";
        }
        if($this->input->post('filter_tanggal_bayar', true) != ""){
            $where .= $where != "" ? " AND " : " WHERE ";
            $where .= "DATE_FORMAT(tanggal_bayar, '%Y-%m') = '" . $this->input->post('filter_tanggal_bayar', true) . "'";
        }
        $query = "SELECT
                A.basket,
                A.tahun_anggaran,
                A.is_murni,
                A.no_prk,
                A.nomor_khs,
                A.no_kontrak,
                A.vendor,
                A.kategori,
                A.material,
                A.awal_kontrak,
                A.akhir_kontrak,
                A.nilai_kontrak,
                A.bae_awal,
                A.bae_akhir,
                status_kirim,
                rencana_bayar,
                tanggal_penerimaan,
                tanggal_bayar,
                CASE
                    WHEN A.tanggal_penerimaan IS NULL AND A.tanggal_bayar IS NULL THEN 0
                    WHEN A.tanggal_penerimaan IS NULL AND A.tanggal_bayar IS NOT NULL THEN 0
                    WHEN A.tanggal_penerimaan IS NOT NULL AND A.tanggal_bayar IS NULL THEN DATEDIFF(NOW(), A.tanggal_penerimaan)
                    WHEN A.tanggal_penerimaan IS NOT NULL AND A.tanggal_bayar IS NOT NULL THEN DATEDIFF(A.tanggal_bayar, A.tanggal_penerimaan)
                END AS durasi,
                status_bayar
            FROM vw_kontrak AS A
            $where
            ORDER BY A.awal_kontrak DESC";

        return $this->M_AllFunction->CustomQuery($query);
    }

    function ajaxPembayaran(){
        $hasil = $this->getPembayaran();

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
            $row[] = html_escape($h->no_prk);
            $row[] = html_escape($h->nomor_khs);
            $row[] = html_escape($h->no_kontrak);
            $row[] = html_escape($h->vendor);
            $row[] = html_escape($h->kategori);
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->awal_kontrak);
            $row[] = html_escape($h->akhir_kontrak);
            $row[] = number_format(html_escape($h->nilai_kontrak), 0, ",", ".");
            $total += html_escape($h->nilai_kontrak);
            $row[] = html_escape($h->bae_awal);
            $row[] = html_escape($h->bae_akhir);
            $row[] = html_escape($h->rencana_bayar);
            $row[] = html_escape($h->tanggal_penerimaan);
            $row[] = html_escape($h->tanggal_bayar);
            $row[] = html_escape($h->durasi);
            $total_durasi += html_escape($h->durasi);
            if(html_escape($h->status_kirim) == "BELUM KIRIM"){
                $row[] = "<span class='badge bg-danger'>" . html_escape($h->status_kirim) . "</span>";
            } else if(html_escape($h->status_kirim) == "PROSES KIRIM"){
                $row[] = "<span class='badge bg-warning'>" . html_escape($h->status_kirim) . "</span>";
            } else {
                $row[] = "<span class='badge bg-primary'>" . html_escape($h->status_kirim) . "</span>";
            }
            $row[] = html_escape($h->status_bayar) == "BELUM BAYAR" ? "<span class='badge bg-danger'>" . html_escape($h->status_bayar) . "</span>" : "<span class='badge bg-primary'>" . html_escape($h->status_bayar) . "</span>";
            if(html_escape($h->status_bayar) == "BELUM BAYAR") {
                $row[] = "<button class='btn btn-outline-secondary btn-sm' onclick=\"showUpdate('" . html_escape($h->no_kontrak) . "')\"><i class=\"fa fa-pencil\"></i></button>";
            } else {
                $row[] = "";
            }
            $data['data'][] = $row;
        }
        $data['total_kontrak'] = number_format($total, 0, ",", ".");
        $data['average'] = number_format($total_durasi / count($hasil), 2, ".", ",");

        echo json_encode($data);
    }

    function ExportPembayaran(){
        $hasil = $this->getPembayaran();

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

        $sheet->getStyle('A1:U1')->applyFromArray($styleHeading);
        $sheet->getStyle('M')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'U') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'BASKET')
            ->setCellValue('C1', 'TAHUN')
            ->setCellValue('D1', 'JENIS ANGGARAN')
            ->setCellValue('E1', 'NO PRK')
            ->setCellValue('F1', 'NO KHS')
            ->setCellValue('G1', 'NO KONTRAK')
            ->setCellValue('H1', 'VENDOR')
            ->setCellValue('I1', 'KATEGORI MATERIAL')
            ->setCellValue('J1', 'MATERIAL')
            ->setCellValue('K1', 'AWAL KONTRAK')
            ->setCellValue('L1', 'AKHIR KONTRAK')
            ->setCellValue('M1', 'NILAI KONTRAK')
            ->setCellValue('N1', 'AWAL BAE')
            ->setCellValue('O1', 'AKHIR BAE')
            ->setCellValue('P1', 'RENCANA BAYAR')
            ->setCellValue('Q1', 'TANGGAL TERIMA')
            ->setCellValue('R1', 'TANGGAL BAYAR')
            ->setCellValue('S1', 'DURASI')
            ->setCellValue('T1', 'STATUS KIRIM')
            ->setCellValue('U1', 'STATUS BAYAR');

        $i = 1;
        $row = 2;
        foreach ($hasil as $d) {
            $sheet->setCellValue('A'.$row, $i++)
                ->setCellValue('B'.$row, html_escape($d->basket))
                ->setCellValue('C'.$row, html_escape($d->tahun_anggaran))
                ->setCellValue('D'.$row, html_escape($d->is_murni) == 1 ? "MURNI" : "LUNCURAN")
                ->setCellValue('E'.$row, html_escape($d->no_prk))
                ->setCellValue('F'.$row, html_escape($d->nomor_khs))
                ->setCellValue('G'.$row, html_escape($d->no_kontrak))
                ->setCellValue('H'.$row, html_escape($d->vendor))
                ->setCellValue('I'.$row, html_escape($d->kategori))
                ->setCellValue('J'.$row, html_escape($d->material))
                ->setCellValue('K'.$row, html_escape($d->awal_kontrak))
                ->setCellValue('L'.$row, html_escape($d->akhir_kontrak))
                ->setCellValue('M'.$row, html_escape($d->nilai_kontrak))
                ->setCellValue('N'.$row, html_escape($d->bae_awal))
                ->setCellValue('O'.$row, html_escape($d->bae_akhir))
                ->setCellValue('P'.$row, html_escape($d->rencana_bayar))
                ->setCellValue('Q'.$row, html_escape($d->tanggal_penerimaan))
                ->setCellValue('R'.$row, html_escape($d->tanggal_bayar))
                ->setCellValue('S'.$row, html_escape($d->durasi))
                ->setCellValue('T'.$row, html_escape($d->status_kirim) == "BELUM KIRIM" ? "BELUM KIRIM" : (html_escape($d->status_kirim) == "PROSES KIRIM" ? "PROSES KIRIM" : "SUDAH KIRIM"))
                ->setCellValue('U'.$row, html_escape($d->status_bayar) == "BELUM BAYAR" ? "BELUM BAYAR" : "SUDAH BAYAR");
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Pembayaran_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function updatePembayaran(){
        if(isset($_POST['rencana_bayar'])){
            $data = array(
                "rencana_bayar" => $this->input->post('rencana_bayar', true)
            );
            if($this->M_AllFunction->update('trn_kontrak_hdr', $data, "no_kontrak = '" . $this->input->post('no_kontrak', true) . "'")){
                $this->session->set_flashdata('flash_succes', 'Rencana Pembayaran DiUpdate.');
            } else {
                $this->session->set_flashdata('flash_failed', 'Gagal Rencana Pembayaran.');
            }
        } else {
            $data = array(
                "tanggal_bayar" => $this->input->post('tanggal_bayar', true),
                "status_bayar"  => 1
            );
            if($this->M_AllFunction->update('trn_kontrak_hdr', $data, "no_kontrak = '" . $this->input->post('no_kontrak', true) . "'")){
                $this->session->set_flashdata('flash_succes', 'Pembayaran DiUpdate.');
            } else {
                $this->session->set_flashdata('flash_failed', 'Gagal Rencana Pembayaran.');
            }
        }
        redirect('C_MonitoringAnggaran/Pembayaran');
    }

    function ajaxGrafik(){
        $tahun = $this->input->post('tahun', true);
        $query = "WITH data AS (
                SELECT
                    MONTH(rencana_bayar) AS bulan,
                    YEAR(rencana_bayar) AS tahun,
                    SUM(nilai_kontrak) AS rencana_bayar,
                    SUM(CASE WHEN tanggal_bayar IS NOT NULL THEN nilai_kontrak ELSE 0 END) AS terbayar
                FROM vw_kontrak
                GROUP BY MONTH(rencana_bayar), YEAR(rencana_bayar)
            )
            SELECT
                A.bulan,
                A.singkatan,
                IFNULL(B.rencana_bayar, 0) AS rencana_bayar,
                IFNULL(B.terbayar, 0) AS terbayar
            FROM mst_bulan AS A
            LEFT JOIN data AS B
            ON A.bulan = B.bulan AND B.tahun = $tahun";
        $data['grafik'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('monitoring_anggaran/grafik_data', $data);
    }

    function ajaxKontrak(){
        $tahun = $this->input->post('tahun', true);
        $query = "WITH data AS (
                        SELECT
                                        MONTH(rencana_bayar) AS bulan,
                                        YEAR(rencana_bayar) AS tahun,
                                        IFNULL(COUNT(id), 0) AS rencana_bayar,
                                        SUM(CASE WHEN status_bayar = 'BELUM BAYAR' THEN 1 ELSE 0 END) AS belum_realisasi
                        FROM vw_kontrak
                        GROUP BY MONTH(rencana_bayar), YEAR(rencana_bayar)
                    )
                    SELECT
                        A.bulan,
                        A.singkatan,
                        IFNULL(B.rencana_bayar, 0) AS rencana_bayar,
                        IFNULL(B.rencana_bayar, 0) - IFNULL(B.belum_realisasi, 0) AS realisasi_bayar
                    FROM mst_bulan AS A
                    LEFT JOIN data AS B
                    ON A.bulan = B.bulan AND B.tahun = $tahun";
        $data['grafik'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('monitoring_anggaran/grafik_kontrak', $data);
    }

    function ajaxDurasi(){
        $tahun = $this->input->post('tahun', true);
        $query = "WITH data AS (
                    SELECT
                            DATE_FORMAT(rencana_bayar,'%Y-%m') AS bulan,
                            AVG(DATEDIFF(tanggal_bayar, tanggal_penerimaan)) AS durasi
                    FROM vw_kontrak
                    WHERE rencana_bayar IS NOT NULL AND YEAR(rencana_bayar) = $tahun AND tanggal_bayar IS NOT NULL AND tanggal_penerimaan IS NOT NULL
                    GROUP BY DATE_FORMAT(rencana_bayar,'%Y-%m')
                    ORDER BY DATE_FORMAT(rencana_bayar,'%Y-%m') ASC
                )

                SELECT
                    *
                FROM mst_bulan
                LEFT JOIN data
                ON CONCAT('$tahun-', mst_bulan.alt_bulan) = data.bulan
                ORDER BY mst_bulan.bulan";
        $data['grafik'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('monitoring_anggaran/grafik_durasi', $data);
    }
}