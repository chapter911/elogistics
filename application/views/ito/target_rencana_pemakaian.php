<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Target Rencana Pemakaian Material</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_ITO/ExportRencanaPemakaian" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Periode</label>
                        <select name="periode_rencana" id="periode_rencana" class="form-select"  onchange="filterData()" required>
                            <?php for($i = date('Y'); $i >= 2020; $i--){ ?>
                            <option value="<?= $i; ?>" <?= ($i == date('Y')) ? 'selected' : ''; ?>><?= $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-5">
                </div>
                <div class="col-2">
                    <?php if($this->session->userdata('add') == '1'){ ?>
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Import</label>
                        <button type="button" class="btn btn-warning btn-block form-control" data-bs-toggle="modal" data-bs-target="#modal_upload">
                            <i class="fa-solid fa-file-upload"></i> &nbsp; Upload
                        </button>
                    </div>
                    <?php } ?>
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
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; vertical-align: middle; color: white;"> UNIT </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> JAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> FEB </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> MAR </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> APR </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> MEI </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> JUN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> JUL </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> AGT </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> SEP </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> OKT </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> NOV </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> DES </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_upload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_ITO/upload_rencana_pemakaian" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="form-label">Periode</label>
                            <select name="periode" id="periode" class="form-select" required>
                                <?php for($i = date('Y'); $i >= 2020; $i--){ ?>
                                <option value="<?= $i; ?>" <?= ($i == date('Y')) ? 'selected' : ''; ?>><?= $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">File Dokumen (Excel)</label>
                            <input type="file" name="upload_file" id="upload_file" class="form-control"
                                accept=".xls,.xlsx" placeholder="xls,.xlsx" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <a href="<?= base_url(); ?>data_uploads/ito/format/target_rencana_pemakaian.xlsx" download target="_blank"
                                class="btn btn-success form-control">Download Format</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var table;

$(document).ready(function() {
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "columnDefs": [{
            targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            className: 'dt-body-right'
        }, ],
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_ITO/AjaxTargetRencanaPemakaian",
            "type": "post",
            "beforeSend": function() {
                Swal.fire({
                    title: 'Mohon Tunggu',
                    html: 'Memuat Data',
                    allowOutsideClick: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                    showCloseButton: false,
                });
                Swal.showLoading();
            },
            "data": function(data) {
                data.periode = $("#periode_rencana").val(),
                data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
            },
            "complete": function(response) {
                Swal.close();
            },
            "error": function(jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        }
    });
});

function filterData() {
    table.ajax.reload();
}
</script>