<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 */

class C_Dashboard extends CI_Controller {

	function __construct(){
		parent::__construct();

		if (!$this->session->userdata('username')) {
			redirect('C_Login');
		}
	}

	public function index(){
        $data['unit'] = $this->M_AllFunction->Where('mst_unit', 'id = ' . $this->session->userdata('unit_id'));
		$this->load->view('v_dashboard', $data);
	}

    public function ajaxStockMaterial(){
        $is_highlight = $this->input->post('is_highlight') == "true" ? " AND vw_material.is_highlight = 1 " : "";
        $data['is_highlight'] = $this->input->post('is_highlight');
        $query = "WITH uid AS (
                    SELECT
                        material,
                        SUM(trn_stock_material.unrestricted_use_stock) AS volume
                    FROM trn_stock_material
                    WHERE plant = 5400 AND trn_stock_material.tanggal_stock = (SELECT max(trn_stock_material.tanggal_stock) FROM trn_stock_material)
                    GROUP BY material
                )
                SELECT
                    trn_stock_material.plant,
                    trn_stock_material.material AS id_material,
                    vw_material.material AS material,
                    vw_material.satuan AS satuan,
                    vw_material.harga_satuan AS harga_satuan,
                    IFNULL(uid.volume, 0) AS stock_uid,
                    SUM(trn_stock_material.unrestricted_use_stock) AS stock_up3,
                    vw_analisis_pengadaan.status_text
                FROM trn_stock_material
                LEFT JOIN vw_material ON trn_stock_material.material = vw_material.id
                LEFT JOIN uid ON trn_stock_material.material = uid.material
                LEFT JOIN vw_analisis_pengadaan
                ON trn_stock_material.material = vw_analisis_pengadaan.material_id
                WHERE trn_stock_material.tanggal_stock = (SELECT max(trn_stock_material.tanggal_stock) FROM trn_stock_material)
                    $is_highlight AND trn_stock_material.plant = '" . $this->session->userdata('unit_id') . "'
                GROUP BY trn_stock_material.material, trn_stock_material.plant
                ORDER BY status ASC, trn_stock_material.plant, trn_stock_material.material";

        $hasil = $this->M_AllFunction->CustomQuery($query);
        $total = 0;
        $data['data'] = array();
        $i = 1;
        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            // $row[] = strlen($h->material) > 30 ? substr($h->material, 0, 30) . "..." : $h->material;
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->satuan);
            $row[] = number_format(html_escape($h->stock_uid), 0, ",", ".");
            $row[] = number_format(html_escape($h->stock_up3), 0, ",", ".");
            $row[] = number_format(html_escape($h->stock_uid)+html_escape($h->stock_up3), 0, ",", ".");

            $total += html_escape($h->harga_satuan) * html_escape($h->stock_up3);
            $row[] = number_format(html_escape($h->harga_satuan) * html_escape($h->stock_up3), 0, ",", ".");

            if(html_escape($h->status_text) == "AMAN"){
                $row[] = "<span class='badge badge-primary btn-primary'> AMAN </span>";
            } else if(html_escape($h->status_text) == "SIAGA") {
                $row[] = "<span class='badge badge-warning btn-warning'> SIAGA </span>";
            } else {
                $row[] = "<span class='badge badge-danger btn-danger'> KRITIS </span>";
            }
            $data['data'][] = $row;
        }

        $data['total'] = number_format($total, 0, ",", ".");

        echo json_encode($data);
    }

	public function ajaxStockMaterialUID(){
        $is_highlight = $this->input->post('is_highlight') == "true" ? " WHERE is_dashboard = 1 " : "";
        $query = "SELECT * FROM vw_analisis_pengadaan $is_highlight ORDER BY `status` ASC";
        $hasil = $this->M_AllFunction->CustomQuery($query);
        $data['data'] = array();
        $i = 1;
        $total = 0;
        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            // $row[] = strlen($h->material) > 30 ? substr($h->material, 0, 30) . "..." : $h->material;
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->satuan);
            $row[] = number_format(html_escape($h->stock_uid), 0, ",", ".");
            $row[] = number_format(html_escape($h->stock_up3), 0, ",", ".");

            $total += html_escape($h->harga_satuan) * html_escape($h->stock_up3);
            $row[] = number_format(html_escape($h->stock_uid) + html_escape($h->stock_up3), 0, ",", ".");

            $row[] = number_format((html_escape($h->harga_satuan) * html_escape($h->stock_up3)), 0, ",", ".");
            if(html_escape($h->status_text) == "AMAN"){
                $row[] = "<span class='badge badge-primary btn-primary'> AMAN </span>";
            } else if(html_escape($h->status_text) == "SIAGA") {
                $row[] = "<span class='badge badge-warning btn-warning'> SIAGA </span>";
            } else {
                $row[] = "<span class='badge badge-danger btn-danger'> KRITIS </span>";
            }
            $data['data'][] = $row;
        }

        $data['total'] = number_format($total, 0, ",", ".");

        echo json_encode($data);
	}

	public function ajaxKedatanganMaterial(){
        $unit = "";
        if($this->session->userdata('unit_id') != 5400){
            $unit = " AND trn_kontrak_dtl.unit_tujuan_id = '" . $this->session->userdata('unit_id') . "'";
        }
        $query = "SELECT
                    vw_pengiriman.material,
                    vw_pengiriman.satuan,
                    vw_pengiriman.harga_satuan,
                    SUM(trn_kontrak_dtl.volume) AS volume,
                    vw_pengiriman.rencana_kirim
                FROM vw_pengiriman
                LEFT JOIN trn_kontrak_dtl
                ON vw_pengiriman.no_kontrak = trn_kontrak_dtl.no_kontrak
                WHERE vw_pengiriman.status_kirim <> 'SELESAI KIRIM' $unit
                GROUP BY vw_pengiriman.material, vw_pengiriman.rencana_kirim";
        $hasil = $this->M_AllFunction->CustomQuery($query);
        $data['data'] = array();
        $i = 1;
        $total = 0;
        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->satuan);
            $row[] = number_format(html_escape($h->volume), 0, ",", ".");

            $total += html_escape($h->harga_satuan) * html_escape($h->volume);
            $row[] = number_format((html_escape($h->harga_satuan) * html_escape($h->volume)), 0, ",", ".");
            $row[] = html_escape($h->rencana_kirim);
            $data['data'][] = $row;
        }

        $data['total'] = number_format($total, 0, ",", ".");

        echo json_encode($data);
	}

	public function ajaxAttb(){
        $unit = "";
        if($this->session->userdata('unit_id') != 5400){
            $unit = "WHERE unit = '" . $this->session->userdata('unit_id') . "'";
        }
        $query = "SELECT
                material,
                SUM(volume) AS volume,
                SUM(CASE WHEN status_id = 0 THEN volume ELSE 0 END) AS belum,
                SUM(CASE WHEN status_id = 1 THEN volume ELSE 0 END) AS ae1,
                SUM(CASE WHEN status_id = 2 THEN volume ELSE 0 END) AS ae2,
                SUM(CASE WHEN status_id = 3 THEN volume ELSE 0 END) AS ae3,
                SUM(CASE WHEN status_id = 4 THEN volume ELSE 0 END) AS ae4
            FROM vw_attb
            $unit
            GROUP BY material_id";

        $hasil = $this->M_AllFunction->CustomQuery($query);
        $data['data'] = array();
        $i = 1;
        foreach ($hasil as $h) {
            $row = array();

            $row[] = $i++;
            $row[] = html_escape($h->material);
            $row[] = number_format(html_escape($h->volume), 0, ",", ".");
            $row[] = number_format(html_escape($h->belum), 0, ",", ".");
            $row[] = number_format(html_escape($h->ae1), 0, ",", ".");
            $row[] = number_format(html_escape($h->ae2), 0, ",", ".");
            $row[] = number_format(html_escape($h->ae3), 0, ",", ".");
            $row[] = number_format(html_escape($h->ae4), 0, ",", ".");
            $data['data'][] = $row;
        }

        echo json_encode($data);
	}

    function ajaxPengeluaranMaterial(){
        $unit = "";
        if($this->session->userdata('unit_id') != 5400){
            $unit = " WHERE unit_id = '" . $this->session->userdata('unit_id') . "'";
        }
        $query = "SELECT
                material,
                satuan,
                SUM(volume) AS volume,
                harga_satuan,
                bulan,
                minggu
            FROM vw_permohonan_dashboard
            $unit
            GROUP BY normalisasi";

        $hasil = $this->M_AllFunction->CustomQuery($query);
        $data['data'] = array();
        $i = 1;
        $total = 0;
        foreach ($hasil as $h) {
            $row = array();

            //ambil bulan berjalan, hitung juga hitungan perweeknya.
            $row[] = $i++;
            $row[] = html_escape($h->material);
            $row[] = html_escape($h->satuan);
            $row[] = number_format(html_escape($h->volume), 0, ",", ".");

            $total += html_escape($h->harga_satuan) * html_escape($h->volume);
            $row[] = number_format((html_escape($h->harga_satuan) * html_escape($h->volume)), 0, ",", ".");
            $row[] = html_escape($h->bulan);
            $row[] = html_escape($h->minggu);
            $data['data'][] = $row;
        }
        $data['total'] = number_format($total, 0, ",", ".");

        echo json_encode($data);
    }
}