<?php

use Slim\Http\Response;
use Slim\Http\Request;

class Library extends Settings {

    /**
     * Library constructor.
     * Berguna untuk pembuatan function tambahan yang digunakan API
     *
     * Cara pemanggilan API di URL :
     * <host>/<route_API>/$Modul/function()
     *
     * Cara inisialisasi SLIM menggunakan $this->app
     * Lihat Class Settings
     *
     * function(Request $request, Response $response, $args)
     * Parameter ketiga adalah inputan masukkan dari url (bagian dari $this->app)
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->deklarasi = $this->container;
        $this->pattern = self::configUri();
    }

    private function configUri() {
        $URI = explode('/', $_SERVER['REQUEST_URI']);
        $modul = $URI[0];
        $class = $URI[1];
        $function = $URI[2];

        $config = '/'.$class.'/'.$function;
        return $config;
    }

    /**
     * Middleware berguna untuk otentikasi API Key
     * Penjelasan selengkapnya tentang konsep middleware dapat dilihat
     * Doc : http://www.slimframework.com/docs/v3/concepts/middleware.html
     */
    protected function middleware() {
        $middleware = function (Request $request, Response $response, $next, $encode = true) {
            try {
                $header = $request->getHeaders();
                $username = $header["HTTP_USERNAME"][0];
                $apikey  = $header["HTTP_APIKEY"][0];

                $cek = "SELECT * FROM mst_user WHERE username = '$username'";
                $datacek = $this->db->query($cek)->fetch(PDO::FETCH_OBJ);

                if($datacek){
                    if($datacek->is_active == '0'){
                        $data['status']  = "logout";
                        $data['message'] = "maaf user anda telah di non-aktifkan";
                        $data['data']    = null;
                        return $response->withJson($data, 200);
                    } else if($datacek->apikey != $apikey){
                        $data['status']  = "logout";
                        $data['message'] = "maaf kami mendeteksi anda telah login di perangkat terbaru";
                        $data['data']    = null;
                        return $response->withJson($data, 200);
                    } else {
                        return $response = $next($request, $response);
                    }
                } else {
                    $data['status']  = "failed";
                    $data['message'] = "maaf user anda tidak ditemukan";
                    $data['data']    = null;
                    return $response->withJson($data, 200);
                }
            } catch (Throwable $exception){
                try {
                    $header = $request->getHeaders();
                    $loging = "INSERT INTO error_log (error_message, createdby) VALUES (:error_message, :createdby)";
                    $stmt = $this->db->prepare($loging);
                    $data = [
                        ":error_message" => $exception,
                        ":createdby"     => isset($header["HTTP_USERNAME"][0]) ? $header["HTTP_USERNAME"][0] : "User Not Found",
                    ];

                    $stmt->execute($data);
                } catch (\Throwable $th) {
                    //ignored
                }

                $data['status']  = "failed";
                $data['message'] = "maaf tidak dapat memproses data anda ada permasalahan disisi server, harap hubungi administrator";
                $data['data']    = null;
                return $response->withJson($data, 200);
            }
        };
        return $middleware;
    }

    protected function encode_str($value, $gembok = '') {
        $skey = (trim($gembok) == '' ? '1z2ben45tyu56yup' : $gembok);
        if (!$value) {
            return false;
        }
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim(self::safe_b64encode($crypttext));
    }

    protected function decode_str($value, $gembok = '') {
        $skey = (trim($gembok) == '' ? '1z2ben45tyu56yup' : $gembok);
        if (!$value) {
            return false;
        }
        $crypttext = self::safe_b64decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

    private function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    private function safe_b64decode($string) {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}