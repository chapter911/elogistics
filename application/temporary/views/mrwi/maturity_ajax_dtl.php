
<?php
if (!empty($subdtl)) {
    echo "<div class=\"col-md-12 pe-md-10 mb-10 mb-md-0\">";

    echo "<input type=\"text\" hidden id='dtls_id' value='" . html_escape($subdtl[0]->dtl_id) . "'>";
    if(!empty($dtl_id)){
        $id_dtl =    $dtl_id->id;
    }else{
        $id_dtl = 0;
    }
    $status_dtl = "Submitted";
    if($id_dtl != 0){
        if($dtl_id->status != 'Submitted' || !empty($dtl_id->remark)){
            echo "<h2>Status : " . html_escape($dtl_id->status) . " - " . html_escape($dtl_id->remark) ."</h2>";
            echo "<hr>";
        }

        $status_dtl = $dtl_id->status;
    }

    echo "<input type=\"text\" hidden id='status_dtl' value='" . html_escape($status_dtl) . "'>";

    echo "<h2 class=\"text-gray-800 fw-bold mb-4\">" . html_escape($subdtl[0]->hdr_kriteria) . "</h2>";
    echo "<input type=\"text\" hidden  id='id_case' value='$id_dtl'>";
    foreach ($subdtl as $d) {
?>
        <div class="mb-4 fs-6 ps-10">
            <div class="form-check form-check-custom form-check-solid">
                <input class="form-check-input" type="radio" onclick="tetapkan()" name="pilihan" value="<?= html_escape($d->id) ?>" <?php if($d->pilih) echo "checked"?> />
                <label class="form-check-label" style="color:black">
                    <b><i>(Level <?=html_escape($d->level)?>)</i></b> <?= html_escape($d->kriteria); ?>
                </label>
            </div>
        </div>

<?php
    }
    // echo "<button class=\"btn btn-primary\" onclick=\"tetapkan()\">Tetapkan</button>";
    echo "<div class=\"col-md-12 pe-md-10 mb-10 mb-md-0\">";
    echo "<hr>";
}
?>
