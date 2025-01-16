<div class="row">
    <div class="table-responsive col-md-6">
        <table class="table table-bordered">
            <tr>
                <td><span class="card-label fw-bold text-gray-800">Nomor Anggaran</span></td>
                <td>
                    <span class="card-label fw-bold text-gray-800"><?= isset($header[0]->no_skki) ? html_escape($header[0]->no_skki) : ""; ?>
                        &nbsp;&nbsp;
                        <a href="<?= base_url() . html_escape($header[0]->dokumen); ?>" target="_blank" class="btn btn-text-danger btn-hover-light-danger btn-sm waves-effect waves-light">
                            <i class="fa fa-file-pdf"></i>PDF
                        </a>
                    </span>
                </td>
            </tr>
            <tr>
                <td><span class="card-label fw-bold text-gray-800">Tanggal Anggaran</span></td>
                <td><span class="card-label fw-bold text-gray-800"><?= isset($header[0]->tanggal) ? html_escape($header[0]->tanggal) : "" ; ?></span></td>
            </tr>
            <tr>
                <td><span class="card-label fw-bold text-gray-800">Nilai AKI Terbit</span></td>
                <td><span class="card-label fw-bold text-gray-800"><?= number_format(isset($header[0]->nilai_aki) ? html_escape($header[0]->nilai_aki) : 0, 0, ",", "."); ?></span></td>
            </tr>
        </table>
    </div>
</div>

<hr/>

<div class="table-responsive">
    <table id="table" class="table" style="white-space: nowrap;">
        <thead style="background-color: #008B8B;">
            <tr>
                <th style="text-align: center; vertical-align: middle; color:white;" rowspan="2"> NO </th>
                <th style="text-align: center; vertical-align: middle; color:white;" rowspan="2"> NORMALISASI </th>
                <th style="text-align: center; vertical-align: middle; color:white;" rowspan="2"> MATERIAL </th>
                <th style="text-align: center; vertical-align: middle; color:white;" rowspan="2"> SATUAN </th>
                <th style="text-align: center; vertical-align: middle; color:white;" rowspan="2"> JENIS </th>
                <th style="text-align: center; vertical-align: middle; color:white;" colspan="3"> SKKI TERBIT </th>
                <th style="text-align: center; vertical-align: middle; color:white;" colspan="2"> KONTRAK RINCI </th>
                <th style="text-align: center; vertical-align: middle; color:white;" colspan="2"> +/- SKKI </th>
                <th style="text-align: center; vertical-align: middle; color:white;" rowspan="2"> ACTION </th>
            </tr>
            <tr>
                <th style="text-align: center; color:white;"> VOLUME </th>
                <th style="text-align: center; color:white;"> HARGA </th>
                <th style="text-align: center; color:white;"> TOTAL </th>
                <th style="text-align: center; color:white;"> VOLUME </th>
                <th style="text-align: center; color:white;"> TOTAL </th>
                <th style="text-align: center; color:white;"> VOLUME </th>
                <th style="text-align: center; color:white;"> RUPIAH </th>
            </tr>
        </thead>
        <tbody>
            <?php
                $i = 1;
                $jumlah_skki = 0;
                $jumlah_kontrak = 0;
                foreach ($data as $d) { ?>
                    <tr <?= html_escape($d->is_registered) == 1 ? '' : "style='background-color: #ffcc99'"?>>
                        <td><?= $i++; ?></td>
                        <td><?= html_escape($d->material_id); ?></td>
                        <td><?= html_escape($d->material); ?></td>
                        <td style="text-align: center; vertical-align: center;"><?= html_escape($d->satuan); ?></td>
                        <td style="text-align: center; vertical-align: center;"><?= html_escape($d->is_mdu); ?></td>
                        <td style="text-align: right; vertical-align: center;"><?= number_format(html_escape($d->volume_skki), 0, ",", "."); ?></td>
                        <td style="text-align: right; vertical-align: center;"><?= number_format(html_escape($d->harga_skki), 0, ",", "."); ?></td>
                        <td style="text-align: right; vertical-align: center;"><?= number_format(html_escape($d->total_skki), 0, ",", "."); $jumlah_skki += html_escape($d->total_skki); ?></td>
                        <td style="text-align: right; vertical-align: center;"><?= number_format(html_escape($d->volume_kontrak), 0, ",", "."); ?></td>
                        <td style="text-align: right; vertical-align: center;"><?= number_format(html_escape($d->total_kontrak), 0, ",", "."); $jumlah_kontrak += html_escape($d->total_kontrak); ?></td>
                        <td style="text-align: right; vertical-align: center; <?= html_escape($d->selisih_skki) < 0 ? 'background-color: #f1416c; color: white' : '';?>;"><?= number_format(html_escape($d->selisih_skki), 0, ",", "."); ?></td>
                        <td style="text-align: right; vertical-align: center; <?= html_escape($d->selisih_harga) < 0 ? 'background-color: #f1416c; color: white' : '';?>;"><?= number_format(html_escape($d->selisih_harga), 0, ",", "."); ?></td>
                        <td style="text-align: right; vertical-align: center;">
                            <div class="btn-group">
                                <?php if(html_escape($d->is_registered)) { ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editSKKI('<?= html_escape($d->id_skki); ?>', '<?= html_escape($d->material_id); ?>')">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="hapusMaterialSKKI('<?= html_escape($d->id_skki); ?>', '<?= html_escape($d->material_id); ?>')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
            <?php }
                $ppn_jumlah_skki = $jumlah_skki * 0.11;
                $ppn_jumlah_kontrak = $jumlah_kontrak * 0.11;
                $pembagi = $jumlah_skki + $ppn_jumlah_skki;
                $prosentase_kontrak = $pembagi == 0 ? "0" : (($jumlah_kontrak + $ppn_jumlah_kontrak) / $pembagi) * 100;
                $prosentase_selisih = $pembagi == 0 ? "0" : ((($jumlah_skki - $jumlah_kontrak) + ($ppn_jumlah_skki - $ppn_jumlah_kontrak)) / $pembagi) * 100;
            ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #008B8B;">
                <td style="text-align: center; vertical-align: middle; color: white; font-weight: bold" colspan="5">JUMLAH</td>
                <td style="text-align: right; vertical-align: middle; color: white; font-weight: bold" colspan="3"><?= number_format($jumlah_skki, 0, ",", "."); ?></td>
                <td style="text-align: right; vertical-align: middle; color: white; font-weight: bold" colspan="2"><?= number_format($jumlah_kontrak, 0, ",", "."); ?></td>
                <td style="text-align: right; vertical-align: middle; color: white; font-weight: bold" colspan="2"><?= number_format($jumlah_skki - $jumlah_kontrak, 0, ",", "."); ?></td>
                <td></td>
            </tr>
            <tr style="background-color: #008B8B;">
                <td style="text-align: center; vertical-align: middle; color: white; font-weight: bold" colspan="5">PPN 11%</td>
                <td style="text-align: right; vertical-align: middle; color: white; font-weight: bold" colspan="3"><?= number_format($ppn_jumlah_skki, 0, ",", "."); ?></td>
                <td style="text-align: right; vertical-align: middle; color: white; font-weight: bold" colspan="2"><?= number_format($ppn_jumlah_kontrak, 0, ",", "."); ?></td>
                <td style="text-align: right; vertical-align: middle; color: white; font-weight: bold" colspan="2"><?= number_format($ppn_jumlah_skki - $ppn_jumlah_kontrak, 0, ",", "."); ?></td>
                <td></td>
            </tr>
            <tr style="background-color: #008B8B;">
                <td style="text-align: center; vertical-align: middle; color: white; font-weight: bold" colspan="5">TOTAL</td>
                <td style="text-align: right; vertical-align: middle; color: white; font-weight: bold" colspan="3"><?= number_format($jumlah_skki + $ppn_jumlah_skki, 0, ",", "."); ?></td>
                <td style="text-align: right; vertical-align: middle; color: white; font-weight: bold" colspan="2"><?= number_format($jumlah_kontrak + $ppn_jumlah_kontrak, 0, ",", "."); ?></td>
                <td style="text-align: right; vertical-align: middle; color: white; font-weight: bold" colspan="2"><?= number_format(($jumlah_skki - $jumlah_kontrak) + ($ppn_jumlah_skki - $ppn_jumlah_kontrak), 0, ",", "."); ?></td>
                <td></td>
            </tr>
            <tr style="background-color: #ffc700;">
                <td style="text-align: center; vertical-align: middle; font-weight: bold" colspan="8">PROSENTASE PENYERAPAN</td>
                <td style="text-align: right; vertical-align: middle; font-weight: bold" colspan="2"><?= number_format($prosentase_kontrak, 2, ",", "."); ?>%</td>
                <td style="text-align: right; vertical-align: middle; font-weight: bold" colspan="2"><?= number_format($prosentase_selisih, 2, ",", "."); ?>%</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    $(document).ready(function(){
        $("#table").DataTable({
            "scrollX": true,
            "fixedHeader": {
                "header": true,
                "headerOffset": $('.layout-navbar').height() + 15
            },
            "lengthMenu": [
                [100, 250, 500, -1],
                [100, 250, 500, 'All']
            ],
            "language": {
                "decimal": ",",
            },
        });
    });
</script>