<?php
    $material_id = array();
    $material = array();
    $tujuan_code = array();
    foreach($data as $d){
        array_push($material_id, html_escape($d['material_id']));
        array_push($material, html_escape($d['material']));
        array_push($tujuan_code, html_escape($d['tujuan_code']));
    }

    $material_id = array_unique($material_id);
    $material = array_unique($material);
    $tujuan_code = array_unique($tujuan_code);
    sort($tujuan_code);
?>

<table id="table-dtl-kontrak" class="table" style="width:100;">
    <thead>
        <tr style="background-color:#008B8B">
            <th style="text-align: center; color:white"> NO </th>
            <th style="text-align: center; color:white"> KATEGORI </th>
            <th style="text-align: center; color:white"> NORMALISASI </th>
            <th style="text-align: center; color:white"> MATERIAL </th>
            <?php foreach ($tujuan_code as $t) { ?>
                <th style="text-align: center; color:white"><?= $t; ?></th>
            <?php } ?>
            <th style="text-align: center;  color:white">TOTAL MATERIAL</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $no = 1;
            foreach ($material_id as $m) {
                $total = 0; ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <?php
                            foreach ($data as $d) {
                                if(html_escape($d['material_id']) == $m){
                                    echo html_escape($d['kategori']);
                                    break;
                                }
                            }
                        ?>
                    </td>
                    <td><?= $m; ?></td>
                    <td>
                        <?php
                            foreach ($data as $d) {
                                if(html_escape($d['material_id']) == $m){
                                    echo html_escape($d['material']);
                                    break;
                                }
                            }
                        ?>
                    </td>
                    <?php foreach ($tujuan_code as $t) {
                        $tujuan_exist = false;
                        foreach($data as $d){
                            if(html_escape($d['material_id']) == $m && html_escape($d['tujuan_code']) == $t){
                                $total += html_escape($d['volume']);
                                echo "<td style='text-align: right'>". html_escape($d['volume']) . "</td>";
                                $tujuan_exist = true;
                            }
                        }
                        if(!$tujuan_exist){
                            echo "<td style='text-align: right'>0</td>";
                        }
                    } ?>
                    <td style="text-align: right">
                        <?= number_format($total, 0, ",", "."); ?>
                    </td>
                </tr>
        <?php } ?>
    </tbody>
</table>