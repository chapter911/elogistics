<div class="table-responsive">
    <table class="table-bordered table table-hover table-header-center table-striped">
        <thead>
            <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B">
                <th style="text-align: center; color: white;">NO</th>
                <th class="max-w-300" style="text-align: center; color: white;">Unit</th>
                <?php
                    foreach($lst_mrwi as $r){
                        echo "<th class='max-w-300' style='text-align: center; color: white;''>" . html_escape($r->kriteria). "</th>";
                    }
                ?>
                <th class="max-w-300" style="text-align: center; color: white;">MATLEV MRWI</th>
                <th class="max-w-300" style="text-align: center; color: white;">TARGET</th>
                <th class="max-w-300" style="text-align: center; color: white;">PORSENTASE</th>
                <th class="max-w-300" style="text-align: center; color: white;">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $i = 1;
                foreach($lst_unit as $r){
                    echo "<tr>";
                    echo "<td style='text-align:center'>". $i++ . ".</td>";
                    echo "<td>" . html_escape($r->name) . "</td>";
                    $total = 0;
                    foreach($lst_mrwi as $l){
                        echo "<td style='text-align:right'>". html_escape($lst_nilai[$r->id][$l->id]) ."</td>";
                        $total += $lst_nilai[$r->id][$l->id];
                    }
                    echo "<td style='text-align:right'>".round($total / count($lst_mrwi), 2)."</td>";
                    echo "<td tyle='text-align:center'>-</td>";
                    echo "<td tyle='text-align:center'>-</td>";
                    echo "<td><a class='btn btn-outline-secondary btn-sm' onclick=\"get_detail('" . html_escape($r->id) . "', '$tw', '$th')\"><i class='fa-regular fa-eye'></i></a></td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
        <tfoot>
            <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B">
                <th class="max-w-300" style="text-align: center; color: white;"></th>
                <th class="max-w-300" style="text-align: center; color: white;">PLN KD UID Jakarta Raya</th>
                <?php
                    $total = 0;
                    foreach($lst_mrwi as $l){
                        echo "<td style='text-align:right; color: white'>".round($lst_nilai['total'][$l->id]/count($lst_unit),2)."</td>";
                        $total += round($lst_nilai['total'][$l->id]/count($lst_unit),2);
                    }
                    echo "<td style='text-align:right; color:white'>".round($total / count($lst_mrwi), 2)."</td>";
                ?>
                <th class="max-w-300" style="text-align: center; color: white;">-</th>
                <th class="max-w-300" style="text-align: center; color: white;">-</th>
                <th class="max-w-300" style="text-align: center; color: white;">-</th>
            </tr>
        </tfoot>
    </table>
</div>


<script>
    var data_nilais = JSON.parse('<?=$nilai?>')
    drawChart(data_nilais)
</script>