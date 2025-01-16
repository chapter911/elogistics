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

class C_Efesiensi extends CI_Controller {

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
        $this->template->display('efesiensi/index', $data);
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

        $query = "SELECT * FROM vw_trn_efesiensi_hdr $where";

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
            $row[] = html_escape($h->pengukuran) == 1 ? "LANGSUNG" : "TIDAK LANGSUNG";
            $row[] = strtoupper(html_escape($h->anggaran));
            $row[] = "<button onclick='getMaterialEfesiensi(" . html_escape($h->id) . ")' class='btn btn-secondary btn-sm'>MATERIAL</button>";
            $row[] = html_escape($h->is_selesai) == "0" ? "<span class='badge bg-danger'>BELUM SELESAI</span>" : "<span class='badge bg-primary'>SELESAI</span>";
            $row[] = date('Y-m-d H:i:s', strtotime(html_escape($h->created_date) . ' +7 hours'));

            $button = "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">";

            if(html_escape($h->is_selesai) == 0){
                $button .= "<button onclick=\"updateEfesiensi(" . html_escape($h->id) . ")\" class='btn btn-outline-secondary btn-sm'><i class=\"fa fa-pencil\"></i></button>";
            }

            if($this->session->userdata('delete') == 1){
                $button .= "<button onclick=\"deleteEfesiensi(" . html_escape($h->id) . ")\" class='btn btn-outline-secondary btn-sm'><i class=\"fa fa-trash\"></i></button>";
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

        $sheet->getStyle('A1:M1')->applyFromArray($styleHeading);

        foreach(range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'UNIT');
        $sheet->setCellValue('C1', 'PELANGGAN / LOKASI');
        $sheet->setCellValue('D1', 'JENIS PENGUKURAN');
        $sheet->setCellValue('E1', 'ANGGARAN');
        $sheet->setCellValue('F1', 'STATUS');
        $sheet->setCellValue('G1', 'TANGGAL INPUT');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->name));
            $sheet->setCellValue('C' . $i, strtoupper(html_escape($h->pelanggan)));
            $sheet->setCellValue('D' . $i, html_escape($h->pengukuran) == 1 ? "LANGSUNG" : "TIDAK LANGSUNG");
            $sheet->setCellValue('E' . $i, strtoupper(html_escape($h->anggaran)));
            $sheet->setCellValue('F' . $i, html_escape($h->is_selesai) == "0" ? "BELUM SELESAI" : "SELESAI");
            $sheet->setCellValue('G' . $i, date('Y-m-d H:i:s', strtotime(html_escape($h->created_date) . ' +7 hours')));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Efesiensi_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function materialEfesiensi(){
        $id = $this->uri->segment(3);
        $data['data'] = $this->M_AllFunction->Where("vw_material_efesiensi", "efesiensi_id = '$id'");
        $this->load->view('efesiensi/materialEfesiensi', $data);
    }

    public function Save(){
        $data = array(
            "unit"             => $this->input->post('unit_add', true),
            "pelanggan"        => $this->input->post('pelanggan', true),
            "segment_tegangan" => $this->input->post('segment_tegangan', true),
            "pengukuran"       => $this->input->post('pengukuran', true),
            "anggaran"         => $this->input->post('anggaran', true),
            "created_by"        => $this->session->userdata('username')
        );

        $id = $this->M_AllFunction->InsertGetId('trn_efesiensi_hdr', $data);

        for ($i = 0; $i < count($this->input->post('material', true)); $i++) {
            $datadetail[$i] = array(
                "efesiensi_id" => $id,
                "material_id"  => $this->input->post('material', true)[$i],
                "volume"       => $this->input->post('volume', true)[$i],
                "rasio"        => $this->input->post('rasio', true)[$i],
                "keterangan"   => $this->input->post('keterangan', true)[$i]
            );
        }

        $this->M_AllFunction->InsertBatch('trn_efesiensi_dtl', $datadetail);

        redirect("C_Efesiensi");
    }

    function update_approved_volume(){
        $id = $this->input->post("id_efesiensi", true);

        for ($i = 0; $i < count($this->input->post('material', true)); $i++) {
            $material_id = $this->input->post('material', true)[$i];
            $data = array(
                "volume"          => $this->input->post('volume', true)[$i],
                "approved_volume" => $this->input->post('approved_volume', true)[$i],
                "updated_by"      => $this->session->userdata('username')
            );
            $this->M_AllFunction->Update("trn_efesiensi_dtl", $data, "efesiensi_id = '$id' AND material_id = '$material_id'");
        }
        if(isset($_POST['is_selesai'])){
            $this->M_AllFunction->Update('trn_efesiensi_hdr', array("is_selesai" => '1'), "id = '$id'");
        }

        redirect("C_Efesiensi");
    }

    function delete(){
        $id = $this->input->post('id', true);
        $this->M_AllFunction->Delete("trn_efesiensi_hdr", "id = '$id'");
        $this->M_AllFunction->Delete("trn_efesiensi_dtl", "efesiensi_id = '$id'");
        echo "success";
    }
}