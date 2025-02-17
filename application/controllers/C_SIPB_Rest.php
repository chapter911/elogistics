<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class C_SIPB_Rest extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    function index_get() {
        if($this->get('kr') == "") {
            $data['status'] = "failed";
            $data['message'] = "no kr is not found";
            $data['data'] = "no kr is not found";
            $this->response($data, 404);
        } else {
            $header = $this->db->query("SELECT * FROM vw_sipb_hdr WHERE no_spj = '".$this->get('kr')."'")->result();
            if(count($header) > 0) {
                $data['status'] = "success";
                $data['message'] = "data found";
                $data['header'] = $header;
                for($i = 0; $i < count($header); $i++) {
                    $h = $header[$i];
                    $detail = $this->db->query("SELECT * FROM vw_sipb_dtl WHERE no_sipb = '".$h->no_sipb."'")->result();
                    $data['header'][$i]['detail'] = $detail;
                }
                $this->response($data, 200);
            } else {
                $data['status'] = "failed";
                $data['message'] = "data not found";
                $data['data'] = "data not found";
                $this->response($data, 404);
            }
        }
    }
}