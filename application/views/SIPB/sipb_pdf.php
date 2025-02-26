<?php
date_default_timezone_set('Asia/Jakarta');
setlocale(LC_TIME, 'id_ID');
class MYPDF extends Pdf{

}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->SetAutoPageBreak(false, 0);
$pdf->SetFont('helvetica', '', 11);

$pdf->AddPage('L');

$theader = "";
$material = "";
$no = 1;

$array_keterangan = array(
    'pekerjaan',
    'lokasi',
    'no_spj',
    'vendor'
);

$keterangan = "";

foreach ($array_keterangan as $a) {
    if($header[0]->{$a} != ""){
        $keterangan .= '<tr>
            <td width="34%">' . ucfirst(str_replace("_", " ", $a)) . '</td>
            <td width="3%">:</td>
            <td width="63%">' . $header[0]->{$a} . '</td>
        </tr>';
    }
}

if($header[0]->form_name == "reservasi" || $header[0]->form_name == "ago" || $header[0]->form_name == "antar_unit"){
    $theader = '<tr>
        <th align="center" style="padding: 5px;" width="5%">No.</th>
        <th align="center" style="padding: 5px;" width="26%">Nama Barang</th>
        <th align="center" style="padding: 5px;" width="10%">Normalisasi</th>
        <th align="center" style="padding: 5px;" width="8%">Satuan</th>
        <th align="center" style="padding: 5px;" width="7%">Volume</th>
        <th align="center" style="padding: 5px;" width="10%">Merk</th>
        <th align="center" style="padding: 5px;" width="9%">Nomor Seri</th>
        <th align="center" style="padding: 5px;" width="25%">Keterangan</th>
    </tr>';
    foreach ($detail as $d) {
        if($no == 1){
            $material .= '<tr>
                <td align="right">' . $no . '</td>
                <td>' . $d->material . '</td>
                <td align="center">' . $d->normalisasi . '</td>
                <td align="center">' . $d->satuan . '</td>
                <td align="center">' . $d->volume . '</td>
                <td align="center">' . $d->merk . '</td>
                <td align="center">' . $d->no_seri . '</td>
                <td rowspan="' . count($detail) . '">
                    <table>
                        ' . $keterangan . '
                    </table>
                </td>
            </tr>';
        } else {
            $material .= '<tr>
                <td align="right">' . $no . '</td>
                <td>' . $d->material . '</td>
                <td align="center">' . $d->normalisasi . '</td>
                <td align="center">' . $d->satuan . '</td>
                <td align="center">' . $d->volume . '</td>
                <td align="center">' . $d->merk . '</td>
                <td align="center">' . $d->no_seri . '</td>
            </tr>';
        }
        $no++;
    }
} else if($header[0]->form_name == "klaim_garansi_retrofit"){
    $theader = '<tr>
        <th align="center" style="padding: 5px;" width="5%">No.</th>
        <th align="center" style="padding: 5px;" width="24%">Nama Barang</th>
        <th align="center" style="padding: 5px;" width="10%">Normalisasi</th>
        <th align="center" style="padding: 5px;" width="8%">Satuan</th>
        <th align="center" style="padding: 5px;" width="9%">Banyaknya</th>
        <th align="center" style="padding: 5px;" width="8%">Merk</th>
        <th align="center" style="padding: 5px;" width="9%">Nomor Seri</th>
        <th align="center" style="padding: 5px;" width="9%">Nama Pabrikan</th>
        <th align="center" style="padding: 5px;" width="18%">Keterangan</th>
    </tr>';
    foreach ($detail as $d) {
        if($no == 1){
            $material .= '<tr>
                <td align="right">' . $no . '</td>
                <td>' . $d->material . '</td>
                <td align="center">' . $d->normalisasi . '</td>
                <td align="center">' . $d->satuan . '</td>
                <td align="center">' . $d->volume . '</td>
                <td align="center">' . $d->merk . '</td>
                <td align="center">' . $d->no_seri . '</td>
                <td align="center">' . $d->pabrikan . '</td>
                <td rowspan="' . count($detail) . '">
                    <table>
                        ' . $keterangan . '
                    </table>
                </td>
            </tr>';
        } else {
            $material .= '<tr>
                <td align="right">' . $no . '</td>
                <td>' . $d->material . '</td>
                <td align="center">' . $d->normalisasi . '</td>
                <td align="center">' . $d->satuan . '</td>
                <td align="center">' . $d->volume . '</td>
                <td align="center">' . $d->merk . '</td>
                <td align="center">' . $d->no_seri . '</td>
                <td align="center">' . $d->pabrikan . '</td>
            </tr>';
        }
        $no++;
    }
} else if($header[0]->form_name == "attb" || $header[0]->form_name == "limbah"){
    $theader = '<tr>
        <th align="center" style="padding: 5px;" width="5%">No.</th>
        <th align="center" style="padding: 5px;" width="37%">Nama Barang</th>
        <th align="center" style="padding: 5px;" width="10%">Normalisasi</th>
        <th align="center" style="padding: 5px;" width="7%">Satuan</th>
        <th align="center" style="padding: 5px;" width="9%">Banyaknya</th>
        <th align="center" style="padding: 5px;" width="32%">Keterangan</th>
    </tr>';
    foreach ($detail as $d) {
        if($no == 1){
            $material .= '<tr>
                <td align="right">' . $no . '</td>
                <td>' . $d->material . '</td>
                <td align="center">' . $d->normalisasi . '</td>
                <td align="center">' . $d->satuan . '</td>
                <td align="center">' . $d->volume . '</td>
                <td rowspan="' . count($detail) . '">
                    <table>
                        ' . $keterangan . '
                    </table>
                </td>
            </tr>';
        } else {
            $material .= '<tr>
                <td align="right">' . $no . '</td>
                <td>' . $d->material . '</td>
                <td align="center">' . $d->normalisasi . '</td>
                <td align="center">' . $d->satuan . '</td>
                <td align="center">' . $d->volume . '</td>
            </tr>';
        }
        $no++;
    }
} else if($header[0]->form_name == "manual"){
    $theader = '<tr>
        <th align="center" style="padding: 5px;" width="5%">No.</th>
        <th align="center" style="padding: 5px;" width="37%">Nama Barang</th>
        <th align="center" style="padding: 5px;" width="11%">Satuan</th>
        <th align="center" style="padding: 5px;" width="15%">Banyaknya</th>
        <th align="center" style="padding: 5px;" width="32%">Keterangan</th>
    </tr>';
    foreach ($detail as $d) {
        if($no == 1){
            $material .= '<tr>
                <td align="right">' . $no . '</td>
                <td>' . $d->material . '</td>
                <td align="center">' . $d->satuan . '</td>
                <td align="center">' . $d->volume . '</td>
                <td rowspan="' . count($detail) . '">
                    <table>
                        ' . $keterangan . '
                    </table>
                </td>
            </tr>';
        } else {
            $material .= '<tr>
                <td align="right">' . $no . '</td>
                <td>' . $d->material . '</td>
                <td align="center">' . $d->normalisasi . '</td>
                <td align="center">' . $d->satuan . '</td>
                <td align="center">' . $d->volume . '</td>
            </tr>';
        }
        $no++;
    }
}

$img_base64_encoded = "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";

$html = '
<table class="header-table" width="100%">
    <tr>
        <td width="5%" rowspan="4"><img src="@' . preg_replace('#^data:image/[^;]+;base64,#', '', $img_base64_encoded) . '"></td>
        <td width="1%" rowspan="4"></td>
        <td width="71%"><h2>PT. PLN (Persero)</h2></td>
        <td width="20%" align="center" style="border: 1px solid black;"><h2>' . $header[0]->no_sipb . '</h2></td>
    </tr>
    <tr>
        <td>UNIT INDUK DISTRIBUSI JAKARTA RAYA</td>
        <td></td>
    </tr>
    <tr>
        <td>' . $header[0]->unit_tujuan_long_name . '</td>
        <td>Kepada Yth,</td>
    </tr>
    <tr>
        <td></td>
        <td>' . strtoupper($header[0]->unit_tujuan_name) . '<br>' . strtoupper($header[0]->bidang_tujuan) . '</td>
    </tr>
</table>
<br/>
<div style="text-align: center; font-size: 18px; font-weight: bold;" margin="10px"><u>SURAT IZIN PENGELUARAN BARANG (S I P B)</u></div>
<br/>
<table>
    <tr>
        <td width="15%">Kode Gudang</td>
        <td width="3%">:</td>
        <td width="20%">' . $header[0]->storage_location . '</td>
        <td width="15%">No. Kendaraan</td>
        <td width="3%">:</td>
        <td width="20%">' . $header[0]->plat_no . '</td>
    </tr>
    <tr>
        <td>Reservasi</td>
        <td>:</td>
        <td>' . (empty($header[0]->reservasi) ? '-' : $header[0]->reservasi) . '</td>
        <td>Dari Gudang</td>
        <td>:</td>
        <td>' . $header[0]->unit_asal_name . '</td>
    </tr>
    <tr>
        <td>No. SLIP</td>
        <td>:</td>
        <td>' . (empty($header[0]->slip) ? '-' : $header[0]->slip) . '</td>
        <td>Kategori</td>
        <td>:</td>
        <td>' . str_replace("_", " ", strtoupper($header[0]->form_name)) . '</td>
    </tr>
</table>

<p>Diizinkan membawa barang-barang dari halaman gudang PLN</p>

<table border="1" width="100%" cellpadding="5">
    ' . $theader . '
    ' . $material . '
</table>

<br/>
<br/>
<table class="footer-table">
    <tr>
        <td align="right">Jakarta, ' . strftime('%e %B %Y', strtotime($header[0]->tanggal)) . '</td>
    </tr>
</table>

<br/>
<br/>
<br/>

<table class="footer-table">
    <tr>
        <td align="center" style="height: 50px;">Pembawa/Penerima Barang</td>
        <td align="center">Pengawas Pekerjaan</td>
        <td align="center">Team Leader Logistik</td>
    </tr>
    <tr>
        <td align="center"><b><u>' . strtoupper($header[0]->ttd_pembawa_barang) . '</u></b></td>
        <td align="center"><b><u>' . strtoupper($header[0]->ttd_pengawas_pekerjaan) . '</u></b></td>
        <td align="center"><b><u>' . strtoupper($header[0]->ttd_team_leader_logistik) . '</u></b></td>
    </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('SIPB Format.pdf');