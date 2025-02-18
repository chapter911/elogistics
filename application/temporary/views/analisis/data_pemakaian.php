<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Data Pemakaian</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Analisis/exportDataPemakaian" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select select2" data-placeholder="Pilih Tahun"
                            onchange="filterData()">
                            <?php for($i = date('Y') - 1; $i >= 2020; $i--) { ?>
                            <option value="<?= $i; ?>"><?= $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit</label>
                        <select name="unit" id="unit" class="form-select select2" data-placeholder="Pilih Unit"
                            onchange="filterData()">
                            <option value="*">ALL</option>
                            <?php foreach ($unit as $u) { ?>
                            <option value="<?= html_escape($u->id); ?>"><?= html_escape($u->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2"></div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Upload</label>
                        <button type="button" class="btn btn-warning btn-block form-control" data-bs-toggle="modal"
                            data-bs-target="#modal_upload">
                            <i class="fa-solid fa-upload"></i>Upload Data
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
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white">TAHUN PEMAKAIAN</th>
                            <th style="text-align: center; color: white">NORMALISASI</th>
                            <th style="text-align: center; color: white">MATERIAL DESCRIPITON</th>
                            <!-- <th style="text-align: center; color: white">221</th>
                            <th style="text-align: center; color: white">222</th>
                            <th style="text-align: center; color: white">261</th>
                            <th style="text-align: center; color: white">262</th> -->
                            <th style="text-align: center; color: white">TAHUNAN</th>
                            <th style="text-align: center; color: white">BULANAN</th>
                            <th style="text-align: center; color: white">MINGGUAN</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Grafik Pemakaian</h5>
    </div>
    <div class="collapse p-5 show">
        <div class="row">
            <div class="col-3">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">Unit</label>
                    <select name="unit_grafik" id="unit_grafik" class="form-select select2"
                        data-placeholder="Pilih Unit" onchange="getGrafik()">
                        <option value="*">ALL</option>
                        <?php foreach ($unit as $u) { ?>
                        <option value="<?= html_escape($u->id); ?>"><?= html_escape($u->name); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">Material</label>
                    <select name="id_material" id="id_material" class="form-select select2"
                        data-placeholder="Pilih Material" onchange="getGrafik()">
                        <option></option>
                        <?php foreach ($material as $m) { ?>
                        <option value="<?= html_escape($m->id); ?>"><?= html_escape($m->id) . " - " . html_escape($m->material); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="ajaxGrafik"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_upload" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h5>List Detail Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_Analisis/import" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col">
                            <label class="required fw-semibold fs-6 mb-2">Tahun Pemakaian</label>
                            <select name="tahun" id="tahun" class="form-select select2" data-placeholder="Pilih Tahun">
                                <?php for($i = date("Y"); $i > 2015; $i--) { ?>
                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">File Dokumen Pemakaian (Excel)</label>
                            <input type="file" name="upload_file" accept=".xls,.xlsx" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="File Dokumen" required />
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
        "ajax": {
            "url": "<?= base_url() ?>C_Analisis/AjaxDataPemakaian",
            "type": "post",
            "beforeSend": function() {
                Swal.fire({
                    title: 'Mohon Tunggu',
                    html: 'Mengambil Data',
                    allowOutsideClick: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                });
                Swal.showLoading();
            },
            "data": function(data) {
                data.tahun = $("#tahun").val(),
                data.unit = $("#unit").val(),
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

function getGrafik() {
    $.ajax({
        url: "<?= base_url() ?>C_Analisis/grafikPemakaian",
        type: "post",
        data: {
            id_material: $('#id_material').val(),
            unit_grafik: $('#unit_grafik').val(),
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
            $("#ajaxGrafik").html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}
</script>