<hr>
<h4>UNIT : <?=$unit?></h4>

<table id="kt_datatable_fixed_header" class="table align-middle table-bordered table-hover table-striped table-row-bordered" >
    <thead>
        <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B">
            <th style="text-align: center; color:white;" class="min-w-200"> KRITERIA </th>
            <th style="text-align: center; color:white;"  class="min-w-200px"> SUB KRITERIA </th>
            <th style="text-align: center; color:white;"  class="min-w-200px"> INDIKASI </th>
            <th style="text-align: center; color:white;"  class="min-w-200px"> LEVEL </th>
            <th style="text-align: center; color:white;"  class="min-w-200px"> KETERANGAN </th>
            <th style="text-align: center; color:white;"  class="min-w-200px"> EVIDENCE </th>
            <th style="text-align: center; color:white;"  class="min-w-200px"> FILE </th>
        </tr>
    </thead>
    <tbody>
        <?php
        // var_dump($lst_sub_dtl);
            foreach($lst_dtl as $r){
                $i = 1;
                $sub = array();
                foreach($lst_sub_dtl as $l){
                    if($r->kriteria_unit == $l->dtl_case_id){
                        $sub[] = $l;
                    }
                }
                // $subdtl = array_filter($lst_sub_dtl, function($data) use ($r) {
                //     return $data['dtl_case_id'] == $r->nomor;
                // });
                $row = count($sub);
                $hasil = $r->status == 'Approved' ? $r->level : 0;
                if($row > 0){
                    echo "<tr>";
                    echo "<td rowspan='$row'>" . html_escape($r->kriteria) . "</td>";
                    echo "<td rowspan='$row'>" . html_escape($r->sub_kriteria) . "</td>";
                    echo "<td rowspan='$row'>" . html_escape($r->indikasi) . "</td>";
                    echo "<td rowspan='$row' style='text-align:center'>" . html_escape($hasil) . "</td>";
                    echo "<td rowspan='$row'>" . html_escape($r->kriteria_unit_name) . "</td>";
                    foreach($sub as $l){
                        if($i == 1){
                            echo "<td>" . html_escape($l->evidence) . "</td>";
                            $url = base_url()."data_uploads/mrwi/" . html_escape($l->uploads);
                            echo "<td style=\"text-align:center\"><a href=\"$url\" target=\"_blank\" class=\"btn btn-text-danger btn-hover-light-danger btn-sm\">
                                <i class=\"fa fa-file-pdf\"></i> PDF</a>
                            </td>";
                            echo "</tr>";
                        }
                        else{
                            echo "<tr>";
                            echo "<td>" . html_escape($l->evidence) . "</td>";
                            $url = base_url()."data_uploads/mrwi/" . html_escape($l->uploads);
                            echo "<td style=\"text-align:center\"><a href=\"$url\" target=\"_blank\" class=\"btn btn-text-danger btn-hover-light-danger btn-sm\">
                                <i class=\"fa fa-file-pdf\"></i> PDF</a>
                            </td>";
                            echo "</tr>";
                        }
                    }
                } else {
                    echo "<tr>";
                    echo "<td>" . html_escape($r->kriteria) . "</td>";
                    echo "<td>" . html_escape($r->sub_kriteria) . "</td>";
                    echo "<td>" . html_escape($r->indikasi) . "</td>";
                    echo "<td style='text-align:center'>" . html_escape($hasil) . "</td>";
                    echo "<td>" . html_escape($r->kriteria_unit_name) . "</td>";
                    echo "<td></td>";
                    echo "</tr>";
                }
            }
        ?>
    </tbody>
</table>
