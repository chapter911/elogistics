<table id="kt_datatable_fixed_header" class="table align-middle table-bordered table-hover table-striped table-row-bordered">
    <thead>
        <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B">
            <th style="text-align: center; color:white;" class="min-w-200px"> NO </th>
            <th style="text-align: center; color:white;" class="min-w-200px"> KRITERIA </th>
            <th style="text-align: center; color:white;" class="min-w-200px"> INDIKASI </th>
            <th style="text-align: center; color:white;" class="min-w-200px"> LEVEL </th>
            <th style="text-align: center; color:white;" class="min-w-200px"> LEVEL NAME</th>
            <th style="text-align: center; color:white;" class="min-w-200px"> EVIDENCE </th>
            <th style="text-align: center; color:white;" class="min-w-200px"> FILE </th>
            <th style="text-align: center; color:white;" class="min-w-400px" colspan="3"> Action </th>

        </tr>
    </thead>
    <tbody>
        <?php
        // var_dump($lst_sub_dtl);
        foreach ($lst_dtl as $r) {
            $i = 1;
            $sub = array();
            foreach ($lst_sub_dtl as $l) {
                if ($r->kriteria_unit == $l->dtl_case_id) {
                    $sub[] = $l;
                }
            }

            if (isset($r->kriteria_unit_approve)) {
                $array = explode('.', $r->kriteria_unit_approve);

                // Ambil elemen yang diinginkan
                $part1 = $array[0] . '.' . $array[1] ; // "1.1"
                $part2 = $array[2]; // "5"
            }


            $row = count($sub);
            $stat = $r->status == "Submitted" ? "" : $r->status;
            if ($row > 0) {
                echo "<tr>";
                echo "<td rowspan='$row'>" . html_escape($r->nomor) . "</td>";
                echo "<td rowspan='$row'>" . html_escape($r->kriteria) . "</td>";
                echo "<td rowspan='$row'>" . html_escape($r->indikasi) . "</td>";
                echo "<td rowspan='$row'>" . html_escape($r->level) . "</td>";
                echo "<td rowspan='$row'>" . html_escape($r->kriteria_unit_name) . "</td>";

                foreach ($sub as $l) {
                    if ($i == 1) {
                        echo "<td>$l->evidence</td>";
                        $url = base_url() . "data_uploads/mrwi/" . html_escape($l->uploads);
                        echo "<td style=\"text-align:center\"><a href=\"$url\" target=\"_blank\" class=\"btn btn-outline-secondary btn-sm\"><i class='fa fa-file-download'></i></a></td>";
                        echo "<td rowspan='$row'><select name=\"kriteria_unit_approve[]\"  class=\"form-select pilihan_kriteria\">";
                        if (isset($r->kriteria_unit_approve)) {
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i == $part2) {
                                    echo "<option value='$part1.$i' selected>$i</option>";
                                } else {
                                    echo "<option value='$part1.$i'>$i</option>";
                                }
                            }
                        }

                        echo "</select></td>";
                        echo "<td rowspan='$row'><select name=\"pilihan[]\"  class=\"form-select pilihan\">
                                    <option value=\"$stat\">$stat</option>
                                    <option value=\"Approved\">Approve</option>
                                    <option value=\"Rejected\">Reject</option>
                                </select><input type=\"text\" name=\"id_approve[]\" value=\"" . html_escape($r->id) . "\" hidden/>
                                </td>";
                        echo "<td rowspan='$row'><textarea name=\"alasan[]\" class=\"form-control alasan[]\" rows=\"2\"></textarea></td>";
                        echo "</tr>";
                    } else {
                        echo "<tr>";
                        echo "<td>" . html_escape($l->evidence) . "</td>";
                        $url = base_url() . "data_uploads/mrwi/" . html_escape($l->uploads);
                        echo "<td style=\"text-align:center\"><a href=\"$url\" target=\"_blank\" class=\"btn btn-outline-secondary btn-sm\"><i class='fa fa-file-download'></i></a></td>";

                        echo "</tr>";
                    }
                    $i++;
                }
            } else {
                echo "<tr>";
                echo "<td>" . html_escape($r->nomor) . "</td>";
                echo "<td>" . html_escape($r->kriteria) . "</td>";
                echo "<td>" . html_escape($r->indikasi) . "</td>";
                if (!empty($r->kriteria_unit)) {
                    echo "<td>" . html_escape($r->level) . "</td>";
                    echo "<td>" . html_escape($r->kriteria_unit_name) . "</td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td><select name=\"kriteria_unit_approve[]\"  class=\"form-select pilihan_kriteria\">";
                    if (isset($r->kriteria_unit_approve)) {
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i == $part2) {
                                echo "<option value='$part1.$i' selected>$i</option>";
                            } else {
                                echo "<option value='$part1.$i'>$i</option>";
                            }
                        }
                    }

                    echo "</select></td>";
                    echo "<td><select name=\"pilihan[]\"  class=\"form-select pilihan\">
                                <option value=\"$stat\">$stat</option>
                                <option value=\"Approved\">Approve</option>
                                <option value=\"Rejected\">Reject</option>
                            </select><input type=\"text\" name=\"id_approve[]\" value=\"" . html_escape($r->id) . "\" hidden/>
                            </td>";
                    echo "<td><textarea name=\"alasan[]\" class=\"form-control alasan[]\" rows=\"2\"></textarea></td>";
                } else {
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                }


                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>