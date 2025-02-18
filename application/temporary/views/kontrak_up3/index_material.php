<table id="table-dtl-kontrak" class="table" style="width:100;">
    <thead>
        <tr style="background-color:#008B8B">
            <th style="text-align: center; color:white"> NO </th>
            <th style="text-align: center; color:white"> KATEGORI </th>
            <th style="text-align: center; color:white"> MATERIAL </th>
            <th style="text-align: center; color:white">SATUAN</th>
            <th style="text-align: center; color:white">VOLUME</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $no = 1;
            $total = 0;
            foreach ($data as $d) { ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= html_escape($d->kategori); ?></td>
                    <td><?= html_escape($d->material); ?></td>
                    <td><?= html_escape($d->satuan); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->volume); ?></td>
                </tr>
        <?php } ?>
    </tbody>
</table>