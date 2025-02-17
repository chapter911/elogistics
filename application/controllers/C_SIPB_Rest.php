<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class C_SIPB_Rest extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    function getKR() {
        $this->db->where('no_spj', $this->get('kr'));
        $data = $this->db->get('trn_sipb_hdr')->result();
        $this->response($data, 200);
    }
}