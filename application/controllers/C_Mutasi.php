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

class C_Mutasi extends CI_Controller {

	function __construct(){
		parent::__construct();

        $this->load->model(array("M_AllFunction", "M_Mutasi"));

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
        $data['material'] = $this->M_AllFunction->Get('vw_material');
		$this->template->display("mutasi/index", $data);
	}

    public function getData(){
        $hasil = $this->M_Mutasi->getData();

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

			$row[] = $i++;
			$row[] = html_escape($h->singkatan);
			$row[] = html_escape($h->normalisasi);
			$row[] = html_escape($h->material);
			$row[] = html_escape($h->satuan);
            $row[] = html_escape($h->persediaan_karantina);
            $row[] = html_escape($h->persediaan_awal);
            $row[] = html_escape($h->mutasi_masuk);
            $row[] = html_escape($h->mutasi_keluar);
            $row[] = html_escape($h->persediaan_akhir);
            $row[] = html_escape($h->tanggal_pergerakan);
            $row[] = html_escape($h->durasi);
            $row[] = html_escape($h->tipe_pergerakan);
            $row[] = html_escape($h->slip);
            $row[] = html_escape($h->mata_uang);
            $row[] = html_escape($h->harga_total);
            $row[] = html_escape($h->harga_satuan);
            $row[] = html_escape($h->no_kode_7);

            $data['data'][] = $row;
        }

		$data['draw'] = $this->input->post('draw', true);
        $data['recordsTotal'] = $this->M_Mutasi->getTotal();
        $data['recordsFiltered'] = $this->M_Mutasi->getTotalFiltered();

		echo json_encode($data);
    }

    function exportIndex(){
        $hasil = $this->M_Mutasi->exportData();

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

        $sheet->getStyle('A1:R1')->applyFromArray($styleHeading);

        foreach(range('A', 'R') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'UNIT');
        $sheet->setCellValue('C1', 'NORMALISASI');
        $sheet->setCellValue('D1', 'JENIS MATERIAL');
        $sheet->setCellValue('E1', 'SATUAN');
        $sheet->setCellValue('F1', 'PERSEDIAAN KARANTINA');
        $sheet->setCellValue('G1', 'PERSEDIAAN AWAL');
        $sheet->setCellValue('H1', 'MUTASI MASUK');
        $sheet->setCellValue('I1', 'MUTASI KELUAR');
        $sheet->setCellValue('J1', 'PERSEDIAAN AKHIR');
        $sheet->setCellValue('K1', 'TANGGAL PERGERAKAN');
        $sheet->setCellValue('L1', 'DURASI');
        $sheet->setCellValue('M1', 'TIPE PERGERAKAN');
        $sheet->setCellValue('N1', 'SLIP');
        $sheet->setCellValue('O1', 'MATA UANG');
        $sheet->setCellValue('P1', 'HARGA TOTAL');
        $sheet->setCellValue('Q1', 'HARGA SATUAN');
        $sheet->setCellValue('R1', 'NO KODE 7');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i - 1);
			$sheet->setCellValue('B' . $i, html_escape($h->singkatan));
			$sheet->setCellValue('C' . $i, html_escape($h->normalisasi));
			$sheet->setCellValue('D' . $i, html_escape($h->material));
			$sheet->setCellValue('E' . $i, html_escape($h->satuan));
            $sheet->setCellValue('F' . $i, html_escape($h->persediaan_karantina));
            $sheet->setCellValue('G' . $i, html_escape($h->persediaan_awal));
            $sheet->setCellValue('H' . $i, html_escape($h->mutasi_masuk));
            $sheet->setCellValue('I' . $i, html_escape($h->mutasi_keluar));
            $sheet->setCellValue('J' . $i, html_escape($h->persediaan_akhir));
            $sheet->setCellValue('K' . $i, html_escape($h->tanggal_pergerakan));
            $sheet->setCellValue('L' . $i, html_escape($h->durasi));
            $sheet->setCellValue('M' . $i, html_escape($h->tipe_pergerakan));
            $sheet->setCellValue('N' . $i, html_escape($h->slip));
            $sheet->setCellValue('O' . $i, html_escape($h->mata_uang));
            $sheet->setCellValue('P' . $i, html_escape($h->harga_total));
            $sheet->setCellValue('Q' . $i, html_escape($h->harga_satuan));
            $sheet->setCellValue('R' . $i, html_escape($h->no_kode_7));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Mutasi_Material'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}