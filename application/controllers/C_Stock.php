<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @property M_AllFunction $M_AllFunction
 * @property M_Stock $M_Stock
 * @property Session $session
 * @property Template $template
 * @property Upload $upload
 * @property Uri $uri
 */

class C_Stock extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model(array("M_AllFunction"));

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
        $data['kategori'] = $this->M_AllFunction->CustomQuery("SELECT id, kategori FROM mst_material_hdr WHERE is_active = 1");
        $data['tanggal_stock'] = $this->M_AllFunction->CustomQuery("SELECT MAX(tanggal_stock) AS tanggal_stock FROM trn_stock_material");
        $this->template->display('stock/index', $data);
    }

    function getData(){
        $where = $this->input->post('is_highlight', true) == "1" ? " WHERE is_highlight = 1 " : "";

        if($this->input->post('kategori', true) != "*"){
            $where .= $where == "" ? " WHERE " : " AND ";
            $where .= "kategori_id = '" . $this->input->post('kategori', true) . "'";
        }

        $query = "SELECT * FROM vw_stock_material $where";

        $hasil = $this->M_AllFunction->CustomQuery($query);

        $data['data'] = array();

        $i = 1;

        foreach ($hasil as $h) {
            $row = array();

			$row[] = $i++;
			$row[] = html_escape($h->id);
			$row[] = html_escape($h->kategori);
			$row[] = html_escape($h->material);
			$row[] = html_escape($h->satuan);
			$row[] = number_format(html_escape($h->stock_uid), 0, ',', '.');
			$row[] = number_format(html_escape($h->stock_up3), 0, ',', '.');
			$row[] = number_format((html_escape($h->stock_uid) + html_escape($h->stock_up3)), 0, ',', '.');
            $row[] = "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">
                        <button class=\"btn btn-outline-secondary btn-sm waves-effect waves-light\" onclick=\"detailStock(" . html_escape($h->id) . ", '" . html_escape($h->material) . "')\" data-bs-toggle=\"modal\" data-bs-target=\"#modal_edit\"><i class=\"fa fa-eye\"></i></button>
                    </div>";
            $data['data'][] = $row;
        }

		echo json_encode($data);
    }

    function detailStock()
    {
        $material_id = $this->input->post('id', true);
        $query = "WITH stock AS (
                    SELECT
                        plant,
                        SUM(unrestricted_use_stock) AS jumlah
                    FROM trn_stock_material
                    WHERE material = '$material_id'
                        AND tanggal_stock = ( SELECT MAX( tanggal_stock ) FROM trn_stock_material )
                    GROUP BY plant
                ), material AS (
                    SELECT
                        *
                    FROM vw_material
                    WHERE id = '$material_id'
                ), unit_material AS (
                    SELECT
                        mst_unit.id AS id_unit,
                        mst_unit.name AS name,
                        material.id,
                        material.material,
                        material.satuan
                    FROM mst_unit
                    CROSS JOIN material
                )
                SELECT
                    unit_material.id_unit AS id,
                    unit_material.id AS material,
                    unit_material.material AS material_description,
                    unit_material.name,
                    unit_material.satuan,
                    IFNULL(stock.jumlah, 0) AS jumlah
                FROM unit_material
                LEFT JOIN stock
                ON unit_material.id_unit = stock.plant
                ORDER BY stock.jumlah DESC, unit_material.name";

        $hasil = $this->M_AllFunction->CustomQuery($query);

        $data['data'] = array();

        $i = 1;
        $total = 0;

        foreach ($hasil as $h) {
            $row = array();

			$row[] = $i++;
			$row[] = html_escape($h->material);
			$row[] = html_escape($h->material_description);
			$row[] = html_escape($h->name);
			$row[] = html_escape($h->satuan);
			$row[] = number_format(html_escape($h->jumlah), 0, ',', '.');
			$total += html_escape($h->jumlah);
            $data['data'][] = $row;
        }
        $data['total'][] = number_format($total, 0, ',', '.');

		echo json_encode($data);
    }

    function import() {
        $username = str_replace(".", "_", $this->session->userdata('username'));
        $config['allowed_types'] = 'xls|xlsx';
        $config['remove_spaces'] = TRUE;
        $config['max_size'] = 10000;
        $config['upload_path'] = "data_uploads/stockmaterial/";
        $config['file_name'] = "stock-" . $username . " " . date('Y-m-d H-i-s');

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
                    $sheetData[6][0] != "Company Code" &&
                    $sheetData[6][1] != "Company Code Description" &&
                    $sheetData[6][2] != "Plant" &&
                    $sheetData[6][3] != "Plant Description" &&
                    $sheetData[6][4] != "Storage Location" &&
                    $sheetData[6][5] != "Storage Location Description" &&
                    $sheetData[6][6] != "Material Type" &&
                    $sheetData[6][7] != "Material Type Description" &&
                    $sheetData[6][8] != "Material" &&
                    $sheetData[6][9] != "Material Description" &&
                    $sheetData[6][10] != "Material Group" &&
                    $sheetData[6][11] != "Base Unit of Measure" &&
                    $sheetData[6][12] != "Valuation Type" &&
                    $sheetData[6][13] != "Unrestricted Use Stock" &&
                    $sheetData[6][14] != "Quality Inspection Stock" &&
                    $sheetData[6][15] != "Blocked Stock" &&
                    $sheetData[6][16] != "In Transit Stock" &&
                    $sheetData[6][17] != "Valuation Class" &&
                    $sheetData[6][18] != "Valuation Description"
                ) {
                    $this->session->set_flashdata('message', 'Format Header File Tidak Sesuai');
                    redirect('C_Stock/Add');
                } else {
                    for ($i = 7; $i < count($sheetData); $i++) {
                        if ($sheetData[$i][0] != '') {
                            $data[$i - 1] = array(
                                "company_code"                 => $sheetData[$i][0],
                                "company_code_description"     => $sheetData[$i][1],
                                "plant"                        => $sheetData[$i][2],
                                "plant_description"            => $sheetData[$i][3],
                                "storage_location"             => $sheetData[$i][4],
                                "storage_location_description" => $sheetData[$i][5],
                                "material_type"                => $sheetData[$i][6],
                                "material_type_description"    => $sheetData[$i][7],
                                "material"                     => $sheetData[$i][8],
                                "material_description"         => $sheetData[$i][9],
                                "material_group"               => $sheetData[$i][10],
                                "base_unit_of_measure"         => $sheetData[$i][11],
                                "valuation_type"               => $sheetData[$i][12],
                                "unrestricted_use_stock"       => $sheetData[$i][13],
                                "quality_inspection_stock"     => $sheetData[$i][14],
                                "blocked_stock"                => $sheetData[$i][15],
                                "intransit_stock"              => $sheetData[$i][16],
                                "valuation_class"              => $sheetData[$i][17],
                                "valuation_description"        => $sheetData[$i][18],
                                "tanggal_stock"                => $this->input->post('tanggal_stock', true),
                                "uploadedby"                   => $this->session->userdata('username'),
                                "uploadeddate"                 => date('Y-m-d H-i-s')
                            );
                        }
                    }
                    $this->M_AllFunction->Delete('trn_stock_material', "tanggal_stock = '" . $this->input->post('tanggal_stock', true) . "'");
                    if ($this->M_AllFunction->InsertBatch('trn_stock_material', $data)) {
                        //update satuan otomatis
                        $this->M_AllFunction->CustomQueryWithoutResult(
                            "UPDATE mst_material_dtl AS A, trn_stock_material AS B
                            SET A.satuan_id = (SELECT id FROM mst_satuan WHERE id = B.base_unit_of_measure)
                            WHERE A.id = B.material");
                        $this->session->set_flashdata('flash_success', 'Data Berhasil Di Import');
                        redirect('C_Stock');
                    } else {
                        $this->session->set_flashdata('flash_failed', 'Gagal Input Data, Cek Kembali File Anda');
                        redirect('C_Stock/Add');
                    }
                }
            }
        }
    }

    function Export(){
        $where = $this->input->post('is_highlight', true) == "1" ? " WHERE is_highlight = 1 " : "";

        if($this->input->post('kategori_id', true) != "*"){
            $where .= $where == "" ? " WHERE " : " AND ";
            $where .= "kategori_id = '" . $this->input->post('kategori_id', true) . "'";
        }

        $query = "SELECT * FROM vw_stock_material $where";

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

        $sheet->getStyle('A1:H1')->applyFromArray($styleHeading);
        $sheet->getStyle('F:H')->getNumberFormat()->setFormatCode('#,##0');

        foreach(range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NORMALISASI');
        $sheet->setCellValue('C1', 'KATEGORI');
        $sheet->setCellValue('D1', 'MATERIAL');
        $sheet->setCellValue('E1', 'SATUAN');
        $sheet->setCellValue('F1', 'STOCK UID');
        $sheet->setCellValue('G1', 'STOCK UNIT');
        $sheet->setCellValue('H1', 'TOTAL STOCK');

        $i = 2;

        foreach ($hasil as $h) {
            $sheet->setCellValue('A' . $i, $i-1);
            $sheet->setCellValue('B' . $i, html_escape($h->id));
            $sheet->setCellValue('C' . $i, html_escape($h->kategori));
            $sheet->setCellValue('D' . $i, html_escape($h->material));
            $sheet->setCellValue('E' . $i, html_escape($h->satuan));
            $sheet->setCellValue('F' . $i, html_escape($h->stock_uid));
            $sheet->setCellValue('G' . $i, html_escape($h->stock_up3));
            $sheet->setCellValue('H' . $i, (html_escape($h->stock_uid) + html_escape($h->stock_up3)));
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        $date = date('d-m-Y_H-i-s');
        header('Content-Disposition: attachment;filename="Dashboard_Kontrak_Rinci_'.$date.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}