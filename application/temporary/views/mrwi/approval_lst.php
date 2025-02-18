<table id="kt_datatable_fixed_header" class="table align-middle table-bordered table-hover table-striped table-row-bordered" style="white-space: nowrap;">
    <thead>
        <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B">
            <th style="text-align: center; color:white;"> UNIT </th>
            <th style="text-align: center; color:white;"> STATUS PENGERJAAN </th>
            <th style="text-align: center; color:white;"> ACTION </th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($lst_mrwi as $r){
                echo "<tr>";
                echo "<td>" . html_escape($r->name) . "</td>";
                if($r->is_completed == 0){
                    echo "<td>Draft</td>";
                }else{
                    echo "<td>Submitted</td>";
                }

                echo "<td style=\"text-align:center\"><a class=\"btn btn-outline-secondary btn-sm\" onclick=\"view('" . html_escape($r->id) . "', '" . html_escape($r->name) . "', '" . html_escape($r->is_completed) . "')\" data-bs-toggle=\"modal\" data-bs-target=\"#kt_modal_add_user\"><i class='fa-regular fa-eye'></i></a></td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>