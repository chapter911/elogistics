<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Attb extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    function execQuery(){
        $this->db->select('*');
        $this->db->from('vw_attb');
        if($this->input->post('unit', true) != '*'){
            $this->db->where('unit', $this->input->post('unit', true));
        }
        if($this->input->post('no_attb', true) != ''){
            $this->db->where('no_attb', $this->input->post('no_attb', true));
        }
        if($this->input->post('material_id', true) != '*'){
            $this->db->where('material_id', $this->input->post('material_id', true));
        }
        if($this->input->post('location', true) != '*'){
            $this->db->where('location', $this->input->post('location', true));
        }
    }

    function getData(){
        $this->execQuery();
        $this->db->order_by("no_attb", "asc");
        return $this->db->get()->result();
    }

    function getTotalFiltered(){
        $this->execQuery();
        return $this->db->count_all_results();
    }

    function getTotal(){
        $this->db->from('vw_attb');
        return $this->db->count_all_results();
    }

    function exportData(){
        $this->execQuery();
        $this->db->order_by("id", "desc");
        return $this->db->get()->result();
    }
}