<div class="row mb-5">
    <div class="col-md-12">
        <div class="card" style="justify-content: center">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 ">Tabel Realisasi ITO</span>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card " style="justify-content: center">
            <div class="card-body pt-3">
                <table id="table_ito" class="table">
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
                        <?php foreach ($data as $d) { ?>
                            <tr>
                                <td><?= $d->name; ?></td>
                                <td style="text-align: right"><?= number_format($d->jan, 2, '.', ',') ?></td>
                                <td style="text-align: right"><?= number_format($d->feb, 2, '.', ',') ?></td>
                                <td style="text-align: right"><?= number_format($d->mar, 2, '.', ',') ?></td>
                                <td style="text-align: right"><?= number_format($d->apr, 2, '.', ',') ?></td>
                                <td style="text-align: right"><?= number_format($d->mei, 2, '.', ',') ?></td>
                                <td style="text-align: right"><?= number_format($d->jun, 2, '.', ',') ?></td>
                                <td style="text-align: right"><?= number_format($d->jul, 2, '.', ',') ?></td>
                                <td style="text-align: right"><?= number_format($d->agts, 2, '.', ',') ?></td>
                                <td style="text-align: right"><?= number_format($d->sep, 2, '.', ',') ?></td>
                                <td style="text-align: right"><?= number_format($d->okt, 2, '.', ',') ?></td>
                                <td style="text-align: right"><?= number_format($d->nov, 2, '.', ',') ?></td>
                                <td style="text-align: right"><?= number_format($d->des, 2, '.', ',') ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $("#table_ito").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 50
    });
</script>