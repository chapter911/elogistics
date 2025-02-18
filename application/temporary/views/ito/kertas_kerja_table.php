<table id="table" class="table align-middle table-bordered"
    style="white-space: nowrap;">
    <thead style="background-color: #008B8B">
        <tr>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> NO </td>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> UNIT </td>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> URAIAN </td>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> SALDO PER <br/> 01 JANUARI <?= isset($data[0]->tahun) ? html_escape($data[0]->tahun) : '-'; ?> </td>
            <td colspan="6" style="text-align: center; vertical-align: middle; color: white;"> PENAMBAHAN </td>
            <td colspan="8" style="text-align: center; vertical-align: middle; color: white;"> PENGURANGAN </td>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> SALDO <br/> AKHIR BULAN </td>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> REKLAS QA </td>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> PENJELASAN </td>
        </tr>
        <tr>
            <td style="text-align: center; color: white;"> PEMBELIAN </td>
            <td style="text-align: center; color: white;"> RELOKASI DARI<br />LUAR UNIT INDUK </td>
            <td style="text-align: center; color: white;"> REKLAS ANTAR<br />JENIS PERSEDIAAN </td>
            <td style="text-align: center; color: white;"> MUTASI DARI<br />MATERIAL HAPUS </td>
            <td style="text-align: center; color: white;"> MUTASI DARI<br />MATERIAL BURSA </td>
            <td style="text-align: center; color: white;"> LAINNYA </td>
            <td style="text-align: center; color: white;"> KE BIAYA </td>
            <td style="text-align: center; color: white;"> KE AT </td>
            <td style="text-align: center; color: white;"> KE PDP<br />KONSTRUKSI </td>
            <td style="text-align: center; color: white;"> RELOKASI KE<br />LUAR UNIT INDUK </td>
            <td style="text-align: center; color: white;"> REKLAS ANTAR<br />JENIS PERSEDIAAN </td>
            <td style="text-align: center; color: white;"> MUTASI KE<br />MATERIAL HAPUS </td>
            <td style="text-align: center; color: white;"> MUTASI KE<br />MATERIAL BURSA </td>
            <td style="text-align: center; color: white;"> LAINNYA </td>
        </tr>
    </thead>
    <tbody>
        <?php
        $bbm_saldo_awal = 0;
        $bbm_tbh_pembelian = 0;
        $bbm_tbh_relokasi = 0;
        $bbm_tbh_reklas = 0;
        $bbm_tbh_mutasi_hapus = 0;
        $bbm_tbh_mutasi_bursa = 0;
        $bbm_tbh_lainnya = 0;
        $bbm_krg_ke_biaya = 0;
        $bbm_krg_ke_at = 0;
        $bbm_krg_ke_pdp = 0;
        $bbm_krg_relokasi = 0;
        $bbm_krg_reklas = 0;
        $bbm_krg_mutasi_hapus = 0;
        $bbm_krg_mutasi_bursa = 0;
        $bbm_krg_lainnya = 0;
        $bbm_saldo_akhir = 0;
        $bbm_reklas_qa = 0;
        $bbm_saldo_akhir2 = 0;
        $persediaan_saldo_awal = 0;
        $persediaan_tbh_pembelian = 0;
        $persediaan_tbh_relokasi = 0;
        $persediaan_tbh_reklas = 0;
        $persediaan_tbh_mutasi_hapus = 0;
        $persediaan_tbh_mutasi_bursa = 0;
        $persediaan_tbh_lainnya = 0;
        $persediaan_krg_ke_biaya = 0;
        $persediaan_krg_ke_at = 0;
        $persediaan_krg_ke_pdp = 0;
        $persediaan_krg_relokasi = 0;
        $persediaan_krg_reklas = 0;
        $persediaan_krg_mutasi_hapus = 0;
        $persediaan_krg_mutasi_bursa = 0;
        $persediaan_krg_lainnya = 0;
        $persediaan_saldo_akhir = 0;
        $persediaan_reklas_qa = 0;
        foreach ($data as $d) {
            if(html_escape($d->no) <= 6){
                $bbm_saldo_awal += html_escape($d->saldo_awal);
                $bbm_tbh_pembelian += html_escape($d->tbh_pembelian);
                $bbm_tbh_relokasi += html_escape($d->tbh_relokasi);
                $bbm_tbh_reklas += html_escape($d->tbh_reklas);
                $bbm_tbh_mutasi_hapus += html_escape($d->tbh_mutasi_hapus);
                $bbm_tbh_mutasi_bursa += html_escape($d->tbh_mutasi_bursa);
                $bbm_tbh_lainnya += html_escape($d->tbh_lainnya);
                $bbm_krg_ke_biaya += html_escape($d->krg_ke_biaya);
                $bbm_krg_ke_at += html_escape($d->krg_ke_at);
                $bbm_krg_ke_pdp += html_escape($d->krg_ke_pdp);
                $bbm_krg_relokasi += html_escape($d->krg_relokasi);
                $bbm_krg_reklas += html_escape($d->krg_reklas);
                $bbm_krg_mutasi_hapus += html_escape($d->krg_mutasi_hapus);
                $bbm_krg_mutasi_bursa += html_escape($d->krg_mutasi_bursa);
                $bbm_krg_lainnya += html_escape($d->krg_lainnya);
                $bbm_saldo_akhir += html_escape($d->saldo_akhir);
                $bbm_reklas_qa += html_escape($d->reklas_qa);
            } else {
                $persediaan_saldo_awal += html_escape($d->saldo_awal);
                $persediaan_tbh_pembelian += html_escape($d->tbh_pembelian);
                $persediaan_tbh_relokasi += html_escape($d->tbh_relokasi);
                $persediaan_tbh_reklas += html_escape($d->tbh_reklas);
                $persediaan_tbh_mutasi_hapus += html_escape($d->tbh_mutasi_hapus);
                $persediaan_tbh_mutasi_bursa += html_escape($d->tbh_mutasi_bursa);
                $persediaan_tbh_lainnya += html_escape($d->tbh_lainnya);
                $persediaan_krg_ke_biaya += html_escape($d->krg_ke_biaya);
                $persediaan_krg_ke_at += html_escape($d->krg_ke_at);
                $persediaan_krg_ke_pdp += html_escape($d->krg_ke_pdp);
                $persediaan_krg_relokasi += html_escape($d->krg_relokasi);
                $persediaan_krg_reklas += html_escape($d->krg_reklas);
                $persediaan_krg_mutasi_hapus += html_escape($d->krg_mutasi_hapus);
                $persediaan_krg_mutasi_bursa += html_escape($d->krg_mutasi_bursa);
                $persediaan_krg_lainnya += html_escape($d->krg_lainnya);
                $persediaan_saldo_akhir += html_escape($d->saldo_akhir);
                $persediaan_reklas_qa += html_escape($d->reklas_qa);
            }
        ?>
        <tr>
            <td><?= html_escape($d->no) ?></td>
            <td><?= isset($unit[0]->name) ? html_escape($unit[0]->name) : 'ALL UNIT' ?></td>
            <td><?= html_escape($d->uraian) ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->saldo_awal), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->tbh_pembelian), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->tbh_relokasi), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->tbh_reklas), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->tbh_mutasi_hapus), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->tbh_mutasi_bursa), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->tbh_lainnya), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->krg_ke_biaya), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->krg_ke_at), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->krg_ke_pdp), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->krg_relokasi), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->krg_reklas), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->krg_mutasi_hapus), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->krg_mutasi_bursa), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->krg_lainnya), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->saldo_akhir), 0, ",", ".") ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->reklas_qa), 0, ",", ".") ?></td>
            <td><?= html_escape($d->penjelasan) ?></td>
        </tr>
        <?php if(html_escape($d->no) == 6){ ?>
            <tr style="background-color: #008B8B">
                <td></td>
                <td></td>
                <td style="color: white;">Jumlah Bahan Bakar dan Minyak Pelumas</td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_saldo_awal, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_tbh_pembelian, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_tbh_relokasi, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_tbh_reklas, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_tbh_mutasi_hapus, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_tbh_mutasi_bursa, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_tbh_lainnya, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_krg_ke_biaya, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_krg_ke_at, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_krg_ke_pdp, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_krg_relokasi, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_krg_reklas, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_krg_mutasi_hapus, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_krg_mutasi_bursa, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_krg_lainnya, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_saldo_akhir, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($bbm_reklas_qa, 0, ",", ".") ?></td>
                <td style="color: white;">-</td>
            </tr>
        <?php } else if(html_escape($d->no) == 19){ ?>
            <tr style="background-color: #008B8B">
                <td></td>
                <td></td>
                <td style="color: white;">Jumlah Persediaan Material</td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_saldo_awal, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_tbh_pembelian, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_tbh_relokasi, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_tbh_reklas, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_tbh_mutasi_hapus, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_tbh_mutasi_bursa, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_tbh_lainnya, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_krg_ke_biaya, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_krg_ke_at, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_krg_ke_pdp, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_krg_relokasi, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_krg_reklas, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_krg_mutasi_hapus, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_krg_mutasi_bursa, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_krg_lainnya, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_saldo_akhir, 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format($persediaan_reklas_qa, 0, ",", ".") ?></td>
                <td style="color: white;">-</td>
            </tr>
            <tr style="background-color: #008B8B">
                <td></td>
                <td></td>
                <td style="color: white;">Jumlah BBM dan Persediaan Material</td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_saldo_awal + $persediaan_saldo_awal), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_tbh_pembelian + $persediaan_tbh_pembelian), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_tbh_relokasi + $persediaan_tbh_relokasi), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_tbh_reklas + $persediaan_tbh_reklas), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_tbh_mutasi_hapus + $persediaan_tbh_mutasi_hapus), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_tbh_mutasi_bursa + $persediaan_tbh_mutasi_bursa), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_tbh_lainnya + $persediaan_tbh_lainnya), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_krg_ke_biaya + $persediaan_krg_ke_biaya), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_krg_ke_at + $persediaan_krg_ke_at), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_krg_ke_pdp + $persediaan_krg_ke_pdp), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_krg_relokasi + $persediaan_krg_relokasi), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_krg_reklas + $persediaan_krg_reklas), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_krg_mutasi_hapus + $persediaan_krg_mutasi_hapus), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_krg_mutasi_bursa + $persediaan_krg_mutasi_bursa), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_krg_lainnya + $persediaan_krg_lainnya), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_saldo_akhir + $persediaan_saldo_akhir), 0, ",", ".") ?></td>
                <td style="text-align: right; color: white;"><?= number_format(($bbm_reklas_qa + $persediaan_reklas_qa), 0, ",", ".") ?></td>
                <td style="color: white;">-</td>
            </tr>
        <?php } } ?>
    </tbody>
</table>

<script>
$(document).ready(function() {
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 25,
        "ordering": false
    });
});
</script>