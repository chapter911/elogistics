<table class="table align-middle table-bordered table-hover table-striped table-row-bordered" style="white-space: nowrap;">
    <thead>
        <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B">
            <th style="text-align: center; color: white;"> NO </th>
            <th style="text-align: center; color: white;"> NORMALISASI </th>
            <th style="text-align: center; color: white;"> MATERIAL </th>
            <th style="text-align: center; color: white;"> ALOKASI </th>
        </tr>
    </thead>
    <tbody>
        <?php
            $i =1;
            foreach ($data as $d) { ?>
                <tr>
                    <td style="text-align: right;"><?= $i++; ?></td>
                    <td><?= html_escape($d->material_id); ?></td>
                    <td><?= html_escape($d->material); ?></td>
                    <td><?= html_escape($d->alokasi); ?></td>
                </tr>
        <?php } ?>
    </tbody>
</table>