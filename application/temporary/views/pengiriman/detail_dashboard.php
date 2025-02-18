<table>
    <tbody>
        <tr>
            <td>No Kontrak</td>
            <td style="padding-left: 20px; padding-right: 20px;">:</td>
            <td><?= html_escape($vendor[0]->no_kontrak); ?></td>
        </tr>
        <tr>
            <td>Vendor</td>
            <td style="padding-left: 20px; padding-right: 20px;">:</td>
            <td><?= html_escape($vendor[0]->vendor); ?></td>
        </tr>
        <tr>
            <td>Material</td>
            <td style="padding-left: 20px; padding-right: 20px;">:</td>
            <td><?= html_escape($vendor[0]->material); ?></td>
        </tr>
    </tbody>
</table>
<br />

<table id="table" class="table">
    <thead>
        <tr style="background-color: #008B8B">
            <th style="text-align: center; color: white;"> NO </th>
            <th style="text-align: center; color: white;"> UNIT </th>
            <th style="text-align: center; color: white;"> VOLUME KONTRAK </th>
            <th style="text-align: center; color: white;"> VOLUME KIRIM </th>
            <th style="text-align: center; color: white;"> VOLUME BELUM KIRIM </th>
            <th style="text-align: center; color: white;"> PROGRESS </th>
            <th style="text-align: center; color: white;"> PENGIRIMAN </th>
        </tr>
    </thead>
    <tbody>
        <?php
            $no = 1;
            foreach ($data as $d) { ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= html_escape($d->name); ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->volume_kontrak), 0, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->volume_kirim), 0, ",", "."); ?></td>
            <td style="text-align: right;"><?= number_format(html_escape($d->volume_kontrak) - html_escape($d->volume_kirim), 0, ",", "."); ?>
            </td>
            <td style="text-align: center;">
                <?php if(html_escape($d->volume_kirim) == 0){ ?>
                <button class="btn btn-danger btn-sm">BELUM KIRIM</button>
                <?php } else if(html_escape($d->volume_kontrak) > html_escape($d->volume_kirim)) { ?>
                <button class="btn btn-warning btn-sm">PROSES KIRIM</button>
                <?php } else { ?>
                <button class="btn btn-primary btn-sm">SELESAI KIRIM</button>
                <?php } ?>
            </td>
            <td style="text-align: center">
                <button onclick="detailPengirimanUnit('<?= html_escape($d->no_kontrak); ?>', '<?= html_escape($d->id); ?>')" class="btn btn-success btn-sm">DETAIL</button>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>