<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @property M_AllFunction $M_AllFunction
 * @property M_Permohonan $M_Permohonan
 * @property Session $session
 * @property Template $template
 */

class C_Permohonan extends CI_Controller {

	function __construct(){
		parent::__construct();

        $this->load->model(array("M_AllFunction", "M_Permohonan"));

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

	public function Permohonan(){
        $query = "SELECT * FROM vw_permohonan ";
        if(($this->session->userdata('group_id') != 1) || ($this->session->userdata('group_id') != 2)){
            $query .= "WHERE request_unit = '" . $this->session->userdata('unit_id') . "'";
        }
        $query .= " ORDER BY id DESC";
        $data['data'] = $this->M_AllFunction->CustomQuery($query);
        $data['basket'] = $this->M_AllFunction->Get('mst_basket');
        $data['material'] = $this->M_AllFunction->CustomQuery("SELECT id, material FROM vw_material");
        $data['jenis_pekerjaan'] = $this->M_AllFunction->Get('mst_jenis_pekerjaan');
        $this->template->display('permohonan/permohonan', $data);
	}

    function exportPermohonan(){
        $query = "SELECT * FROM vw_permohonan ";
        if(($this->session->userdata('group_id') != 1) || ($this->session->userdata('group_id') != 2)){
            $query .= "WHERE request_unit = '" . $this->session->userdata('unit_id') . "'";
        }
        $query .= " ORDER BY id DESC";
        $hasil = $this->M_AllFunction->CustomQuery($query);

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

        $sheet->getStyle('A1:J1')->applyFromArray($styleHeading);

        foreach(range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NO PR');
        $sheet->setCellValue('C1', 'TANGGAL PR');
        $sheet->setCellValue('D1', 'MATERIAL');
        $sheet->setCellValue('E1', 'SUMBER ANGGARAN');
        $sheet->setCellValue('F1', 'NOMOR ANGGARAN');
        $sheet->setCellValue('G1', 'PEKERJAAN');
        $sheet->setCellValue('H1', 'UNIT');
        $sheet->setCellValue('I1', 'NO STO');
        $sheet->setCellValue('J1', 'STATUS');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->no_pr));
            $sheet->setCellValue('C' . $i, html_escape($h->tanggal_pr));
            $sheet->setCellValue('D' . $i, html_escape($h->material));
            $sheet->setCellValue('E' . $i, html_escape($h->basket));
            $sheet->setCellValue('F' . $i, html_escape($h->no_anggaran));
            $sheet->setCellValue('G' . $i, html_escape($h->pekerjaan));
            $sheet->setCellValue('H' . $i, html_escape($h->unit_name));
            $sheet->setCellValue('I' . $i, html_escape($h->no_sto));
            $sheet->setCellValue('J' . $i, html_escape($h->is_sto_released) ? "STO TERBIT" : html_escape($h->status));

            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Daftar_Permohonan_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function PermohonanSave(){
        $directory = 'data_uploads/permohonan/';
        $config['allowed_types'] = 'pdf';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;
        $config['upload_path'] = $directory;

        $this->load->library('upload', $config);

        $filename = "tug-" . $this->session->userdata('unit_id') . '-' . bin2hex(random_bytes(24));
        $config['file_name'] = $filename;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_tug')) {
            $errornya = $this->upload->display_errors();
            $this->session->set_flashdata('flash_failed', 'Maaf Dokumen yang dipilih tidak sesuai format.' . $errornya);
            redirect('C_Permohonan/Permohonan');
        }

        $filename_surat = "surat-" . $this->session->userdata('unit_id') . '-' . bin2hex(random_bytes(24));
        $config['file_name'] = $filename_surat;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_surat')) {
            $filename_surat = null;
        }

        if(!isset($_POST['volume'])){
            $this->session->set_flashdata('flash_failed', 'Maaf Tidak Ada Material yang ditambahkan.');
            redirect('C_Permohonan/Permohonan');
        } else {
            $no_pr = $this->input->post('no_pr', true);
            $cek = $this->M_AllFunction->Where('trn_permohonan_hdr', "no_pr = '$no_pr'");
            $no_tlsk = $this->M_AllFunction->CustomQuery('SELECT MAX(no_tlsk) + 1 as no_tlsk FROM trn_permohonan_hdr');
            if(count($cek) == 0) {
                $data = array(
                    "no_pr"             => $this->input->post('no_pr', true),
                    "tanggal_pr"        => $this->input->post('tanggal_pr', true),
                    "no_tlsk"           => $no_tlsk[0]->no_tlsk,
                    "basket_id"         => $this->input->post('basket_id', true),
                    "jenis_anggaran"    => $this->input->post('jenis_anggaran', true),
                    "no_anggaran"       => $this->input->post('no_anggaran', true),
                    "pekerjaan"         => $this->input->post('pekerjaan', true),
                    "request_unit"      => $this->session->userdata("unit_id"),
                    "status"            => "PERMOHONAN",
                    "file_surat"        => $filename_surat,
                    "file_tug"          => $filename,
                    "file_tug_location" => $directory,
                    "created_by"        => $this->session->userdata('username'),
                    "created_date"      => date('Y-m-d H:i:s')
                );

                $this->M_AllFunction->Insert('trn_permohonan_hdr', $data);

                for($i = 0; $i < count($this->input->post('volume', true)); $i++){
                    $detail = array(
                        'no_pr'    => $this->input->post('no_pr', true),
                        'material' => $this->input->post('material', true)[$i],
                        'volume'   => $this->input->post('volume', true)[$i],
                    );
                    $this->M_AllFunction->Insert('trn_permohonan_dtl', $detail);
                }
                $this->sendPermohonan($this->input->post('no_pr', true));
                $this->session->set_flashdata('flash_succes', 'Permohonan telah dibuat.');
                redirect('C_Permohonan/Permohonan');
            } else {
                $this->session->set_flashdata('flash_failed', 'Maaf Nomor PR Sudah DiGunakan.');
                redirect('C_Permohonan/Permohonan');
            }
        }
    }

    public function update_surat(){
        $filename_surat = "surat-" . $this->session->userdata('unit_id') . '-' . bin2hex(random_bytes(24));
        $directory = 'data_uploads/permohonan/';
        $config['allowed_types'] = 'pdf';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;
        $config['file_name'] = $filename_surat;
        $config['upload_path'] = $directory;
        echo '<pre>'; print_r($config); echo '</pre>';
        die();

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_surat')) {
            $errornya = $this->upload->display_errors();
            $this->session->set_flashdata('flash_failed', 'Maaf Dokumen yang dipilih tidak sesuai format.' . $errornya);
            redirect('C_Permohonan/Permohonan');
        }

        $data = array(
            "file_surat"        => $filename_surat
        );

        $this->M_AllFunction->Update('trn_permohonan_hdr', $data, "no_pr = '" . $this->input->post('no_pr', true) . "'");
        redirect('C_Permohonan/Permohonan');
    }

	public function getMaterialPermohonan(){
        $no_pr = $this->input->post('no_pr', true);
        $query = "SELECT
                        B.kategori, B.material, A.volume, A.approved_volume
                    FROM trn_permohonan_dtl AS A
                    JOIN (SELECT id, kategori, material FROM vw_material GROUP BY id) AS B
                    ON A.material = B.id
                    WHERE A.no_pr = $no_pr
                    ORDER BY B.kategori, B.id";
        $hasil = $this->M_AllFunction->CustomQuery($query);

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->kategori);
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->volume);
            $row[] = html_escape($h->approved_volume);
            $data['data'][] = $row;
        }
        $data['query'] = $query;

        echo json_encode($data);
	}

    function sendPermohonan($no_pr){
        // $data = $this->M_AllFunction->Where('vw_permohonan', "no_pr = '$no_pr'");
        // $title = "Telah Di Ajukan Permintaan Baru";
        // $message =
        // "<table>
        //     <tr>
        //         <td>NO PR</td>
        //         <td>:</td>
        //         <td>" . strtoupper($data[0]->no_pr) . "</td>
        //     </tr>
        //     <tr>
        //         <td>TANGGAL PR</td>
        //         <td>:</td>
        //         <td>" . strtoupper($data[0]->tanggal_pr) . "</td>
        //     </tr>
        //     <tr>
        //         <td>SUMBER ANGGARAN</td>
        //         <td>:</td>
        //         <td>" . strtoupper($data[0]->basket) . "</td>
        //     </tr>
        //     <tr>
        //         <td>NOMOR ANGGARAN</td>
        //         <td>:</td>
        //         <td>" . strtoupper($data[0]->no_anggaran) . "</td>
        //     </tr>
        //     <tr>
        //         <td>PEKERJAAN</td>
        //         <td>:</td>
        //         <td>" . strtoupper($data[0]->pekerjaan) . "</td>
        //     </tr>
        //     <tr>
        //         <td>UP3</td>
        //         <td>:</td>
        //         <td>" . strtoupper($data[0]->unit) . "</td>
        //     </tr>
        //     <tr>
        //         <td>STATUS</td>
        //         <td>:</td>
        //         <td>" . strtoupper($data[0]->status) . "</td>
        //     </tr>
        // </table>
        // <br/>
        // <a href='" . base_url() . "' style='background-color: blue; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;'>Buka Website</a>";
        // if($basket == '4'){
        //     //jika basket SKKO kirimkan email untuk jabatan user MSB Rensisdis
        //     $receiver = $this->M_AllFunction->CustomQuery("SELECT email FROM mst_user WHERE jabatan_id = 6");
        // } else {
        //     //jika basket selain SKKO kirimkan email untuk jabatan user MSB Logistik
        //     $receiver = $this->M_AllFunction->CustomQuery("SELECT email FROM mst_user WHERE jabatan_id = 5");
        // }
        // if(count($receiver) == 0){
        //     $this->session->set_flashdata('flash_failed', 'Maaf Tidak Ada Penerima Email untuk di Kirimkan.');
        // }
        // $this->sendemail->send($title, $message, $receiver);
    }

	public function DaftarPermohonan(){
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $data['basket'] = $this->M_AllFunction->Get('mst_basket');
        $this->template->display('permohonan/daftar_permohonan', $data);
    }

    function AjaxDaftarPermohonan() {
        $data['draw'] = $this->input->post('draw', true);
        $length = $this->input->post('length', true);
        $start = $this->input->post('start', true);

        $data['data'] = array();

        $hasil = $this->M_Permohonan->getData();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();
            $row[] = $i++;
            $row[] = html_escape($h->unit_name);
            $row[] = html_escape($h->no_pr);
            $row[] = html_escape($h->tanggal_pr);
            $row[] = html_escape($h->basket);
            $row[] = html_escape($h->no_anggaran);
            $row[] = "<button type='button' class='btn btn-sm btn-secondary' onclick=\"showMaterial(" . html_escape($h->no_pr) . ")\">MATERIAL</button>";
            $row[] = html_escape($h->pekerjaan);
            $row[] = html_escape($h->is_sto_released) ? "STO TERBIT" : html_escape($h->status);
            $file = "";
            if (!empty(html_escape($h->file_tug))) {
                $file .= "<a href='" . base_url() . html_escape($h->file_tug_location) . '/' . html_escape($h->file_tug) . ".pdf' class='btn btn-text-danger btn-hover-light-danger btn-sm' target='_blank'><i class='fa fa-file-pdf'></i> TUG</a>";
            } else {
                $file .= "";
            }
            if (!empty(html_escape($h->file_surat))) {
                $file .= "<a href='" . base_url() . html_escape($h->file_tug_location) . '/' . html_escape($h->file_surat) . ".pdf' class='btn btn-text-danger btn-hover-light-danger btn-sm' target='_blank'><i class='fa fa-file-pdf'></i> SURAT</a>";
            } else {
                $file .= "";
            }
            $row[] = $file;
            if(html_escape($h->status) == "PERMOHONAN" && $this->session->userdata('edit')){
                $row[] = "<button type='button' class='btn btn-outline-secondary btn-sm waves-effect waves-light' onclick=\"verifikasiPermohonan(" . html_escape($h->no_pr) . ")\"><i class=\"fa fa-pencil\"></i></button>";
            } else {
                $row[] = "";
            }
            $data['data'][] = $row;
        }

        $data['recordsTotal'] = $this->M_Permohonan->getTotal();
        $data['recordsFiltered'] = $this->M_Permohonan->getTotalFiltered();
        echo json_encode($data);
    }

    function exportDaftarPermohonan(){
        $hasil = $this->M_Permohonan->exportData();

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

        $sheet->getStyle('A1:H1')->applyFromArray($styleHeading);

        foreach(range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'UNIT');
        $sheet->setCellValue('C1', 'NO PR');
        $sheet->setCellValue('D1', 'TANGGAL PR');
        $sheet->setCellValue('E1', 'ANGGARAN');
        $sheet->setCellValue('F1', 'NO ANGGARAN');
        $sheet->setCellValue('G1', 'PEKERJAAN');
        $sheet->setCellValue('H1', 'STATUS');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->unit_name));
            $sheet->setCellValue('C' . $i, html_escape($h->no_pr));
            $sheet->setCellValue('D' . $i, html_escape($h->tanggal_pr));
            $sheet->setCellValue('E' . $i, html_escape($h->basket));
            $sheet->setCellValue('F' . $i, html_escape($h->no_anggaran));
            $sheet->setCellValue('G' . $i, html_escape($h->pekerjaan));
            $sheet->setCellValue('H' . $i, html_escape($h->is_sto_released) ? "STO TERBIT" : html_escape($h->status));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Daftar_Permohonan_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

	public function verifikasiPermohonan(){
        $no_pr = $this->input->post('no_pr', true);
        $query = "SELECT
                        B.id, B.kategori, B.material, A.volume
                    FROM trn_permohonan_dtl AS A
                    JOIN (SELECT id, kategori, material FROM vw_material GROUP BY id) AS B
                    ON A.material = B.id
                    WHERE A.no_pr = $no_pr
                    ORDER BY B.kategori, B.id";$hasil = $this->M_AllFunction->CustomQuery($query);

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = "<input name='material_id[]' type='number' class='form-control' value='" . html_escape($h->id) . "' readonly />";
            $row[] = html_escape($h->kategori);
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->volume);
            $row[] = "<input name='volume[]' type='number' class='form-control' max='" . html_escape($h->volume) . "' value='" . html_escape($h->volume) . "'/>";
            $data['data'][] = $row;
        }

        echo json_encode($data);
	}

    function approvalPermohonan(){
        $no_pr = $this->input->post('no_pr', true);
        $data = array(
            "status"             => $this->input->post('status', true),
            "approval_by"        => $this->session->userdata('username'),
            "approval_date"      => date('Y-m-d H:i:s'),
            "approval_comment"   => $this->input->post('approval_comment', true),
            "additional_comment" => $this->input->post('additional_comment', true),
        );

        if(isset($_POST['material_id'])){
            for($i = 0; $i < count($this->input->post('material_id', true)); $i++){
                $detail = array(
                    'volume'   => $this->input->post('volume', true)[$i],
                );
                $this->M_AllFunction->Update('trn_permohonan_dtl', $detail, "no_pr = '$no_pr' AND material = '" . $this->input->post('material_id', true)[$i] . "'");
            }
        }

        if($this->M_AllFunction->Update('trn_permohonan_hdr', $data, "no_pr = '$no_pr'")){
            $this->session->set_flashdata('flash_success', 'Berhasil melakukan Approval.');
        } else {
            $this->session->set_flashdata('flash_failed', 'Gagal melakukan Approval.');
        }
        redirect('C_Permohonan/DaftarPermohonan');
    }

    function DashboardSTO(){
        $data['basket'] = $this->M_AllFunction->CustomQuery('SELECT * FROM mst_basket');
        $data['skki'] = $this->M_AllFunction->CustomQuery('SELECT * FROM trn_skki_hdr');
        $this->template->display('permohonan/dashboard_sto', $data);
    }

    function queryDashboardSTO(){
        $where = "";
        if($this->input->post('basket', true) != "*"){
            $where = " AND basket_id = '" . $this->input->post('basket', true) . "'";
        }
        if($this->input->post('jenis_anggaran', true) != "*"){
            $where .= " AND jenis_anggaran = '" . $this->input->post('jenis_anggaran', true) . "'";
        }
        if($this->input->post('tahun', true) != "*"){
            $where .= " AND YEAR(sto_date) = '" . $this->input->post('tahun', true) . "'";
        }
        $query = "WITH data AS (
                SELECT
                    mst_unit.id,
                    mst_unit.singkatan,
                    vw_material.id AS normalisasi,
                    vw_material.kategori AS kategori,
                    vw_material.material,
                    vw_material.satuan,
                    SUM(trn_permohonan_dtl.approved_volume) AS volume
                FROM trn_permohonan_hdr
                JOIN trn_permohonan_dtl
                ON trn_permohonan_hdr.no_pr = trn_permohonan_dtl.no_pr
                    AND trn_permohonan_hdr.no_sto = trn_permohonan_dtl.no_sto
                JOIN vw_material
                ON trn_permohonan_dtl.material = vw_material.id
                JOIN mst_unit
                ON trn_permohonan_hdr.request_unit = mst_unit.id
                WHERE trn_permohonan_hdr.is_sto_released = 1
                $where
                GROUP BY
                    mst_unit.singkatan,
                    vw_material.id
                ORDER BY
                    mst_unit.singkatan,
                    vw_material.id
            )
            SELECT
                normalisasi,
                material,
                kategori,
                satuan,
                SUM( CASE WHEN singkatan = 'BDG' THEN volume ELSE 0 END ) AS BDG,
                SUM( CASE WHEN singkatan = 'BLG' THEN volume ELSE 0 END ) AS BLG,
                SUM( CASE WHEN singkatan = 'BTR' THEN volume ELSE 0 END ) AS BTR,
                SUM( CASE WHEN singkatan = 'CKG' THEN volume ELSE 0 END ) AS CKG,
                SUM( CASE WHEN singkatan = 'CPP' THEN volume ELSE 0 END ) AS CPP,
                SUM( CASE WHEN singkatan = 'CPT' THEN volume ELSE 0 END ) AS CPT,
                SUM( CASE WHEN singkatan = 'CRC' THEN volume ELSE 0 END ) AS CRC,
                SUM( CASE WHEN singkatan = 'JTN' THEN volume ELSE 0 END ) AS JTN,
                SUM( CASE WHEN singkatan = 'KBJ' THEN volume ELSE 0 END ) AS KBJ,
                SUM( CASE WHEN singkatan = 'KJT' THEN volume ELSE 0 END ) AS KJT,
                SUM( CASE WHEN singkatan = 'LTA' THEN volume ELSE 0 END ) AS LTA,
                SUM( CASE WHEN singkatan = 'MRD' THEN volume ELSE 0 END ) AS MRD,
                SUM( CASE WHEN singkatan = 'MTG' THEN volume ELSE 0 END ) AS MTG,
                SUM( CASE WHEN singkatan = 'PDG' THEN volume ELSE 0 END ) AS PDG,
                SUM( CASE WHEN singkatan = 'PDK' THEN volume ELSE 0 END ) AS PDK,
                SUM( CASE WHEN singkatan = 'TJP' THEN volume ELSE 0 END ) AS TJP,
                SUM( CASE WHEN singkatan = 'UID' THEN volume ELSE 0 END ) AS UID,
                SUM( CASE WHEN singkatan = 'UP2D' THEN volume ELSE 0 END ) AS UP2D
            FROM
                data
            GROUP BY
                normalisasi,
                material,
                kategori,
                satuan";

        return $this->M_AllFunction->CustomQuery($query);
    }

    function ajaxDashboardSTO(){
        $hasil = $this->queryDashboardSTO();

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

			$row[] = $i++;
			$row[] = html_escape($h->normalisasi);
			$row[] = html_escape($h->kategori);
			$row[] = html_escape($h->material);
			$row[] = html_escape($h->satuan);
            $total = html_escape($h->BDG) + html_escape($h->BLG) + html_escape($h->BTR) + html_escape($h->CKG) + html_escape($h->CPP) + html_escape($h->CPT) + html_escape($h->CRC) + html_escape($h->JTN) + html_escape($h->KBJ) + html_escape($h->KJT) + html_escape($h->LTA) + html_escape($h->MRD) + html_escape($h->MTG) + html_escape($h->PDG) + html_escape($h->PDK) + html_escape($h->TJP) + html_escape($h->UID) + html_escape($h->UP2D);
            $row[] = number_format($total, 0, ",", ".");
            $row[] = html_escape($h->UID) == 0 ? "" : number_format(html_escape($h->UID), 0, ",", ".");
            $row[] = html_escape($h->BDG) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"BDG\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->BDG), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->BLG) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"BLG\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->BLG), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->BTR) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"BTR\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->BTR), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->CKG) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"CKG\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->CKG), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->CPP) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"CPP\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->CPP), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->CPT) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"CPT\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->CPT), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->CRC) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"CRC\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->CRC), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->JTN) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"JTN\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->JTN), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->KBJ) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"KBJ\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->KBJ), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->KJT) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"KJT\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->KJT), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->LTA) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"LTA\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->LTA), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->MRD) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"MRD\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->MRD), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->MTG) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"MTG\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->MTG), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->PDG) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"PDG\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->PDG), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->PDK) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"PDK\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->PDK), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->TJP) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"TJP\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->TJP), 0, ",", ".") . "</a>";
            $row[] = html_escape($h->UP2D) == 0 ? "" : "<a style='color: blue; cursor: pointer;' onclick='showDetail(\"UP2D\", \"" . html_escape($h->normalisasi) . "\")'>" . number_format(html_escape($h->UP2D), 0, ",", ".") . "</a>";
            $data['data'][] = $row;
        }

		echo json_encode($data);
    }

    function exportDashboardSTO(){
        $hasil = $this->queryDashboardSTO();

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

        $sheet->getStyle('A1:X1')->applyFromArray($styleHeading);
        $sheet->getStyle('F:X')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'X') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NORMALISASI');
        $sheet->setCellValue('C1', 'KATEGORI');
        $sheet->setCellValue('D1', 'MATERIAL');
        $sheet->setCellValue('E1', 'SATUAN');
        $sheet->setCellValue('F1', 'TOTAL');
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
            $total = html_escape($h->BDG) + html_escape($h->BLG) + html_escape($h->BTR) + html_escape($h->CKG) + html_escape($h->CPP) + html_escape($h->CPT) + html_escape($h->CRC) + html_escape($h->JTN) + html_escape($h->KBJ) + html_escape($h->KJT) + html_escape($h->LTA) + html_escape($h->MRD) + html_escape($h->MTG) + html_escape($h->PDG) + html_escape($h->PDK) + html_escape($h->TJP) + html_escape($h->UID) + html_escape($h->UP2D);

            $sheet->setCellValue('B' . $i , html_escape($h->normalisasi));
			$sheet->setCellValue('C' . $i , html_escape($h->kategori));
			$sheet->setCellValue('D' . $i , html_escape($h->material));
			$sheet->setCellValue('E' . $i , html_escape($h->satuan));
            $sheet->setCellValue('F' . $i , $total);
            $sheet->setCellValue('G' . $i , html_escape($h->UID) == 0 ? "" : html_escape($h->UID));
            $sheet->setCellValue('H' . $i , html_escape($h->BDG) == 0 ? "" : html_escape($h->BDG));
            $sheet->setCellValue('I' . $i , html_escape($h->BLG) == 0 ? "" : html_escape($h->BLG));
            $sheet->setCellValue('J' . $i , html_escape($h->BTR) == 0 ? "" : html_escape($h->BTR));
            $sheet->setCellValue('K' . $i , html_escape($h->CKG) == 0 ? "" : html_escape($h->CKG));
            $sheet->setCellValue('L' . $i , html_escape($h->CPP) == 0 ? "" : html_escape($h->CPP));
            $sheet->setCellValue('M' . $i , html_escape($h->CPT) == 0 ? "" : html_escape($h->CPT));
            $sheet->setCellValue('N' . $i , html_escape($h->CRC) == 0 ? "" : html_escape($h->CRC));
            $sheet->setCellValue('O' . $i , html_escape($h->JTN) == 0 ? "" : html_escape($h->JTN));
            $sheet->setCellValue('P' . $i , html_escape($h->KBJ) == 0 ? "" : html_escape($h->KBJ));
            $sheet->setCellValue('Q' . $i , html_escape($h->KJT) == 0 ? "" : html_escape($h->KJT));
            $sheet->setCellValue('R' . $i , html_escape($h->LTA) == 0 ? "" : html_escape($h->LTA));
            $sheet->setCellValue('S' . $i , html_escape($h->MRD) == 0 ? "" : html_escape($h->MRD));
            $sheet->setCellValue('T' . $i , html_escape($h->MTG) == 0 ? "" : html_escape($h->MTG));
            $sheet->setCellValue('U' . $i , html_escape($h->PDG) == 0 ? "" : html_escape($h->PDG));
            $sheet->setCellValue('V' . $i , html_escape($h->PDK) == 0 ? "" : html_escape($h->PDK));
            $sheet->setCellValue('W' . $i , html_escape($h->TJP) == 0 ? "" : html_escape($h->TJP));
            $sheet->setCellValue('X' . $i , html_escape($h->UP2D) == 0 ? "" : html_escape($h->UP2D));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Dashboard_STO_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function getDetailDashboardSTO(){
        $basket = $this->input->post('basket', true) == "*" ? "" : "AND mst_basket.id = '" . $this->input->post('basket', true) . "'";
        $jenis_anggaran = $this->input->post('jenis_anggaran', true) == "*" ? "" : "AND trn_permohonan_hdr.jenis_anggaran = '" . $this->input->post('jenis_anggaran', true) . "'";
        $query = "SELECT
                    mst_unit.id,
                    mst_unit.singkatan,
                    vw_material.id AS normalisasi,
                    vw_material.kategori,
                    vw_material.material,
                    vw_material.satuan,
                    trn_permohonan_hdr.no_pr,
                    tanggal_pr,
                    no_anggaran,
                    mst_basket.basket,
                    approved_volume
                FROM trn_permohonan_hdr
                JOIN trn_permohonan_dtl
                ON trn_permohonan_hdr.no_pr = trn_permohonan_dtl.no_pr
                    AND trn_permohonan_hdr.no_sto = trn_permohonan_dtl.no_sto
                JOIN mst_unit
                ON trn_permohonan_hdr.request_unit = mst_unit.id
                JOIN vw_material
                ON trn_permohonan_dtl.material = vw_material.id
                JOIN mst_basket
                ON trn_permohonan_hdr.basket_id = mst_basket.id
                WHERE trn_permohonan_hdr.is_sto_released = 1
                AND vw_material.id = '" . $this->input->post('normalisasi', true) . "'
                AND mst_unit.singkatan = '" . $this->input->post('unit', true) . "'
                AND YEAR(trn_permohonan_hdr.sto_date) = " . $this->input->post('tahun', true) . "
                $basket
                $jenis_anggaran";
        $data['data'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('permohonan/dashboard_sto_detail', $data);
    }

    function STO(){
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $data['basket'] = $this->M_AllFunction->Get('mst_basket');
        $this->template->display('permohonan/penerbitan_sto', $data);
    }

    function AjaxSTO() {
        $data['data'] = array();

        $hasil = $this->M_Permohonan->getDataSTO();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();
            $row[] = $i++;
            $row[] = html_escape($h->unit_name);
            $row[] = html_escape($h->no_tlsk);
            $row[] = html_escape($h->no_pr);
            $row[] = html_escape($h->tanggal_pr);
            $row[] = html_escape($h->basket);
            $row[] = html_escape($h->jenis_anggaran);
            $row[] = html_escape($h->no_anggaran);
            $row[] = "<button type='button' class='btn btn-secondary btn-sm w-100' onclick='showMaterial(" . html_escape($h->no_pr) . ")'>" . html_escape($h->material) . "</button>";
            $row[] = html_escape($h->is_sto_released) ? "STO TERBIT" : html_escape($h->status);
            $row[] = html_escape($h->no_sto);
            $row[] = html_escape($h->tanggal_sto);
            if (!empty(html_escape($h->file_tug))) {
                $row[] = "<a href='" . base_url() . html_escape($h->file_tug_location) . '/' . html_escape($h->file_tug) . ".pdf' class='btn btn-text-danger btn-hover-light-danger btn-sm' target='_blank'><i class='fa fa-file-pdf'></i> PDF</a>";
            } else {
                $row[] = "";
            }
            if(html_escape($h->is_sto_released)) {
                $row[] = "";
            } else {
                $row[] = "<button type='button' class='btn btn-outline-secondary btn-sm' onclick='showPenerbitanSTO(" . html_escape($h->no_pr) . ")'><i class=\"fa fa-pencil\"></i></button>";
            }

            $data['data'][] = $row;
        }

        $data['draw'] = $this->input->post('draw', true);
        $data['length'] = $this->input->post('length', true);
        $data['start'] = $this->input->post('start', true);
        $data['recordsTotal'] = $this->M_Permohonan->getTotalSTO();
        $data['recordsFiltered'] = $this->M_Permohonan->getTotalFilteredSTO();
        echo json_encode($data);
    }

    function exportSTO(){
        $hasil = $this->M_Permohonan->exportDataSTO();

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

        foreach(range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'UNIT');
        $sheet->setCellValue('C1', 'NO TLSK');
        $sheet->setCellValue('D1', 'NO PR');
        $sheet->setCellValue('E1', 'TANGGAL PR');
        $sheet->setCellValue('F1', 'ANGGARAN');
        $sheet->setCellValue('G1', 'JENIS');
        $sheet->setCellValue('H1', 'NO ANGGARAN');
        $sheet->setCellValue('I1', 'MATERIAL');
        $sheet->setCellValue('J1', 'STATUS');
        $sheet->setCellValue('K1', 'NO STO');
        $sheet->setCellValue('L1', 'TANGGAL STO');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->unit_name));
            $sheet->setCellValue('C' . $i, html_escape($h->no_tlsk));
            $sheet->setCellValue('D' . $i, html_escape($h->no_pr));
            $sheet->setCellValue('E' . $i, html_escape($h->tanggal_pr));
            $sheet->setCellValue('F' . $i, html_escape($h->basket));
            $sheet->setCellValue('G' . $i, html_escape($h->jenis_anggaran));
            $sheet->setCellValue('H' . $i, html_escape($h->no_anggaran));
            $sheet->setCellValue('I' . $i, html_escape($h->material));
            $sheet->setCellValue('J' . $i, html_escape($h->is_sto_released) ? "STO TERBIT" : html_escape($h->status));
            $sheet->setCellValue('K' . $i, html_escape($h->no_sto));
            $sheet->setCellValue('L' . $i, html_escape($h->tanggal_sto));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Daftar_STO_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function getDetailSTO(){
        $no_pr = $this->input->post('no_pr', true);
        $data['detail'] = $this->M_AllFunction->CustomQuery("SELECT * FROM vw_permohonan WHERE no_pr = '$no_pr'");

        $query = "SELECT
                    vw_material.id,
                    vw_material.material,
                    (vw_stock_material.stock_uid) AS stock,
                    (vw_stock_material.stock_up3) AS stock_unit,
                    trn_permohonan_dtl.volume,
                    trn_permohonan_dtl.approved_volume
                FROM trn_permohonan_hdr
                LEFT JOIN trn_permohonan_dtl
                ON trn_permohonan_hdr.no_pr = trn_permohonan_dtl.no_pr
                LEFT JOIN vw_material
                ON trn_permohonan_dtl.material = vw_material.id
                LEFT JOIN vw_stock_material
                ON vw_stock_material.id = vw_material.id
                WHERE trn_permohonan_hdr.no_pr = '$no_pr'";

        $hasil = $this->M_AllFunction->CustomQuery($query);

        foreach ($hasil as $h) {
            $row = array();
            $row[] = "<input name='material_id[]' type='number' class='form-control' value='" . html_escape($h->id) . "' readonly/>";
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->stock);
            $row[] = html_escape($h->stock_unit);
            $row[] = html_escape($h->volume);
            $row[] = "<input name='approved_volume[]' type='number' class='form-control' value='" . html_escape($h->volume) . "'/>";

            $data['data'][] = $row;
        }

        echo json_encode($data);
    }

    function SaveSTO(){
        $no_pr = $this->input->post('modal_no_pr', true);
        $data = array(
            "no_sto"          => $this->input->post('no_sto', true),
            "tanggal_sto"     => $this->input->post('tanggal_sto', true),
            "is_sto_released" => 1,
            "sto_by"          => $this->session->userdata('username'),
            "sto_date"        => date('Y-m-d H:i:s')
        );

        $this->M_AllFunction->Update('trn_permohonan_hdr', $data, "no_pr = '$no_pr'");

        for($i = 0; $i < count($this->input->post('material_id', true)); $i++){
            $detail = array(
                "no_sto"          => $this->input->post('no_sto', true),
                'approved_volume' => $this->input->post('approved_volume', true)[$i],
            );
            $this->M_AllFunction->Update('trn_permohonan_dtl', $detail, "no_pr = '" . $no_pr . "' AND material = '" . $this->input->post('material_id', true)[$i] . "'");
        }
        $this->sendPenerbitanSTO($no_pr);
        redirect('C_Permohonan/STO');
    }

    function sendPenerbitanSTO($no_pr){
        $data = $this->M_AllFunction->Where('vw_permohonan', "no_pr = '$no_pr'");

        $query = "SELECT
                    vw_material.kategori,
                    vw_material.material,
                    vw_material.satuan,
                    trn_permohonan_dtl.volume,
                    trn_permohonan_dtl.approved_volume
                FROM trn_permohonan_dtl
                JOIN vw_material
                ON trn_permohonan_dtl.material = vw_material.id
                WHERE trn_permohonan_dtl.no_pr = $no_pr
                ORDER BY vw_material.kategori, vw_material.id";

        $detail = $this->M_AllFunction->CustomQuery($query);

        $detail_row = "";
        $no = 1;

        foreach ($detail as $d) {
            $detail_row .= "<tr>
                                <td style='border: 1px solid black; border-collapse: collapse;'>" . $no++ . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse;'>" . html_escape($d->kategori) . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse;'>" . html_escape($d->material) . "</td>
                                <td style='border: 1px solid black; border-collapse: collapse;'>" . html_escape($d->satuan) . "</td>
                                <td style='text-align: right; border: 1px solid black; border-collapse: collapse;'>" . html_escape(number_format($d->volume, '0', ',', '.')) . "</td>
                                <td style='text-align: right; border: 1px solid black; border-collapse: collapse;'>" . html_escape(number_format($d->approved_volume, '0', ',', '.')) . "</td>
                            </tr>";
        }

        $title = "Penerbitan Stock Transfer Order (STO) - " . $no_pr;
        $message =
        "Yth. Admin " . strtoupper($data[0]->unit_name) . "<br/><br/>
        Berikut disampaikan penerbitan Stock Transfer Order (STO) dengan data sebagai berikut :
        <br/><br/>
        <table>
            <tr>
                <td>NO PR</td>
                <td>:</td>
                <td>" . strtoupper($data[0]->no_pr) . "</td>
            </tr>
            <tr>
                <td>TANGGAL PR</td>
                <td>:</td>
                <td>" . strtoupper($data[0]->tanggal_pr) . "</td>
            </tr>
            <tr>
                <td>NO STO</td>
                <td>:</td>
                <td>" . strtoupper($data[0]->no_sto) . "</td>
            </tr>
            <tr>
                <td>TANGGAL STO</td>
                <td>:</td>
                <td>" . strtoupper($data[0]->tanggal_sto) . "</td>
            </tr>
        </table>
        <br/><br/>
        <table style='border: 1px solid black; border-collapse: collapse;' width='55%' cellpadding='5'>
            <tr style='background-color: #008B8B; border: 1px solid black; border-collapse: collapse;'>
                <th style='text-align: center; color:white; border: 1px solid black; border-collapse: collapse;'> NO </th>
                <th style='text-align: center; color:white; border: 1px solid black; border-collapse: collapse;'> KATEGORI </th>
                <th style='text-align: center; color:white; border: 1px solid black; border-collapse: collapse;'> MATERIAL </th>
                <th style='text-align: center; color:white; border: 1px solid black; border-collapse: collapse;'> SATUAN </th>
                <th style='text-align: center; color:white; border: 1px solid black; border-collapse: collapse;'> VOLUME PERMINTAAN </th>
                <th style='text-align: center; color:white; border: 1px solid black; border-collapse: collapse;'> VOLUME DISETUJUI </th>
            </tr>" . $detail_row
        . "</table>
        <br/>
        Silahkan klik tombol dibawah ini untuk masuk ke e-logistics<br/>
        <a href='" . base_url() . "'
            style='display: block; width: 115px; height: 18px; background: #008B8B; padding: 8px; text-decoration: none; text-align: center; border-radius: 5px; color: white; font-weight: bold; line-height: 18px;'>
        LOGIN</a><br/>
        Demikian pemberitahuan ini kami sampaikan. Atas perhatiannya kami ucapkan terima kasih<br/>
        --<br/>
        Salam<br/>
        <b>Bidang Logistik PLN UID Jakarta Raya</b>";

        $receiver = $this->M_AllFunction->CustomQuery("SELECT email FROM mst_user WHERE username COLLATE utf8mb4_unicode_ci = (
                SELECT
                    created_by
                FROM trn_permohonan_hdr
                WHERE no_pr = '$no_pr'
            ) COLLATE utf8mb4_unicode_ci");
        // $receiver = $this->M_AllFunction->CustomQuery("SELECT email FROM mst_user WHERE username = 'administrator'");
        if(count($receiver) > 0){
            $this->sendemail->send($title, $message, $receiver);
        }
    }
}