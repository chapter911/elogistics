<table id="table" class="table align-middle table-bordered" style="white-space: nowrap;">
    <thead style="background-color: #008B8B">
        <tr>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> BA </td>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> URAIAN </td>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> SALDO PER <br /> 01
                JANUARI <?= html_escape($data[0]->tahun); ?></td>
            <td colspan="4" style="text-align: center; vertical-align: middle; color: white;"> PENAMBAHAN </td>
            <td colspan="7" style="text-align: center; vertical-align: middle; color: white;"> PENGURANGAN </td>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> SALDO <br/> AKHIR BULAN </td>
            <td rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> ITO </td>
        </tr>
        <tr>
            <td style="text-align: center; color: white;"> PEMBELIAN </td>
            <td style="text-align: center; color: white;"> DARI UNIT LAIN </td>
            <td style="text-align: center; color: white;"> REKLAS PERSEDIAAN </td>
            <td style="text-align: center; color: white;"> LAINNYA </td>
            <td style="text-align: center; color: white;"> KE BIAYA </td>
            <td style="text-align: center; color: white;"> KE AT </td>
            <td style="text-align: center; color: white;"> KE PDP<br />KONSTRUKSI </td>
            <td style="text-align: center; color: white;"> KE UNIT LAIN </td>
            <td style="text-align: center; color: white;"> REKLAS PERSEDIAAN </td>
            <td style="text-align: center; color: white;"> MUTASI KE<br />MATERIAL BURSA </td>
            <td style="text-align: center; color: white;"> LAINNYA </td>
        </tr>
    </thead>
    <tbody>
        <?php
            $total_saldo_awal = 0;
            $total_tbh_pembelian = 0;
            $total_tbh_relokasi = 0;
            $total_tbh_reklas = 0;
            $total_tbh_lainnya = 0;
            $total_krg_ke_biaya = 0;
            $total_krg_ke_at = 0;
            $total_krg_ke_pdp = 0;
            $total_krg_relokasi = 0;
            $total_krg_reklas = 0;
            $total_krg_mutasi_bursa = 0;
            $total_krg_lainnya = 0;
            $total_saldo_akhir = 0;
            $total_ito = 0;
            foreach ($data as $d) {
                $total_saldo_awal += html_escape($d->saldo_awal);
                $total_tbh_pembelian += html_escape($d->tbh_pembelian);
                $total_tbh_relokasi += html_escape($d->tbh_relokasi);
                $total_tbh_reklas += html_escape($d->tbh_reklas);
                $total_tbh_lainnya += html_escape($d->tbh_lainnya);
                $total_krg_ke_biaya += html_escape($d->krg_ke_biaya);
                $total_krg_ke_at += html_escape($d->krg_ke_at);
                $total_krg_ke_pdp += html_escape($d->krg_ke_pdp);
                $total_krg_relokasi += html_escape($d->krg_relokasi);
                $total_krg_reklas += html_escape($d->krg_reklas);
                $total_krg_mutasi_bursa += html_escape($d->krg_mutasi_bursa);
                $total_krg_lainnya += html_escape($d->krg_lainnya);
                $total_saldo_akhir += html_escape($d->saldo_akhir);
                $total_ito += html_escape($d->ito);
            ?>
        <tr>
            <td><?= html_escape($d->unit); ?></td>
            <td><?= html_escape($d->name); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->saldo_awal), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->tbh_pembelian), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->tbh_relokasi), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->tbh_reklas), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->tbh_lainnya), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->krg_ke_biaya), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->krg_ke_at), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->krg_ke_pdp), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->krg_relokasi), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->krg_reklas), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->krg_mutasi_bursa), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->krg_lainnya), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->saldo_akhir), 0, ",", "."); ?></td>
            <td style="text-align: right"><?= number_format(html_escape($d->ito), 3, ",", "."); ?></td>
        </tr>
        <?php } ?>
        <tr style="background-color: #008B8B">
            <td></td>
            <td style="text-align: right; color: white">Jumlah Persediaan Material</td>
            <td style="text-align: right; color: white"><?= number_format($total_saldo_awal, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_tbh_pembelian, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_tbh_relokasi, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_tbh_reklas, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_tbh_lainnya, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_krg_ke_biaya, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_krg_ke_at, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_krg_ke_pdp, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_krg_relokasi, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_krg_reklas, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_krg_mutasi_bursa, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_krg_lainnya, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format($total_saldo_akhir, 0, ",", "."); ?></td>
            <td style="text-align: right; color: white"><?= number_format((($total_krg_ke_biaya + $total_krg_ke_at + $total_krg_ke_pdp) / (($total_saldo_awal + $total_saldo_akhir) / 2)), 2, ",", "."); ?></td>
        </tr>
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