<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Permohonan extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    function execQuery(){
        $this->db->select('*');
        $this->db->from('vw_permohonan');
        if($this->input->post('unit_id', true) != '' && $this->input->post('unit_id', true) != '*'){
            $this->db->where('unit_id', $this->input->post('unit_id', true));
        }
        if($this->input->post('no_pr', true) != ''){
            $this->db->where('no_pr', $this->input->post('no_pr', true));
        }
        if($this->input->post('basket_id', true) != '' && $this->input->post('basket_id', true) != '*'){
            $this->db->where('basket_id', $this->input->post('basket_id', true));
        }
        if($this->input->post('pekerjaan', true) != '' && $this->input->post('pekerjaan', true) != '*'){
            $this->db->where('pekerjaan', $this->input->post('pekerjaan', true));
        }
        if($this->input->post('status', true) != '*'){
            $this->db->where('status', $this->input->post('status', true));
        }
        if($this->session->userdata("jabatan_id") == 6){
            $this->db->where('basket_id', 4);
        }
    }

    function getData(){
        $this->execQuery();
        if($this->input->post('length', true) != -1) {
            $this->db->limit($this->input->post('length', true), $this->input->post('start', true));
        }
        $this->db->order_by("id", "desc");
        return $this->db->get()->result();
    }

    function getTotalFiltered(){
        $this->execQuery();
        return $this->db->count_all_results();
    }

    function getTotal(){
        $this->db->from('vw_permohonan');
        return $this->db->count_all_results();
    }

    function exportData(){
        $this->execQuery();
        $this->db->order_by("id", "desc");
        return $this->db->get()->result();
    }

    function execQuerySTO(){
        $this->db->select('*');
        $this->db->from('vw_permohonan');
        if($this->input->post('unit_id', true) != '' && $this->input->post('unit_id', true) != '*'){
            $this->db->where('unit_id', $this->input->post('unit_id', true));
        }
        if($this->input->post('no_pr', true) != ''){
            $this->db->where('no_pr', $this->input->post('no_pr', true));
        }
        if($this->input->post('no_sto', true) != ''){
            $this->db->where('no_sto', $this->input->post('no_sto', true));
        }
        $this->db->where('status', 'DISETUJUI');
    }

    function getDataSTO(){
        $this->execQuerySTO();
        $this->db->order_by('is_sto_released ASC, tanggal_pr DESC');
		$this->db->limit($this->input->post('length', true), $this->input->post('start', true));
        return $this->db->get()->result();
    }

    function getTotalFilteredSTO(){
        $this->execQuerySTO();
        return $this->db->count_all_results();
    }

    function getTotalSTO(){
        $this->db->from('vw_permohonan');
        $this->db->where('status', 'DISETUJUI');
        return $this->db->count_all_results();
    }

    function exportDataSTO(){
        $this->execQuerySTO();
        $this->db->order_by('is_sto_released ASC, tanggal_pr DESC');
        return $this->db->get()->result();
    }
}