<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Mutasi extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    function execQuery(){
        $this->db->select('*');
        $this->db->from('vw_last_mutasi_material');
        if($this->input->post('unit', true) != '*'){
            $this->db->where('unit', $this->input->post('unit', true));
        }
        if($this->input->post('material_id', true) != '*'){
            $this->db->where('material_id', $this->input->post('material_id', true));
        }
        $this->db->where('persediaan_akhir >', 0);
    }

    function getData(){
        $this->execQuery();
        if($this->input->post('length', true) != -1) {
            $this->db->limit($this->input->post('length', true), $this->input->post('start', true));
        }
        return $this->db->get()->result();
    }

    function getTotalFiltered(){
        $this->execQuery();
        return $this->db->count_all_results();
    }

    function getTotal(){
        $this->db->from('vw_mutasi_material');
        return $this->db->count_all_results();
    }

    function exportData(){
        $this->execQuery();
        return $this->db->get()->result();
    }
}