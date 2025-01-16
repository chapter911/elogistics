<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">ITO Gabungan</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_ITO/Export" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Periode</label>
                        <input type="month" name="periode" id="periode" class="form-control mb-3 mb-lg-0"
                            onfocus="this.showPicker()" value="<?= date('Y-m'); ?>" onchange="filterData()" />
                    </div>
                </div>
                <div class="col-7">
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Export</label>
                        <button type="submit" class="btn btn-primary btn-block form-control">
                            <i class="fa-solid fa-file-excel"></i> &nbsp; Export
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="card-datatable text-nowrap">
                <div id="ajax-container"></div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    filterData();
});

function filterData() {
    $.ajax({
        url: "<?= base_url() ?>C_ITO/ajaxIndex",
        type: "POST",
        data: {
            periode: $('#periode').val(),
            <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
        },
        beforeSend: function() {
            Swal.fire({
                title: 'Mohon Tunggu',
                html: 'Mengambil Data',
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
            Swal.showLoading();
        },
        success: function(response) {
            Swal.close();
            $("#ajax-container").html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
        }
    });
}
</script>