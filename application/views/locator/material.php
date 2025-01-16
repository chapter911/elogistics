<div class="table-responsive">
    <table id="kt_datatable_fixed_header" class="table align-middle table-bordered table-hover table-striped table-row-bordered" style="white-space: nowrap;">
        <thead>
            <tr style="background-color: #008B8B">
                <th style="text-align: center; color: white;"> NO </th>
                <th style="text-align: center; color: white;"> NORMALISASI </th>
                <th style="text-align: center; color: white;"> MATERIAL </th>
                <th style="text-align: center; color: white;"> KATEGORI </th>
                <th style="text-align: center; color: white;"> RAK </th>
                <th style="text-align: center; color: white;"> LANTAI </th>
                <th style="text-align: center; color: white;"> PETAK </th>
                <th style="text-align: center; color: white;"> VOLUME </th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($data as $d) { ?>
                <tr>
                    <td style="text-align: center;"><?= $i++; ?></td>
                    <td><?= html_escape($d->id_material); ?></td>
                    <td><?= html_escape($d->material); ?></td>
                    <td style="text-align: center;"><?= html_escape($d->kategori); ?></td>
                    <td style="text-align: center;"><?= html_escape($d->rak); ?></td>
                    <td style="text-align: center;"><?= html_escape($d->lantai); ?></td>
                    <td style="text-align: center;"><?= html_escape($d->petak); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->stock); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>