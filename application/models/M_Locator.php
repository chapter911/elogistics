<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Locator extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    function execLayout(){
        if($this->input->post('unit', true) != '*'){
            $this->db->where('unit', $this->input->post('unit', true));
        }
        if($this->input->post('gudang', true) != '*'){
            $this->db->where('id_gudang', $this->input->post('gudang', true));
        }
        if($this->input->post('no_gudang', true) != '*'){
            $this->db->where('no_gudang', $this->input->post('no_gudang', true));
        }
        if($this->input->post('rak', true) != '*'){
            $this->db->where('rak', $this->input->post('rak', true));
        }
        $this->db->from('vw_layout_rak');
    }

    function getDataLayout(){
        $this->execLayout();
        return $this->db->get()->result();
    }

    function getTotalFilteredLayout(){
        $this->execLayout();
        return $this->db->count_all_results();
    }

    function getTotalLayout(){
        $this->db->from('vw_layout_rak');
        return $this->db->count_all_results();
    }
}