<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_AllFunction extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function CountAll($table)
    {
        return $this->db->count_all_results($table);
    }

    public function Count($table, $where)
    {
        $this->db->where($where);
        return $this->db->count_all_results($table);
    }

    public function Delete($table, $where)
    {
        $this->db->where($where);
        return $this->db->delete($table);
    }

    public function Get($table)
    {
        return $this->db->get($table)->result();
    }

    public function GetLimit($table, $limit)
    {
        $this->db->limit($limit);
        return $this->db->get($table)->result();
    }

    public function GroupBy($select, $table)
    {
        $this->db->select($select);
        $this->db->from($table);
        $this->db->group_by($select);
        return $this->db->get()->result();
    }

    public function Insert($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    public function InsertBatch($table, $data)
    {
        return $this->db->insert_batch($table, $data);
    }

    public function InsertGetId($table, $data)
    {
        $this->db->insert($table, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function Join($table1, $table2, $ontable1, $ontable2)
    {
        $this->db->select("*");
        $this->db->from($table1);
        $this->db->join($table2, $table1 . "." . $ontable1 . "=" . $table2 . "." .  $ontable2);
        return $this->db->get()->result();
    }

    public function JoinWhere($table1, $table2, $on, $where)
    {
        $this->db->select("*");
        $this->db->from($table1);
        $this->db->join($table2, $table1 . "." . $on . "=" . $table2 . "." .  $on);
        $this->db->where($where);
        return $this->db->get()->result();
    }

    public function Menu($table)
    {
        $where = $this->session->userdata('group_id');
        $this->db->select('*');
        $this->db->from('menu_akses');
        $this->db->join($table, 'menu_akses.menu_id = '.$table.'.id');
        $this->db->where('menu_akses.group_id', $where);
        $this->db->order_by($table . '.ordering');
        return $this->db->get()->result();
    }

    public function CekAkses($group_id, $link)
    {
        $query = "WITH menu AS (
                        SELECT id, link FROM menu_lv2
                        UNION
                        SELECT id, link FROM menu_lv3
                    )
                    SELECT id, link,
                        CASE WHEN menu_akses.menu_id IS NULL THEN 0 ELSE 1 END AS akses,
                        CASE WHEN menu_akses.FiturAdd IS NULL THEN 0 ELSE menu_akses.FiturAdd END AS FiturAdd,
                        CASE WHEN menu_akses.FiturEdit IS NULL THEN 0 ELSE menu_akses.FiturEdit END AS FiturEdit,
                        CASE WHEN menu_akses.FiturDelete IS NULL THEN 0 ELSE menu_akses.FiturDelete END AS FiturDelete,
                        CASE WHEN menu_akses.FiturExport IS NULL THEN 0 ELSE menu_akses.FiturExport END AS FiturExport,
                        CASE WHEN menu_akses.FiturImport IS NULL THEN 0 ELSE menu_akses.FiturImport END AS FiturImport
                    FROM menu
                    LEFT JOIN menu_akses ON menu.id = menu_akses.menu_id
                    AND menu_akses.group_id = $group_id
                    WHERE link = '$link'";
        return $this->db->query($query)->result();
    }

    public function CekAkses2($group_id, $link)
    {
        $query = "WITH menu AS (
                        SELECT id, link FROM menu_lv2
                        UNION
                        SELECT id, link FROM menu_lv3
                    )
                    SELECT id, link,
                        CASE WHEN (
                            SELECT menu_id
                            FROM menu_akses
                            WHERE menu_id = menu.id
                            AND group_id = $group_id)
                        IS NOT NULL THEN 1 ELSE 0 END AS akses
                    FROM menu
                    WHERE link = '$link'";
        return $this->db->query($query)->result();
    }

    public function MenuAkses($group_id, $menu_lv)
    {
        $query = "SELECT $menu_lv.*,
            CASE WHEN menu_a.group_id IS NOT NULL THEN True ELSE False END AS active,
            CASE WHEN menu_a.FiturAdd IS NOT NULL THEN menu_a.FiturAdd ELSE False END AS FiturAdd,
            CASE WHEN menu_a.FiturEdit IS NOT NULL THEN menu_a.FiturEdit ELSE False END AS FiturEdit,
            CASE WHEN menu_a.FiturDelete IS NOT NULL THEN menu_a.FiturDelete ELSE False END AS FiturDelete,
            CASE WHEN menu_a.FiturExport IS NOT NULL THEN menu_a.FiturExport ELSE False END AS FiturExport,
            CASE WHEN menu_a.FiturImport IS NOT NULL THEN menu_a.FiturImport ELSE False END AS FiturImport
            FROM $menu_lv
            LEFT JOIN
            (SELECT * FROM menu_akses WHERE group_id = '$group_id') AS menu_a
            ON $menu_lv.id = menu_a.menu_id
            ORDER BY $menu_lv.ordering";
        return $this->db->query($query)->result();
    }

    public function Replace($table, $data)
    {
        return $this->db->replace($table, $data);
    }

    public function Sum($table, $fields)
    {
        $this->db->select_sum($fields);
        return $this->db->get($table)->result();
    }

    public function Truncate($table)
    {
        return $this->db->truncate($table);
    }

    public function Update($table, $data, $where)
    {
        $this->db->where($where);
        return $this->db->update($table, $data);
    }

    public function Where($table, $where)
    {
        $this->db->where($where);
        return $this->db->get($table)->result();
    }

    public function WhereIn($table, $pkey, $where)
    {
        $this->db->where_in($pkey, $where);
        return $this->db->get($table)->result();
    }

    public function WhereLimit($table, $where, $limit)
    {
        $this->db->where($where);
        $this->db->limit($limit);
        $this->db->order_by('id', 'DESC');
        return $this->db->get($table)->result();
    }

    public function CustomQuery($query)
    {
        return $this->db->query($query)->result();
    }

    public function CustomQueryArray($query)
    {
        return $this->db->query($query)->result_array();
    }

    public function CustomQueryWithoutResult($query)
    {
        $this->db->query($query);
        return true;
    }

    public function CallSP($query)
    {
        return $this->db->query($query)->next_result();
    }
}