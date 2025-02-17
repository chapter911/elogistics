<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 * @property Uri $uri
 */

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
            $header = $this->m_allfunction->CustomQuery("SELECT * FROM trn_sipb_hdr WHERE no_spj = '".$this->get('kr')."'");
            if(count($header) > 0) {
                $data['status'] = "success";
                $data['message'] = "data found";
                $data['data'] = $header;
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