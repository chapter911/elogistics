<?php
use Slim\Http\Response;
use Slim\Http\Request;

class user extends Library {

    public function __construct($function)
    {
        parent::__construct();
        self::$function();
        return $this->app->run();
    }

    private function login() {
        $this->app->post($this->pattern, function (Request $request, Response $response) {
            $value_data = $request->getParsedBody();
            $sql = "SELECT * FROM vw_user WHERE username = '" . $value_data["username"] . "' AND password = '" . sha1(md5($value_data["password"])) . " '";
            $fetch = $this->db->query($sql)->fetchAll();
            if ($fetch) {
                if($fetch[0]['is_active'] == 0){
                    $result['status']  = "failed";
                    $result['message'] = "user anda telah di non aktifkan";
                    $result['data']    = null;
                } else if($fetch[0]['is_android'] == 0){
                    $result['status']  = "failed";
                    $result['message'] = "user anda tidak di izinkan menggunakan aplikasi ini";
                    $result['data']    = null;
                } else {
                    $apikey = wordwrap(strtoupper(bin2hex(random_bytes(32))), 8, '-', true);
                    $update = "UPDATE mst_user SET apikey = '$apikey' WHERE username = '" . $value_data["username"] . "'";
                    $stmt = $this->db->prepare($update);
                    if($stmt->execute()){
                        $fetch[0]['apikey'] = $apikey;

                        $result['status']  = "success";
                        $result['message'] = "selamat datang";
                        $result['data']    = $fetch;
                    } else {
                        $data['status']     = "failed";
                        $data['message'] = "maaf tidak dapat memproses data anda";
                        $data['data'] = null;
                    }
                }
            } else {
                $result['status']  = "failed";
                $result['message'] = "username atau password anda salah";
                $result['data']    = null;
            }
            return $response->withJson($result, 200);
        });
    }
}