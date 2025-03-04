<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_SIPB extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    function execQuery(){
        $this->db->select('*');
        $this->db->from('vw_sipb_hdr');
        if($this->input->post('unit_asal', true) != '*'){
            $this->db->where('unit_asal', $this->input->post('unit_asal', true));
        }
        if($this->input->post('unit_tujuan', true) != '*'){
            $this->db->where('unit_tujuan', $this->input->post('unit_tujuan', true));
        }
    }

    function getData(){
        $this->execQuery();
        $this->db->order_by("tanggal", "desc");
        return $this->db->get()->result();
    }

    function getTotalFiltered(){
        $this->execQuery();
        return $this->db->count_all_results();
    }

    function getTotal(){
        $this->db->from('vw_sipb_hdr');
        return $this->db->count_all_results();
    }

    function exportData(){
        $this->execQuery();
        $this->db->order_by("no_sipb", "asc");
        return $this->db->get()->result();
    }
}