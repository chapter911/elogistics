<?php
use Slim\Http\Response;
use Slim\Http\Request;

class master extends Library {

    public function __construct($function)
    {
        parent::__construct();
        self::$function();
        return $this->app->run();
    }

    private function unit() {
        $this->app->get($this->pattern, function (Request $request, Response $response) {
            $sql = "SELECT id, name FROM mst_unit";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan unit";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "gagal mendapatkan unit";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }

    private function tipeGudang() {
        $this->app->get($this->pattern, function (Request $request, Response $response) {
            $sql = "SELECT * FROM mst_tipe_gudang";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan tipe gudang";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "gagal mendapatkan tipe gudang";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }

    private function noGudang() {
        $this->app->get($this->pattern, function (Request $request, Response $response) {
            $sql = "SELECT no_gudang FROM trn_stock_material_dtl GROUP BY no_gudang ORDER BY no_gudang";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan tipe gudang";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "gagal mendapatkan tipe gudang";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }

    private function rak() {
        $this->app->get($this->pattern, function (Request $request, Response $response) {
            $sql = "SELECT rak FROM trn_stock_material_dtl GROUP BY rak ORDER BY rak";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan rak";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "gagal mendapatkan rak";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }

    private function gudang() {
        $this->app->get($this->pattern, function (Request $request, Response $response) {
            $sql = "SELECT * FROM mst_gudang";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan master gudang";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "gagal mendapatkan master gudang";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }

    private function statusAttb() {
        $this->app->get($this->pattern, function (Request $request, Response $response) {
            $sql = "SELECT * FROM mst_status_attb";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                $result['status']  = "success";
                $result['message'] = "berhasil mendapatkan master status attb";
                $result['data']    = $fetch;
            } else {
                $result['status']  = "failed";
                $result['message'] = "gagal mendapatkan master status attb";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }
}