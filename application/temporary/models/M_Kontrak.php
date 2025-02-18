<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Kontrak extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    function execQueryAlokasiPemasaran(){
        $this->db->select('material_id,
            nomor_khs,
            vendor,
            kategori,
            material,
            satuan,
            alokasi,
            SUM(jumlah) AS jumlah'
        );
        $this->db->from('vw_kontrak_vendor');
        if($this->input->post('nomor_khs', true) != ''){
            $this->db->like('nomor_khs', $this->input->post('nomor_khs', true));
        }
        if($this->input->post('kategori', true) != '*'){
            $this->db->where('kategori', $this->input->post('kategori', true));
        }
        if($this->input->post('material_id', true) != '*'){
            $this->db->where('material_id', $this->input->post('material_id', true));
        }
        if($this->input->post('id_vendor', true) != '*'){
            $this->db->where('id_vendor', $this->input->post('id_vendor', true));
        }
        $this->db->group_by('nomor_khs, material_id');
    }

    function getAlokasiPemasaran(){
        $this->execQueryAlokasiPemasaran();
        return $this->db->get()->result();
    }

    function getTotalFiltered(){
        $this->execQueryAlokasiPemasaran();
        return $this->db->count_all_results();
    }

    function getTotal(){
        $this->db->from('vw_kontrak_vendor');
        return $this->db->count_all_results();
    }

    function exportData(){
        $this->execQueryAlokasiPemasaran();
        return $this->db->get()->result();
    }
}