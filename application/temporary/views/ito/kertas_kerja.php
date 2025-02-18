<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Kertas Kerja Persediaan</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_ITO/Export" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit</label>
                        <select name="unit" id="unit" class="form-select select2" data-placeholder="Pilih Unit"
                            onchange="filterData()">
                            <option value="*">- ALL UNIT -</option>
                            <?php foreach($unit as $d) { ?>
                            <option value="<?= html_escape($d->kode_unit); ?>"><?= html_escape($d->kode_unit) . ' - ' . html_escape($d->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Periode</label>
                        <input type="month" name="periode" id="periode" class="form-control mb-3 mb-lg-0"
                            onfocus="this.showPicker()" value="<?= date('Y-m'); ?>" onchange="filterData()" />
                    </div>
                </div>
                <div class="col-2">
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Upload</label>
                        <button type="button" class="btn btn-warning btn-block form-control" data-bs-toggle="modal"
                            data-bs-target="#modal_upload_data">
                            <i class="fa-solid fa-upload"></i> &nbsp; Upload Data
                        </button>
                    </div>
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

<div class="modal fade" id="modal_upload_data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Upload Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_ITO/import" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="form-label">Periode</label>
                            <input type="month" name="periode_upload" id="periode_upload" class="form-control"
                                placeholder="Periode" onclick="this.showPicker()" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">File Dokumen Data (Excel)</label>
                            <input type="file" name="upload_file" id="upload_file" class="form-control"
                                accept=".xls,.xlsx" placeholder="xls,.xlsx" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <a href="<?= base_url(); ?>data_uploads/Datamaterial/format.xlsx" download target="_blank"
                                class="btn btn-success form-control">Download Format</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal"
                        onclick="showLoading()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    filterData();
});

function filterData() {
    $.ajax({
        url: "<?= base_url() ?>C_ITO/getKertasKerjaData",
        type: "POST",
        data: {
            unit: $('#unit').val(),
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

function showLoading() {
    Swal.fire({
        title: 'Menyimpan Data',
        html: 'Harap untuk tidak meninggalkan halaman ini',
        allowOutsideClick: false,
        showCancelButton: false,
        showConfirmButton: false,
    });
    Swal.showLoading();
}
</script>