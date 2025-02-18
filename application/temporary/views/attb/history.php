<div class="table-responsive">
    <table id="kt_datatable_attb" class="table align-middle table-bordered table-hover table-striped table-row-bordered"
        style="white-space: nowrap;">
        <thead>
            <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B">
                <th style="text-align: center; color: white;"> NO ATTB </th>
                <th style="text-align: center; color: white;"> TUG </th>
                <th style="text-align: center; color: white;"> NORMALISASI </th>
                <th style="text-align: center; color: white;"> MATERIAL </th>
                <th style="text-align: center; color: white;"> KATEGORI </th>
                <th style="text-align: center; color: white;"> VOLUME </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($header as $h) { ?>
            <tr>
                <td style="text-align: center;"><?= html_escape($h->no_attb)?></td>
                <td style="text-align: center;"><?= html_escape($h->tug) ?></td>
                <td style="text-align: center;"><?= html_escape($h->material_id) ?></td>
                <td style="text-align: center;"><?= html_escape($h->material) ?></td>
                <td style="text-align: center;"><?= html_escape($h->kategori) ?></td>
                <td style="text-align: center;"><?= html_escape($h->volume) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<br />
<div class="table-responsive">
    <table id="kt_datatable_attb" class="table align-middle table-bordered table-hover table-striped table-row-bordered"
        style="white-space: nowrap;">
        <thead>
            <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B">
                <th style="text-align: center; color: white;"> NO </th>
                <th style="text-align: center; color: white;"> TANGGAL </th>
                <th style="text-align: center; color: white;"> STATUS </th>
                <th style="text-align: center; color: white;"> LOCATION </th>
                <th style="text-align: center; color: white;"> SIPB </th>
                <th style="text-align: center; color: white;"> FOTO </th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($detail as $d) { ?>
            <tr>
                <td style="text-align: center;"><?= $i++ ?></td>
                <td style="text-align: center;"><?= html_escape($d->updated_date) ?></td>
                <td style="text-align: center;"><?= html_escape($d->status) ?></td>
                <td style="text-align: center;"><?= html_escape($d->location) ?></td>
                <td style="text-align: center;">
                    <?php
                        if(html_escape($d->sipb) != null){
                            if(html_escape($d->sipb_file) == null){?>
                    <button class="btn btn-danger btn-sm"><?= html_escape($d->sipb) ?></button>
                    <?php } else { ?>

                    <a href="<?= base_url() . html_escape($d->filepath) . '/' . html_escape($d->sipb_file) ?>" target="_blank"
                        class="btn btn-success btn-sm"><?= html_escape($d->sipb) ?></a>
                    <?php }
                        }
                    ?>
                </td>
                <td style="text-align: center;">
                    <?php if(html_escape($d->location) != null) { ?>
                    <button class='btn <?= html_escape($d->foto1) == null ? 'btn-danger' : 'btn-success' ?> btn-sm'
                        onclick="foto(<?= html_escape($d->id) ?>, 1, <?= '\'' . html_escape($d->filepath) . '/' . html_escape($d->foto1) . '\'' ?>)">1</button>
                    <button class='btn <?= html_escape($d->foto2) == null ? 'btn-danger' : 'btn-success' ?> btn-sm'
                        onclick="foto(<?= html_escape($d->id) ?>, 2, <?= '\'' . html_escape($d->filepath) . '/' . html_escape($d->foto2) . '\'' ?>)">2</button>
                    <button class='btn <?= html_escape($d->foto3) == null ? 'btn-danger' : 'btn-success' ?> btn-sm'
                        onclick="foto(<?= html_escape($d->id) ?>, 3, <?= '\'' . html_escape($d->filepath) . '/' . html_escape($d->foto3) . '\'' ?>)">3</button>
                    <button class='btn <?= html_escape($d->foto4) == null ? 'btn-danger' : 'btn-success' ?> btn-sm'
                        onclick="foto(<?= html_escape($d->id) ?>, 4, <?= '\'' . html_escape($d->filepath) . '/' . html_escape($d->foto4) . '\'' ?>)">4</button>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <div>