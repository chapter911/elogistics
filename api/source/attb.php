<?php
use Slim\Http\Response;
use Slim\Http\Request;

class attb extends Library {

    public function __construct($function)
    {
        parent::__construct();
        self::$function();
        return $this->app->run();
    }

    private function getData() {
        $this->app->post($this->pattern, function (Request $request, Response $response) {
            $header = $request->getHeaders();
            $unit = $header["HTTP_UNIT_ID"][0];

            $value_data = $request->getParsedBody();
            $no_attb = $value_data['no_attb'];

            $where = "";
            if($no_attb != "*") {
                $where .= $where == "" ? " WHERE " : " AND ";
                $where .= " no_attb = '" . $no_attb . "'";
            }
            if($unit != "5400") {
                $where .= $where == "" ? " WHERE " : " AND ";
                $where .= " unit = '" . $unit . "'";
            }

            $sql = "SELECT *, 'https://e-logisticspln.com' AS url FROM vw_attb $where";
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

    function save(){
        $this->app->post($this->pattern, function (Request $request, Response $response) {
            $header = $request->getHeaders();
            $unit = $header["HTTP_UNIT_ID"][0];
            $username = $header["HTTP_USERNAME"][0];

            $value_data = $request->getParsedBody();
            $id            = $value_data['id'];
            $tug           = $value_data['tug'];
            $sipb          = $value_data['sipb'];
            $no_attb       = $value_data['no_attb'];
            $material_id   = $value_data['material_id'];
            $volume        = $value_data['volume'];
            $fat           = $value_data['fat'];
            $serial_number = $value_data['serial_number'];
            $location      = $value_data['location'];
            $status        = $value_data['status'];

            if($id == "0") {
                $sql = "INSERT INTO trn_attb (tug, sipb,no_attb, material_id, volume, fat, serial_number, location, status, unit, created_by)
                    VALUES (:tug, :sipb,n:o_attb, :material_id, :volume, :fat, :serial_number, :location, :status, :unit, :created_by)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':tug'           => $tug,
                    ':sipb'          => $sipb,
                    ':no_attb'       => $no_attb,
                    ':material_id'   => $material_id,
                    ':volume'        => $volume,
                    ':fat'           => $fat,
                    ':serial_number' => $serial_number,
                    ':location'      => $location,
                    ':status'        => $status,
                    ':unit'          => $unit,
                    ':created_by'    => $username
                ]);
                $result['status']  = "success";
                $result['message'] = "Berhasil Menambah Data";
                $result['data']    = null;
            } else {
                $sql = "UPDATE trn_attb SET tug = :tug, sipb = :sipb, no_attb = :no_attb, material_id = :material_id, volume = :volume, fat = :fat, serial_number = :serial_number, location = :location, status = :status, unit = :unit, updated_by = :updated_by, updated_date = :updated_date WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':id'            => $id,
                    ':tug'           => $tug,
                    ':sipb'          => $sipb,
                    ':no_attb'       => $no_attb,
                    ':material_id'   => $material_id,
                    ':volume'        => $volume,
                    ':fat'           => $fat,
                    ':serial_number' => $serial_number,
                    ':location'      => $location,
                    ':status'        => $status,
                    ':unit'          => $unit,
                    ':updated_by'    => $username,
                    ':updated_date'  => date('Y-m-d H:i:s')
                ]);
                $result['status'] = "success";
                $result['message'] = "Berhasil Mengedit Data";
                $result['data']    = null;
            }

            return $response->withJson($result, 200);
        })->add(parent::middleware());
    }
}