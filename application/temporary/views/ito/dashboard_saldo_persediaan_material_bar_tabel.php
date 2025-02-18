<div class="row mb-5">
    <div class="col-md-12">
        <div class="card" style="justify-content: center">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 "><?= $judul; ?></span>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card" style="justify-content: center">
            <div class="card-body pt-3">
                <table id="table_<?= $tabel_id; ?>" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; vertical-align: middle; color: white;"> UNIT </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> JAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> FEB </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> MAR </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> APR </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> MEI </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> JUN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> JUL </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> AGT </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> SEP </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> OKT </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> NOV </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> DES </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $total_jan = 0;
                            $total_feb = 0;
                            $total_mar = 0;
                            $total_apr = 0;
                            $total_mei = 0;
                            $total_jun = 0;
                            $total_jul = 0;
                            $total_agt = 0;
                            $total_sep = 0;
                            $total_okt = 0;
                            $total_nov = 0;
                            $total_des = 0;
                            foreach ($data as $d) {
                                $total_jan += $d->jan;
                                $total_feb += $d->feb;
                                $total_mar += $d->mar;
                                $total_apr += $d->apr;
                                $total_mei += $d->mei;
                                $total_jun += $d->jun;
                                $total_jul += $d->jul;
                                $total_agt += $d->agts;
                                $total_sep += $d->sep;
                                $total_okt += $d->okt;
                                $total_nov += $d->nov;
                                $total_des += $d->des;
                            ?>
                            <tr>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 300px;"><?= $d->name; ?></td>
                                <td style="text-align: right"><?= number_format($d->jan, 0, ',', '.') ?></td>
                                <td style="text-align: right"><?= number_format($d->feb, 0, ',', '.') ?></td>
                                <td style="text-align: right"><?= number_format($d->mar, 0, ',', '.') ?></td>
                                <td style="text-align: right"><?= number_format($d->apr, 0, ',', '.') ?></td>
                                <td style="text-align: right"><?= number_format($d->mei, 0, ',', '.') ?></td>
                                <td style="text-align: right"><?= number_format($d->jun, 0, ',', '.') ?></td>
                                <td style="text-align: right"><?= number_format($d->jul, 0, ',', '.') ?></td>
                                <td style="text-align: right"><?= number_format($d->agts, 0, ',', '.') ?></td>
                                <td style="text-align: right"><?= number_format($d->sep, 0, ',', '.') ?></td>
                                <td style="text-align: right"><?= number_format($d->okt, 0, ',', '.') ?></td>
                                <td style="text-align: right"><?= number_format($d->nov, 0, ',', '.') ?></td>
                                <td style="text-align: right"><?= number_format($d->des, 0, ',', '.') ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; vertical-align: middle; color: white;"> TOTAL </th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_jan, 0, ',', '.') ?></th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_feb, 0, ',', '.') ?></th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_mar, 0, ',', '.') ?></th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_apr, 0, ',', '.') ?></th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_mei, 0, ',', '.') ?></th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_jun, 0, ',', '.') ?></th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_jul, 0, ',', '.') ?></th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_agt, 0, ',', '.') ?></th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_sep, 0, ',', '.') ?></th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_okt, 0, ',', '.') ?></th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_nov, 0, ',', '.') ?></th>
                            <th style="text-align: right; vertical-align: middle; color: white;"><?= number_format($total_des, 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $("#table_<?= $tabel_id; ?>").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 50
    });
</script>