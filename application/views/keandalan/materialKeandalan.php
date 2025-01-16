<div class="table-responsive">
    <table class="table" style="white-space: nowrap;">
        <thead>
            <tr style="background-color: #008B8B">
                <th style="text-align: center; vertical-align: middle; color: white;"> NO </th>
                <th style="text-align: center; vertical-align: middle; color: white;"> NORMALISASI </th>
                <th style="text-align: center; vertical-align: middle; color: white;"> MATERIAL </th>
                <th style="text-align: center; vertical-align: middle; color: white;"> VOLUME MINTA </th>
                <th style="text-align: center; vertical-align: middle; color: white;"> VOLUME DIPENUHI </th>
                <th style="text-align: center; vertical-align: middle; color: white;"> KETERANGAN </th>
            </tr>
        </thead>
        <tbody>
            <?php $i =1; foreach ($data as $d) { ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td>
                        <input type="hidden" name="material[]" value="<?= html_escape($d->material_id); ?>"/>
                        <?= html_escape($d->material_id); ?>
                    </td>
                    <td><?= html_escape($d->material); ?></td>
                    <td>
                        <input type="number" name="volume[]" class="form-control" value="<?= html_escape($d->volume); ?>"/>
                    </td>
                    <td>
                        <input type="number" name="approved_volume[]" min="<?= html_escape($d->approved_volume); ?>" max="<?= html_escape($d->volume); ?>" class="form-control" value="<?= html_escape($d->approved_volume); ?>" required/>
                    </td>
                    <td><?= html_escape($d->keterangan); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>