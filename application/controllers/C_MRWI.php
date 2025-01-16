<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/**
 * @property M_AllFunction $M_AllFunction
 * @property Session $session
 * @property Template $template
 */

class C_MRWI extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('username')) {
            redirect('C_Login');
        } else {
            $user = $this->M_AllFunction->Where('mst_user', "username = '" . $this->session->userdata('username') . "' AND is_active = 1");
            if (count($user) == 0) {
                $this->session->sess_destroy();
                $this->session->set_flashdata('message', 'User Di Non Aktifkan.');
                redirect("C_Login");
            }
            $controller = $this->uri->segment(1);
            if ($this->uri->segment(2) != null) {
                $controller .=  "/" . $this->uri->segment(2);
            }
            $data = $this->M_AllFunction->CekAkses($this->session->userdata('group_id'), $controller);
            if (count($data) > 0) {
                if ($data[0]->akses == "0") {
                    $this->session->set_userdata('controller', $controller);
                    redirect('C_My404/NoAccess');
                } else {
                    $this->session->set_userdata('add', $data[0]->FiturAdd);
                    $this->session->set_userdata('edit', $data[0]->FiturEdit);
                    $this->session->set_userdata('delete', $data[0]->FiturDelete);
                    $this->session->set_userdata('export', $data[0]->FiturExport);
                    $this->session->set_userdata('import', $data[0]->FiturImport);
                }
            }
        }

        $this->load->library('upload');
        $this->load->helper('url');
        $this->load->helper('form');
    }

    function index(){
        $this->template->display("mrwi/index");
    }

    function get_data()
    {
        $unit = $this->input->get("unit");
        $tw = $this->input->get("tw");
        $th = $this->input->get("th");
        $query = "with data_hdr as ( SELECT * FROM trn_mrwi where unit = '$unit' and triwulan = '$tw' and tahun = '$th' ),
                    data_dtl as ( SELECT a.*, c.level FROM trn_mrwi_dtl a INNER JOIN data_hdr b on a.hdr_id = b.id INNER JOIN mst_mrwi_kriteria_dtl_case c on a.kriteria_unit = c.id where a.status = 'Approved')
                    SELECT a.kriteria,  AVG(IFNULL(c.level, 0)) as nilai FROM mst_mrwi_kriteria_hdr a INNER JOIN mst_mrwi_kriteria_dtl b on a.id = b.hdr_id left JOIN data_dtl c on b.nomor = c.kriteria_manajemen GROUP BY a.id, a.kriteria";

        $data = $this->M_AllFunction->CustomQueryArray($query);

        echo json_encode($data);
    }

    function get_data_chart_dtl()
    {
        $unit = $this->input->get("unit");
        $tw = $this->input->get("tw");
        $th = $this->input->get("th");
        $query = "with data_hdr as ( SELECT * FROM trn_mrwi where unit = '$unit' and triwulan = '$tw' and tahun = '$th' ), 
                    data_dtl as ( SELECT a.*, c.level FROM trn_mrwi_dtl a INNER JOIN data_hdr b on a.hdr_id = b.id INNER JOIN mst_mrwi_kriteria_dtl_case c on a.kriteria_unit = c.id where a.status = 'Approved')
                    SELECT Concat(a.id,'-', b.kriteria) as kriteria,  AVG(IFNULL(c.level, 0)) as nilai FROM mst_mrwi_kriteria_hdr a INNER JOIN mst_mrwi_kriteria_dtl b on a.id = b.hdr_id left JOIN data_dtl c on b.nomor = c.kriteria_manajemen GROUP BY b.nomor, b.kriteria";

        $data = $this->M_AllFunction->CustomQueryArray($query);

        echo json_encode($data);
    }

    function get_data_hdr(){
        $tw = $this->input->get("tw");
        $th = $this->input->get("th");

        $query = "with dt_header as (SELECT id, unit, STATUS from trn_mrwi WHERE triwulan = '$tw' and tahun = '$th')
                    SELECT a.unit , c.id as hdr_kriteria, sum(d.LEVEL) as nilai_unit
                    FROM dt_header a INNER JOIN trn_mrwi_dtl b on a.id = b.hdr_id
                    LEFT JOIN vw_mst_mrwi c on b.kriteria_manajemen = c.nomor and b.status = 'Approved'
                    LEFT JOIN mst_mrwi_kriteria_dtl_case d on b.kriteria_unit_approve = d.id
                    where c.id is not null
                    GROUP BY  a.unit , c.id";
        $data_nilai = $this->M_AllFunction->CustomQuery($query);

        $query_unit = "SELECT * FROM mst_unit where id != 5400";
        $data_unit = $this->M_AllFunction->CustomQuery($query_unit);

        $query_mrwi = "SELECT * FROM vw_mst_mrwi_hdr";
        $data_mrwi = $this->M_AllFunction->CustomQuery($query_mrwi);

        $nilai = array();
        foreach($data_nilai as $r){
            $nilai[html_escape($r->unit)][html_escape($r->hdr_kriteria)] = html_escape($r->nilai_unit);
        }

        $hasil = array();
        $hasil_tem = array();
        foreach($data_unit as $r){
            foreach($data_mrwi as $l){
                $hasil[html_escape($r->id)][html_escape($l->id)] = isset($nilai[html_escape($r->id)][html_escape($l->id)]) ? round($nilai[html_escape($r->id)][html_escape($l->id)] / html_escape($l->jlh_row), 2) : 0;
                $hasil_tem[html_escape($l->id)] = isset($hasil_tem[html_escape($l->id)]) ? $hasil_tem[html_escape($l->id)] + $hasil[html_escape($r->id)][html_escape($l->id)] : $hasil[html_escape($r->id)][html_escape($l->id)];
            }
        }

        $n = array();
        foreach($data_mrwi as $r){
            $n1 = array();
            $n1["kriteria"] = html_escape($r->kriteria);
            $n1["nilai"] = round($hasil_tem[html_escape($r->id)] / count($data_unit),2);

            array_push($n, $n1);
        }


        $hasil["total"] = $hasil_tem;

        $data["lst_unit"] = $data_unit;
        $data["lst_mrwi"] = $data_mrwi;
        $data["lst_nilai"] = $hasil;
        $data["tw"] = $tw;
        $data["th"] = $th;

        $data["nilai"] = json_encode($n);

        $this->load->view("mrwi/index_header", $data);
    }

    function get_data_dtl()
    {
        $unit = $this->input->get("unit");
        $tw = $this->input->get("tw");
        $th = $this->input->get("th");
        $hdr = $this->db->query("SELECT * FROM trn_mrwi where unit = '$unit' and triwulan = '$tw' and tahun = '$th' ")->row();
        $units = $this->db->query("SELECT * FROM mst_unit where id = '$unit'")->row();
        $data['hdr'] = "Belum Ada Aktifitas";
        $id = 0;
        if (!empty($hdr)) {
            $id = $hdr->id;

            $data['hdr'] = $hdr->is_completed == 0 ? "Draft" : "Submitted";

        }
        $data['unit'] = $units->name;
        $data['unit_id'] = $unit;

        $query = "with data_dtl as (SELECT * from trn_mrwi_dtl where hdr_id = '$id')
            SELECT a.nomor, a.kriteria, a.sub_kriteria, a.jlh_row, a.indikasi, b.kriteria_unit, c.level, c.kriteria as kriteria_unit_name , b.status, b.remark
            FROM vw_mst_mrwi a LEFT JOIN data_dtl b on a.nomor = b.kriteria_manajemen LEFT JOIN mst_mrwi_kriteria_dtl_case c on b.kriteria_unit_approve = c.id";

        $data['lst_dtl'] =  $this->M_AllFunction->CustomQuery($query);

        $query2 = "with data_lst_dtl as (SELECT * FROM trn_mrwi_evidence where dtl_id in (SELECT id from trn_mrwi_dtl where hdr_id = '$id' )),
                        data_dtl as (SELECT * from trn_mrwi_dtl where hdr_id = '$id')
                        SELECT a.*, b.uploads FROM mst_mrwi_kriteria_evidence a LEFT JOIN data_lst_dtl b on a.id = b.evidence_id INNER JOIN data_dtl c on a.dtl_case_id = c.kriteria_unit";

        $data['lst_sub_dtl'] =  $this->M_AllFunction->CustomQuery($query2);

        $this->load->view('mrwi/index_ajax', $data);
    }

    function Maturity()
    {
        $data['header'] = $this->M_AllFunction->Get('mst_mrwi_kriteria_hdr');
        $data['detail'] = $this->M_AllFunction->Get('mst_mrwi_kriteria_dtl');
        $this->template->display('mrwi/maturity', $data);
    }

    function Maturitynew()
    {
        $data['header'] = $this->M_AllFunction->Get('mst_mrwi_kriteria_hdr');
        $data['detail'] = $this->M_AllFunction->Get('mst_mrwi_kriteria_dtl');
        $this->template->display('mrwi/maturity_new', $data);
    }

    function MaturitySetHdr()
    {
        $tw = $this->input->get("triwulan");
        $th = $this->input->get("tahun");
        $unit = $this->session->userdata("unit_id");

        $query = "SELECT * FROM trn_mrwi WHERE triwulan = '$tw' and tahun = '$th' and unit = '$unit'";

        $a = $this->M_AllFunction->CustomQuery($query);

        if (!empty($a)) {
            if ($a[0]->is_completed == 0)
                $st = "Draft";
            else
                $st = "Completed";

            echo json_encode(array("status" => "success", "id" => $a[0]->id, "status_hdr" => $st, "remark" => $a[0]->remark));
        } else {
            $data = array(
                "unit"             => $unit,
                "triwulan"        => $tw,
                "tahun"          => $th,
                "created_by"        => $this->session->userdata('username')
            );

            $id = $this->M_AllFunction->InsertGetId('trn_mrwi', $data);

            echo json_encode(array("status" => "success", "id" => $id, "status_hdr" => "Draft", "remark" => ""));
        }
    }

    function MaturityGetDtl()
    {
        $id = $this->input->get("id");
        $query = "SELECT * FROM mst_mrwi_kriteria_dtl WHERE hdr_id = '$id'";

        $data['dtl'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('mrwi/matuary_ajax', $data);
    }


    function MaturityGetDtlIsi()
    {
        $id = $this->input->get("id");
        $hdr = $this->input->get("hdr");
        $query = "WITH pilihan AS ( SELECT * FROM trn_mrwi_dtl WHERE kriteria_manajemen = '$id' AND hdr_id = '$hdr' ) SELECT
                    a.*,  a1.indikasi as hdr_kriteria,
                    CASE
                            WHEN b.id IS NULL THEN
                        FALSE ELSE TRUE 
                        END AS pilih 
                    FROM
                        mst_mrwi_kriteria_dtl_case a
                        INNER JOIN mst_mrwi_kriteria_dtl a1 on a1.nomor = a.dtl_id
                        LEFT JOIN pilihan b ON a.dtl_id = b.kriteria_manajemen AND a.id = b.kriteria_unit 
                    WHERE
                        dtl_id = '$id' ";

        $data['subdtl'] = $this->M_AllFunction->CustomQuery($query);

        $data['dtl_id'] = $this->db->query("SELECT * FROM trn_mrwi_dtl WHERE kriteria_manajemen = '$id' AND hdr_id = '$hdr'")->row();
        $this->load->view('mrwi/maturity_ajax_dtl', $data);
    }

    function MaturitySaveDtl()
    {
        $id = $this->input->post("id");
        $hdr_id = $this->input->post("hdr_id");
        $kriteria_manajemen = $this->input->post("kriteria_manajemen");
        $kriteria_unit = $this->input->post("kriteria_unit");
        $created_by = $this->session->userdata('username');
        $created_date = date('Y-m-d');
        $query = "INSERT INTO trn_mrwi_dtl (id, hdr_id, kriteria_manajemen, kriteria_unit, created_by, created_date)
                    VALUES ('$id', '$hdr_id', '$kriteria_manajemen', '$kriteria_unit', '$created_by','$created_date')
                    ON DUPLICATE KEY UPDATE
                    kriteria_manajemen = VALUES(kriteria_manajemen),
                    kriteria_unit = VALUES(kriteria_unit),
                    created_by = VALUES(created_by),
                    created_date = VALUES(created_date);
                    ";


        $this->db->query($query);

        $query = "
                with datas  as (SELECT * from trn_mrwi_evidence where dtl_id = '$id')
                SELECT a.*, b.uploads, IFNULL(b.id, 0) as id_trn from mst_mrwi_kriteria_evidence a left join datas b on a.id = b.evidence_id  where dtl_case_id = '$kriteria_unit'  ";

        $data['data_evidence'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('mrwi/maturity_ajax_evidence', $data);
    }

    function MaturityGetEvidence()
    {
        $kriteria_unit = $this->input->post("kriteria_unit");
        $id = $this->input->post("id");

        $query = "
                with datas  as (SELECT * from trn_mrwi_evidence where dtl_id = '$id')
                SELECT a.*, b.uploads, IFNULL(b.id, 0) as id_trn from mst_mrwi_kriteria_evidence a left join datas b on a.id = b.evidence_id  where dtl_case_id = '$kriteria_unit' ";


        $data['data_evidence'] = $this->M_AllFunction->CustomQuery($query);
        $this->load->view('mrwi/maturity_ajax_evidence', $data);
    }

    function MaturitySaveDoc()
    {
        try {
            // Jika upload berhasil, dapatkan informasi file yang diunggah
            $go = $this->input->post("id_hdrs");
            $data_simpan = array(
                "id" => $this->input->post("id_hdrs"),
                "dtl_id" => $this->input->post("dtl_id"),
                "evidence_id" => $this->input->post("evidence_id"),
                "created_date" => date('Y-m-d'),
                "created_by" => $this->session->userdata("username"),
            );

            $config['upload_path'] = './data_uploads/mrwi'; // Tentukan direktori penyimpanan gambar
            $config['allowed_types'] = 'pdf|jpeg|jpg|xls|xlsx|png'; // Jenis file yang diizinkan
            $config['max_size'] = 1024; // Batasan ukuran file (dalam kilobyte)
            // $new_name =   
            $config['file_name'] = "mrwi-" . bin2hex(random_bytes(24));

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('file')) {
                // Jika upload gagal, tampilkan pesan error
                $error = $this->upload->display_errors();
                // echo $error;
                // echo json_encode(array('status' => 'failed', 'msg' => 'Error simpan cover : ' . $error . ''));
            } else {
                // Jika upload berhasil, dapatkan informasi file yang diunggah
                $data = $this->upload->data();
                $data_simpan["uploads"] = $data['file_name'];
            }

            if ($go == 0) {
                $this->db->insert('trn_mrwi_evidence', $data_simpan);
            } else {
                $this->db->where('id', $go);
                $this->db->update('trn_mrwi_evidence', $data_simpan);
            }

            echo json_encode(array('status' => 'success', 'msg' => 'Berhasil Upload Dokumen'));

            // $data = $this->M_Honda->save($data_simpan);
            // echo json_encode($data);    

        } catch (\Exception $ex) {
            echo json_encode(array('status' => 'failed', 'msg' => $ex->getMessage()));
        }
    }

    function complete()
    {
        $id = $this->input->get('id');

        $query = "with data_isi as (SELECT * FROM trn_mrwi_dtl where hdr_id = $id )
        SELECT count(a.nomor) as total FROM mst_mrwi_kriteria_dtl a left join data_isi b on a.nomor = b.kriteria_manajemen WHERE b.hdr_id is null";
        $data_hasil = $this->db->query($query)->row();

        if ($data_hasil->total > 0) {
            echo json_encode(array('status' => 'error', 'msg' => 'TOLONG LENGKAPI DAHULU SEMUA DATA MRWI !'));
        } else {



            $query = "UPDATE trn_mrwi SET is_completed = 1 Where id = $id";
            $this->db->query($query);

            $query = "UPDATE trn_mrwi_dtl SET status = 'Submitted' where  status = 'Rejected' and hdr_id = $id";
            $this->db->query($query);

            echo json_encode(array('status' => 'success', 'msg' => 'Dokumen Berhasil Di ajukan !'));
        }
    }

    function Approval()
    {
        $this->template->display("mrwi/approval");
    }

    function CariDataMRWI()
    {
        $tw = $this->input->get("triwulan");
        $th = $this->input->get("tahun");
        // $unit = $this->session->userdata("unit_id");

        $query = "SELECT a.*, b.name FROM trn_mrwi a INNER JOIN mst_unit b on a.unit = b.id WHERE a.triwulan = '$tw' and a.tahun = '$th' ";

        $data['lst_mrwi'] = $this->M_AllFunction->CustomQuery($query);

        $this->load->view("mrwi/approval_lst", $data);
    }

    function Tampil_detailmrwi()
    {
        $id = $this->input->get('id');

        $query = "with data_dtl as (SELECT * from trn_mrwi_dtl where hdr_id = '$id')
            SELECT a.nomor, a.kriteria, a.indikasi, b.kriteria_unit, c.level, c.kriteria as kriteria_unit_name , b.status, b.remark, b.id, b.kriteria_unit_approve
	            FROM mst_mrwi_kriteria_dtl a LEFT JOIN data_dtl b on a.nomor = b.kriteria_manajemen LEFT JOIN mst_mrwi_kriteria_dtl_case c on b.kriteria_unit = c.id;";

        $data['lst_dtl'] =  $this->M_AllFunction->CustomQuery($query);

        $query2 = "with data_lst_dtl as (SELECT * FROM trn_mrwi_evidence where dtl_id in (SELECT id from trn_mrwi_dtl where hdr_id = '$id' )),
                        data_dtl as (SELECT * from trn_mrwi_dtl where hdr_id = '$id')
                        SELECT a.*, b.uploads FROM mst_mrwi_kriteria_evidence a LEFT JOIN data_lst_dtl b on a.id = b.evidence_id INNER JOIN data_dtl c on a.dtl_case_id = c.kriteria_unit";

        $data['lst_sub_dtl'] =  $this->M_AllFunction->CustomQuery($query2);

        $this->load->view("mrwi/approval_lst_dtl", $data);
    }

    function approval_save()
    {
        $id = $this->input->post("id_hdrs");
        $status = $this->input->post("pilihan");
        $remark = $this->input->post("alasan");
        $id_approve = $this->input->post("id_approve");
        $kriteria = $this->input->post("kriteria_unit_approve");

        for ($i = 0; $i < count($status); $i++) {
            $query = "UPDATE trn_mrwi_dtl set kriteria_unit_approve = '$kriteria[$i]', status = '$status[$i]', remark = '$remark[$i]' where id = '$id_approve[$i]'";
            $this->db->query($query);

            if ($status[$i] == "Rejected") {
                $query = "UPDATE trn_mrwi SET is_completed = 0, remark = 'Ada KRITERIA YANG DI REJECT , MOHON SEGERA DI UPDATE !' where id = $id";
                $this->db->query($query);
            }
        }

        echo json_encode(array('status' => 'success', 'msg' => 'Berhasil Melakukan Tindakan !'));
    }

    function check_mrwi_hdr()
    {
        $id = $this->input->get('id');
        $query = "with trn_dtl as (SELECT * from trn_mrwi_dtl where hdr_id = '$id'), 
                            trn_sub_dtl as (SELECT * from trn_mrwi_evidence where dtl_id in (SELECT id from trn_dtl) )
                    SELECT a.*, b.kriteria_unit, b.`status`, c.evidence, d.uploads
                        FROM vw_mst_mrwi a LEFT JOIN trn_dtl b on a.nomor = b.kriteria_manajemen 
                                    LEFT JOIN mst_mrwi_kriteria_evidence c on b.kriteria_unit = c.dtl_case_id 
                                    LEFT JOIN trn_sub_dtl d on c.id = d.evidence_id
                                    ORDER BY a.id";

        $data = $this->db->query($query)->result_array();

        $hdr_id = $this->db->query("SELECT * from mst_mrwi_kriteria_hdr ")->result();

        foreach ($hdr_id as $l) {
            $id_to_find = html_escape($l->id);
            $result = array_filter($data, function ($item) use ($id_to_find) {
                return $item["id"] === $id_to_find;
            });

            $hdr = true;
            $back = "txt-primary";
            foreach ($result as $r) {
                if (empty($r['kriteria_unit'])) {
                    $hdr = false;
                    $back = "txt-danger";
                    break;
                } else {
                    if (!empty(html_escape($r['evidence']))) {
                        if (empty(html_escape($r['uploads']))) {
                            $hdr = false;
                            $back = "txt-danger";
                            break;
                        }
                    }

                    if(html_escape($r['status']) == 'Rejected'){
                        $hdr = false;
                        $back = "txt-danger";
                        break;
                    }
                }
            }

            $hasil[] = array("id" => html_escape($l->id), "selesai" => $hdr, "background" => $back);
        }

        echo json_encode($hasil);
    }

    function check_mrwi_dtl()
    {
        $id = $this->input->get('id');
        $kriteria = $this->input->get('kr');

        $query = "with trn_dtl as (SELECT * from trn_mrwi_dtl where hdr_id = '$id'), 
                            trn_sub_dtl as (SELECT * from trn_mrwi_evidence where dtl_id in (SELECT id from trn_dtl) )
                    SELECT a.*, b.kriteria_unit, b.`status`, c.evidence, d.uploads
                        FROM vw_mst_mrwi a LEFT JOIN trn_dtl b on a.nomor = b.kriteria_manajemen 
                                    LEFT JOIN mst_mrwi_kriteria_evidence c on b.kriteria_unit = c.dtl_case_id 
                                    LEFT JOIN trn_sub_dtl d on c.id = d.evidence_id where a.id = $kriteria
                                    ORDER BY a.id, a.nomor";

        $data = $this->db->query($query)->result_array();

        $hdr_id = $this->db->query("SELECT * from mst_mrwi_kriteria_dtl where hdr_id = $kriteria ")->result();

        
        foreach ($hdr_id as $l) {
            $id_to_find = html_escape($l->nomor);
            $result = array_filter($data, function ($item) use ($id_to_find) {
                return $item["nomor"] === $id_to_find;
            });

            $hdr = true;
            $back = "txt-primary";
            foreach ($result as $r) {
                if (empty(html_escape($r['kriteria_unit']))) {
                    $hdr = false;
                    $back = "txt-danger";
                    break;
                } else {
                    if (!empty(html_escape($r['evidence']))) {
                        if (empty(html_escape($r['uploads']))) {
                            $hdr = false;
                            $back = "txt-danger";
                            break;
                        }
                    }

                    if(html_escape($r['status']) == 'Rejected'){
                        $hdr = false;
                        $back = "txt-danger";
                        break;
                    }
                }
            }
            $parts = explode('.', html_escape($l->nomor));
            $lastPart = end($parts);

            $hasil[] = array("id" => $lastPart, "selesai" => $hdr, "background" => $back);
        }

        echo json_encode($hasil);
    }
}
