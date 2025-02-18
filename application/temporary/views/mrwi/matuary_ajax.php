<!-- <ul class="pagination pagination-outline"> -->
<?php $i = 1;
foreach ($dtl as $h) {
    if($i < 4){
        echo "<div class=\"col-md-4 py-1\">";
    }else{
        echo "<div class=\"col-md-6 py-1\">";
    }

    echo "<button class=\"sub btn btn-outline-primary waves-effect waves-light btn-sm form-control\" id=\"dtl-$i\" onclick=\"get_dtl_isi('" . html_escape($h->nomor) . "', $i)\"> <span class='fa-solid fa-circle me-2 fs-4' id='btn_nomor_$i'></span> " . html_escape($h->kriteria) . "</button>";
 
    echo "</div>";
    $i++;
} ?>
<!-- </ul> -->
<hr>

<script>

</script>

<!-- <div class="col-sm-3">
    <button class="hdr btn btn-light btn-sm form-control" id="hdr-<?= $h->id ?>" onclick="get_dtl('<?= $h->id ?>')"><?= $h->kriteria ?></button>
</div>

<li class="page-item sub" id="dtl-<?= $i ?>"><a class="page-link" style="cursor: pointer;" onclick="get_dtl_isi('<?= $h->nomor ?>', <?= $i ?>)"><?= $h->kriteria ?></a></li> -->