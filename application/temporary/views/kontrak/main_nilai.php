<table 
    class="table align-middle table-bordered table-hover table-striped table-row-bordered" style="white-space: nowrap;">
    <thead>
        <tr style="background-color: #008B8B">
            <th style="text-align: center; color: white;"> NO </th>
            <th style="text-align: center; color: white;"> NORMALISASI </th>
            <th style="text-align: center; color: white;"> MATERIAL </th>
            <th style="text-align: center; color: white;"> KATEGORI </th>
            <th style="text-align: center; color: white;"> SATUAN </th>
            <th style="text-align: center; color: white;"> VOLUME </th>
            <th style="text-align: center; color: white;"> HARGA </th>
            <th style="text-align: center; color: white;"> ONGKIR </th>
            <th style="text-align: center; color: white;"> TOTAL + PPN (11%) </th>
        </tr>
    </thead>
    <tbody>
        <?php
            $no = 1;
            $total = 0;
            foreach ($data as $d) {
                $total += html_escape($d->total);?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= html_escape($d->material_id); ?></td>
            <td><?= html_escape($d->material); ?></td>
            <td><?= html_escape($d->kategori); ?></td>
            <td><?= html_escape($d->satuan); ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->volume), 0, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->harga), 0, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->ongkir), 0, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->total), 0, ",", "."); ?></td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr style="background-color: #008B8B">
            <th colspan="8" style="text-align: center; color: white;">TOTAL NILAI</th>
            <th style="text-align: right; color: white;"><?= number_format($total, 0, ",", "."); ?></th>
        </tr>
    </tfoot>
</table>