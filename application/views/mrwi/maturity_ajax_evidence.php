<?php
if (!empty($data_evidence)) {
?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="w-1200px">Evidence</th>
                    <th class="w-200px">Document</th>
                    <th class="min-w-100px"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data_evidence as $r) {
                    echo "<tr>";
                    echo "<td>" . html_escape($r->evidence) . "</td>";
                    if (!empty($r->uploads)) {
                        echo "<td><a href='" . base_url() . "data_uploads/mrwi/" . html_escape($r->uploads) . "' target='_blank'>files</a></td>";
                    } else {
                        echo "<td>-</td>";
                    }

                    echo "<td><button class='btn btn-success btn-sm btn_upload' onclick=\"isi_form('" . html_escape($r->id) . "', '" . html_escape($r->evidence) . "', '" . html_escape($r->id_trn) . "')\" data-bs-toggle='modal' data-bs-target='#kt_modal_add_user'>Upload</button></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
}
?>