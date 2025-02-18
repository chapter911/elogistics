<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @property M_AllFunction $M_AllFunction
 * @property M_Pemasaran $M_Pemasaran
 * @property Session $session
 * @property Template $template
 */

class C_Pemasaran extends CI_Controller {

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

	public function index(){
        $data['unit'] = $this->M_AllFunction->Get('mst_unit');
        $data['material'] = $this->M_AllFunction->CustomQuery("SELECT id, material, satuan FROM vw_material");
        $data['tarif'] = $this->M_AllFunction->Get("mst_tarif");
        $data['daya'] = $this->M_AllFunction->Get("mst_daya");
        $this->template->display('pemasaran/index', $data);
	}

    function cekUsingRatio(){
        $material_id = $this->input->post('material_id', true);
        echo count($this->M_AllFunction->Where('vw_material', "id = $material_id AND is_using_ratio = 1")) > 0 ? "1" : "0";
    }

    function queryIndex(){
        $where = "";
        if($this->input->post('unit', true) != "*"){
            $where .= "WHERE unit = '" . $this->input->post('unit', true) . "'";
        }
        if($this->input->post('status', true) != "*"){
            $where .= $where == "" ? " WHERE " : " AND ";
            $where .= " is_menyala = '" . $this->input->post('status', true) . "'";
        }

        $query = "SELECT * FROM vw_trn_pemasaran_hdr $where";

        return $this->M_AllFunction->CustomQuery($query);
    }

    public function ajaxIndex(){
        $hasil = $this->queryIndex();

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->name);
            $row[] = strtoupper(html_escape($h->pelanggan));
            $row[] = strtoupper(html_escape($h->layanan));
            $row[] = html_escape($h->pengukuran) == 1 ? "LANGSUNG" : "TIDAK LANGSUNG";
            $row[] = "T" .strtoupper(substr(html_escape($h->segment_tegangan), 0, 1));
            $row[] = strtoupper(html_escape($h->pekerjaan));
            $row[] = strtoupper(html_escape($h->tarif));
            $row[] = html_escape($h->daya_lama) == null ? "" : number_format(html_escape($h->daya_lama), 0, ',', '.');
            $row[] = number_format(html_escape($h->daya), 0, ',', '.');
            $row[] = html_escape($h->tanggal_permohonan);
            $row[] = html_escape($h->tanggal_sip) == "0000-00-00" ? "BELUM BAYAR" : html_escape($h->tanggal_sip);
            $row[] = html_escape($h->rencana_nyala);
            $row[] = html_escape($h->tanggal_nyala);
            if(empty(html_escape($h->tanggal_nyala))){
                $row[] = html_escape($h->tanggal_sip) == "0000-00-00" ? "0" : date_diff(new DateTime(), new DateTime(html_escape($h->tanggal_sip)))->format('%a');
            } else {
                $row[] = date_diff(new DateTime(html_escape($h->tanggal_nyala)), new DateTime(html_escape($h->tanggal_sip)))->format('%a');
            }
            if($h->status == "grey"){
                $row[] = "<button onclick='getMaterialPemasaran(" . html_escape($h->id) . ")' class='btn btn-secondary btn-sm'>MATERIAL</button>";
            } else if($h->status == "yellow"){
                $row[] = "<button onclick='getMaterialPemasaran(" . html_escape($h->id) . ")' class='btn btn-warning btn-sm'>MATERIAL</button>";
            } else if($h->status == "green"){
                $row[] = "<button onclick='getMaterialPemasaran(" . html_escape($h->id) . ")' class='btn btn-primary btn-sm'>MATERIAL</button>";
            }
            $row[] = html_escape($h->is_menyala) == 0 ? "<span class='badge bg-danger'>BELUM NYALA</span>" : "<span class='badge bg-primary'>NYALA</span>";
            $row[] = html_escape($h->keterangan) == null ? "-" : "<button onclick='showKeterangan(\"" . html_escape($h->keterangan) . "\")' class='btn btn-secondary btn-sm'>KETERANGAN</button>";
            $row[] = date('Y-m-d H:i:s', strtotime(html_escape($h->createddate) . ' +7 hours'));

            $button = "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">";

            if(html_escape($h->is_menyala) == 0){
                $button .= "<button onclick=\"updatePemasaran(" . html_escape($h->id) . ", '" . html_escape($h->tanggal_sip) . "', '" . html_escape($h->rencana_nyala) . "')\" class='btn btn-outline-secondary btn-sm'><i class=\"fa fa-pencil\"></i></button>";
            }

            if($this->session->userdata('delete') == 1){
                $button .= "<button onclick=\"deletePemasaran(" . html_escape($h->id) . ")\" class='btn btn-outline-secondary btn-sm'><i class=\"fa fa-trash\"></i></button>";
            }

            $button .= "</div>";
            $row[] = $button;

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

        $sheet->getStyle('A1:O1')->applyFromArray($styleHeading);
        $sheet->getStyle('H')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'O') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'UNIT');
        $sheet->setCellValue('C1', 'PELANGGAN');
        $sheet->setCellValue('D1', 'LAYANAN');
        $sheet->setCellValue('E1', 'JENIS PENGUKURAN');
        $sheet->setCellValue('F1', 'TEGANGAN');
        $sheet->setCellValue('G1', 'TARIF');
        $sheet->setCellValue('H1', 'DAYA');
        $sheet->setCellValue('I1', 'TANGGAL MOHON');
        $sheet->setCellValue('J1', 'TANGGAL BAYAR');
        $sheet->setCellValue('K1', 'RENCANA NYALA');
        $sheet->setCellValue('L1', 'TANGGAL NYALA');
        $sheet->setCellValue('M1', 'DURASI');
        $sheet->setCellValue('N1', 'STATUS');
        $sheet->setCellValue('O1', 'TANGGAL INPUT');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->name));
            $sheet->setCellValue('C' . $i, strtoupper(html_escape($h->pelanggan)));
            $sheet->setCellValue('D' . $i, strtoupper(html_escape($h->layanan)));
            $sheet->setCellValue('E' . $i, html_escape($h->pengukuran) == 1 ? "LANGSUNG" : "TIDAK LANGSUNG");
            $sheet->setCellValue('F' . $i, "T" .strtoupper(substr(html_escape($h->segment_tegangan), 0, 1)));
            $sheet->setCellValue('G' . $i, strtoupper(html_escape($h->tarif)));
            $sheet->setCellValue('H' . $i, html_escape($h->daya));
            $sheet->setCellValue('I' . $i, html_escape($h->tanggal_permohonan));
            $sheet->setCellValue('J' . $i, html_escape($h->tanggal_sip) == "0000-00-00" ? "BELUM BAYAR" : html_escape($h->tanggal_sip));
            $sheet->setCellValue('K' . $i, html_escape($h->rencana_nyala));
            $sheet->setCellValue('L' . $i, html_escape($h->tanggal_nyala));
            if(empty(html_escape($h->tanggal_nyala))){
                $sheet->setCellValue('M' . $i, html_escape($h->tanggal_sip) == "0000-00-00" ? "0" : date_diff(new DateTime(), new DateTime(html_escape($h->tanggal_sip)))->format('%a'));
            } else {
                $sheet->setCellValue('M' . $i, date_diff(new DateTime(html_escape($h->tanggal_nyala)), new DateTime(html_escape($h->tanggal_sip)))->format('%a'));
            }
            $sheet->setCellValue('N' . $i, html_escape($h->is_menyala) == 0 ? "BELUM NYALA" : "NYALA");
            $sheet->setCellValue('O' . $i, date('Y-m-d H:i:s', strtotime(html_escape($h->createddate) . ' +7 hours')));

            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Pemasaran_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function materialPemasaran(){
        $id = $this->uri->segment(3);
        $data['data'] = $this->M_AllFunction->Where("vw_material_pemasaran", "pemasaran_id = '$id'");
        $this->load->view('pemasaran/materialPemasaran', $data);
    }

    public function Save(){
        $data = array(
            "unit"               => $this->input->post('unit_add', true),
            "pelanggan"          => $this->input->post('pelanggan', true),
            "segment_tegangan"   => $this->input->post('segment_tegangan', true),
            "pengukuran"         => $this->input->post('pengukuran', true),
            "tarif"              => $this->input->post('tarif', true),
            "daya_lama"          => $this->input->post('daya_lama', true),
            "daya"               => $this->input->post('daya', true),
            "layanan"            => $this->input->post('layanan', true),
            "pekerjaan"          => $this->input->post('pekerjaan', true),
            "tanggal_permohonan" => $this->input->post('tanggal_permohonan', true),
            "is_bayar"           => $this->input->post('is_bayar', true),
            "tanggal_sip"        => $this->input->post('tanggal_sip', true),
            "rencana_nyala"      => $this->input->post('rencana_nyala', true),
            "createdby"          => $this->session->userdata('username')
        );

        $id = $this->M_AllFunction->InsertGetId('trn_pemasaran_hdr', $data);

        for ($i = 0; $i < count($this->input->post('material', true)); $i++) {
            $datadetail[$i] = array(
                "pemasaran_id" => $id,
                "material_id"  => $this->input->post('material', true)[$i],
                "volume"       => $this->input->post('volume', true)[$i],
                "rasio"        => $this->input->post('rasio', true)[$i]
                // "keterangan"   => $this->input->post('keterangan', true)[$i]
            );
        }

        $this->M_AllFunction->InsertBatch('trn_pemasaran_dtl', $datadetail);

        redirect("C_Pemasaran");
    }

    function updateTanggalSIP(){
        $id = $this->input->post("id_sip", true);
        $data = array(
            "tanggal_sip" => $this->input->post("update_tanggal_sip", true),
            "updatedby"   => $this->session->userdata('username'),
            "updateddate" => date("Y-m-d H:i:s")
        );
        $this->M_AllFunction->Update("trn_pemasaran_hdr", $data, "id = '$id'");
        redirect("C_Pemasaran");
    }

    function updateStatus(){
        $id = $this->input->post("id_status", true);
        $data = array(
            "is_menyala"    => '1',
            "tanggal_nyala" => $this->input->post("update_tanggal_nyala", true),
            "updatedby"     => $this->session->userdata('username'),
            "updateddate"   => date("Y-m-d H:i:s")
        );
        $this->M_AllFunction->Update("trn_pemasaran_hdr", $data, "id = '$id'");
        $this->M_AllFunction->CustomQueryWithoutResult("UPDATE trn_pemasaran_dtl SET approved_volume = volume WHERE pemasaran_id = '$id'");
        redirect("C_Pemasaran");
    }

    function updateRencanaNyala(){
        $id = $this->input->post("id_rencana_nyala", true);
        $data = array(
            "rencana_nyala" => $this->input->post("update_rencana_nyala", true),
            "updatedby"     => $this->session->userdata('username'),
            "updateddate"   => date("Y-m-d H:i:s")
        );
        $this->M_AllFunction->Update("trn_pemasaran_hdr", $data, "id = '$id'");
        redirect("C_Pemasaran");
    }

    function updateKeterangan(){
        $id = $this->input->post("id_keterangan", true);
        $data = array(
            "keterangan"    => $this->input->post("keterangan", true),
            "updatedby"     => $this->session->userdata('username'),
            "updateddate"   => date("Y-m-d H:i:s")
        );
        $this->M_AllFunction->Update("trn_pemasaran_hdr", $data, "id = '$id'");
        redirect("C_Pemasaran");
    }

    function update_approved_volume(){
        $id = $this->input->post("id_pemasaran", true);

        for ($i = 0; $i < count($this->input->post('material', true)); $i++) {
            $material_id = $this->input->post('material', true)[$i];
            $data = array(
                "volume"          => $this->input->post('volume', true)[$i],
                "approved_volume" => $this->input->post('approved_volume', true)[$i],
                "updated_by"      => $this->session->userdata('username')
            );
            $this->M_AllFunction->Update("trn_pemasaran_dtl", $data, "pemasaran_id = '$id' AND material_id = '$material_id'");
        }
        redirect("C_Pemasaran");
    }

    function delete(){
        $id = $this->input->post('id', true);
        $this->M_AllFunction->Delete("trn_pemasaran_hdr", "id = '$id'");
        $this->M_AllFunction->Delete("trn_pemasaran_dtl", "pemasaran_id = '$id'");
        echo "success";
    }
}