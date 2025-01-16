<?php
use Slim\Http\Response;
use Slim\Http\Request;

class material extends Library {

    public function __construct($function)
    {
        parent::__construct();
        self::$function();
        return $this->app->run();
    }

    private function search() {
        $this->app->post($this->pattern, function (Request $request, Response $response) {
            $value_data = $request->getParsedBody();
            $normalisasi = $value_data['normalisasi'];
            $unit = $value_data['unit'];

            $sql = "SELECT * FROM vw_stock_material_dtl WHERE id = '$normalisasi' AND unit_id = '$unit'";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan data";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "gagal mendapatkan data";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        })->add(parent::middleware());
    }

    private function kategori() {
        $this->app->get($this->pattern, function (Request $request, Response $response) {
            $sql = "SELECT id, kategori FROM mst_material_hdr WHERE is_active = 1";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan kategori";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "gagal mendapatkan kategori";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }

    function searchMaterial(){
        $this->app->post($this->pattern, function (Request $request, Response $response) {
            $value_data = $request->getParsedBody();

            $where = "";
            if($value_data['kategori'] != "*") {
                $where = "WHERE kategori = '" . $value_data['kategori'] . "'";
            }
            if($value_data['material'] != "*"){
                $where .= $where == "" ? "WHERE " : " AND ";
                $where .= "id = '" . $value_data['material'] . "' OR material LIKE '%" . $value_data['material'] . "%'";
            }
            if($where == ""){
                $where = "WHERE is_highlight = 1";
            }

            $sql = "SELECT * FROM vw_stock_material $where";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan material";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "maaf material tidak di temukan";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }

    function detailStockMaterial(){
        $this->app->post($this->pattern, function (Request $request, Response $response) {
            $value_data = $request->getParsedBody();
            $material_id = $value_data['material_id'];

            $sql = "SELECT
                    id, material, kategori, satuan, unit_id, name, stock
                FROM vw_stock_material_dtl
                WHERE id = '$material_id'
                GROUP BY id, material, kategori, satuan, name, stock";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan material";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "gagal mendapatkan material";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }

    function detailGudang(){
        $this->app->post($this->pattern, function (Request $request, Response $response) {
            $value_data = $request->getParsedBody();
            $material_id = $value_data['material_id'];
            $unit = $value_data['unit'];

            $sql = "SELECT
                        vw_material.id,
                        vw_material.material,
                        mst_unit.id AS unit_id,
                        mst_unit.name AS name,
                        mst_tipe_gudang.id AS id_gudang,
                        mst_tipe_gudang.name AS gudang,
                        trn_stock_material_dtl.no_gudang,
                        trn_stock_material_dtl.rak,
                        trn_stock_material_dtl.lantai,
                        trn_stock_material_dtl.petak,
                        CONCAT(mst_unit.id, ' . ', mst_tipe_gudang.id, ' - ', trn_stock_material_dtl.no_gudang, ' . ', trn_stock_material_dtl.rak, ' . ', trn_stock_material_dtl.lantai, ' . ', trn_stock_material_dtl.petak) as kode_lokasi,
                        CASE WHEN CONCAT(mst_layout_rak.url, mst_layout_rak.image_name, '.', mst_layout_rak.extension) IS NULL
                            THEN 'https://e-logisticspln.com/uploads/denah_gudang/not_found.png'
                            ELSE CONCAT(mst_layout_rak.url, mst_layout_rak.image_name, '.', mst_layout_rak.extension)
                        END AS url
                    FROM trn_stock_material_dtl
                    JOIN vw_material
                    ON trn_stock_material_dtl.id_material = vw_material.id
                    JOIN mst_unit
                    ON trn_stock_material_dtl.unit = mst_unit.id
                    JOIN mst_tipe_gudang
                    ON trn_stock_material_dtl.id_gudang = mst_tipe_gudang.id
                    LEFT JOIN mst_layout_rak
                    ON trn_stock_material_dtl.unit = mst_layout_rak.unit
                        AND trn_stock_material_dtl.id_gudang = mst_layout_rak.id_gudang
                        AND trn_stock_material_dtl.no_gudang = mst_layout_rak.no_gudang
                        AND trn_stock_material_dtl.rak = mst_layout_rak.rak
                    WHERE trn_stock_material_dtl.id_material = '$material_id' AND trn_stock_material_dtl.unit = '$unit'";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan data";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "material belum di inputkan di gudang";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }

    function cekGudang(){
        $this->app->post($this->pattern, function (Request $request, Response $response) {
            $value_data = $request->getParsedBody();
            $id_material = $value_data['normaliasi'];
            $unit = $value_data['unit'];

            $sql = "SELECT * FROM vw_stock_material_gudang WHERE id_material = '$id_material' AND id_unit = '$unit'";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan data";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "gagal mendapatkan data";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }
}