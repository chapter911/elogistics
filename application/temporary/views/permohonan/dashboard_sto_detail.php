<div class="modal-header">
    <h5 class="modal-title" id="nama_material">
        <?= html_escape($data[0]->singkatan) . ' - ' . html_escape($data[0]->normalisasi). ' - ' . html_escape($data[0]->material); ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body scroll-y m-5">
    <table id="table_detail" class="table">
        <thead>
            <tr style="background-color: #008B8B">
                <th style="text-align: center; color: white;"> BASKET </th>
                <th style="text-align: center; color: white;"> NO PR </th>
                <th style="text-align: center; color: white;"> TGL PR </th>
                <th style="text-align: center; color: white;"> NO ANGGARAN </th>
                <th style="text-align: center; color: white;"> VOLUME </th>
                <th style="text-align: center; color: white;"> SATUAN </th>
            </tr>
        </thead>
        <tbody>
            <?php
                $total = 0;
                foreach ($data as $d) { ?>
            <tr>
                <td><?= html_escape($d->basket); ?></td>
                <td><?= html_escape($d->no_pr); ?></td>
                <td><?= html_escape($d->tanggal_pr); ?></td>
                <td><?= html_escape($d->no_anggaran); ?></td>
                <td style="text-align: right;">
                    <?php
                            $total += html_escape($d->approved_volume);
                            echo number_format(html_escape($d->approved_volume), 0, ",", ".");
                        ?>
                </td>
                <td style="text-align: center;"><?= html_escape($d->satuan); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #008B8B">
                <th colspan="4"">#</th>
                <th style=" text-align: right; color: white;"><?= number_format($total, 0, ",", "."); ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>