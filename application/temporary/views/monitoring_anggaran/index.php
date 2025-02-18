<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Daftar SKKI</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_MonitoringAnggaran/exportIndex" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Basket</label>
                        <select name="basket_id" id="basket_id" class="select2" data-placeholder="Pilih Jenis"
                            onchange="filterData()">
                            <option value="*">- SEMUA BASKET -</option>
                            <?php foreach ($basket as $b) { ?>
                            <option value="<?= html_escape($b->id); ?>"><?= html_escape($b->basket); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Jenis Anggaran</label>
                        <select name="jenis" id="jenis" class="form-select select2" data-placeholder="Pilih Jenis"
                            onchange="filterData()">
                            <option value="*">- SEMUA ANGGARAN -</option>
                            <option value="M">MURNI</option>
                            <option value="L">LUNCURAN</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select select2" data-placeholder="Pilih Tahun"
                            onchange="filterData()">
                            <?php for($i = (date("Y")); $i >= 2020; $i--) { ?>
                            <option value="<?= $i; ?>" <?= date("Y") == $i ? "selected" : "" ?>><?= $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2"></div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Input</label>
                        <button type="button" class="btn btn-warning btn-block form-control" onclick="resetForm()" data-bs-toggle="modal" data-bs-target="#add_data">
                            <i class="fa-solid fa-add"></i> &nbsp; Input SKKI
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
                            <th style="text-align: center; color: white;"> NO </th>
                            <th style="text-align: center; color: white;"> UNIT </th>
                            <th style="text-align: center; color: white;"> BASKET </th>
                            <th style="text-align: center; color: white;"> NOMOR SKKI </th>
                            <th style="text-align: center; color: white;"> TANGGAL </th>
                            <th style="text-align: center; color: white;"> JENIS </th>
                            <th style="text-align: center; color: white;"> AKI </th>
                            <th style="text-align: center; color: white;"> TAHUN </th>
                            <th style="text-align: center; color: white;"> DOKUMEN </th>
                            <th style="text-align: center; color: white;"> ACTION </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Input SKKI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_MonitoringAnggaran/save_skkihdr" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Basket</label>
                            <select name="basket_id" id="mdl_basket_id" class="form-select select2" data-placeholder="Pilih Basket" required >
                                <option value="*">- PILIH BASKET -</option>
                                <?php foreach ($basket as $b) { ?>
                                    <option value="<?= html_escape($b->id); ?>"><?= html_escape($b->basket); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">No SKKI</label>
                            <input type="hidden" name="id_skki" id="mdl_id_skki" class="form-control" placeholder="ID SKKI" value="0" required />
                            <input type="text" name="no_skki" id="mdl_no_skki" minlength="10" class="form-control" placeholder="Nomor SKKI" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Tanggal SKKI</label>
                            <input type="date" name="tanggal" id="mdl_tanggal" class="form-control" placeholder="Tanggal SKKI" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Tahun SKKI</label>
                            <select name="tahun" id="mdl_tahun" class="form-select select2" data-placeholder="Pilih Tahun" required>
                                <option>- PILIH TAHUN -</option>
                                <?php for($i = date('Y'); $i >= 2022; $i--) { ?>
                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Jenis Anggaran</label>
                            <select name="jenis" id="mdl_jenis" class="form-select select2" data-placeholder="Pilih Jenis Anggaran" required >
                                <option value="*">- PILIH JENIS ANGGARAN -</option>
                                <option value="M">MURNI</option>
                                <option value="L">LUNCURAN</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Nilai Aki</label>
                            <input type="number" name="nilai_aki" id="mdl_nilai_aki" class="form-control" placeholder="Nilai AKI"/>
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

<div class="modal fade" id="modal_dokumen_skki" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h5>Dokumen SKKI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <div id="ajaxContainer"></div>
            </div>
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
            targets: [2, 4, 5, 7, 8, 9],
            className: 'dt-body-center'
        }, {
            targets: [6],
            className: 'dt-body-right'
        }],
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_MonitoringAnggaran/getIndexAjax",
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
                data.basket_id = $("#basket_id").val(),
                data.jenis = $("#jenis").val(),
                data.tahun = $("#tahun").val(),
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

function resetForm() {
    $('#mdl_basket_id').val('').trigger('change');
    $('#mdl_no_skki').val('').prop('readonly', false);
    $('#mdl_tanggal').val('');
    $('#mdl_tahun').val('').trigger('change');
    $('#mdl_jenis').val('').trigger('change');
    $('#mdl_nilai_aki').val('');
    $('#kt_modal_add_skki').modal('show');
}

function editForm(id) {
    $.ajax({
        url: "<?= base_url() ?>C_MonitoringAnggaran/getSKKIHDR",
        type: "POST",
        data: {
            id: id,
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
            var data = JSON.parse(response);
            $('#mdl_basket_id').val(data[0]['basket_id']).trigger('change');
            $('#mdl_id_skki').val(data[0]['id']);
            $('#mdl_no_skki').val(data[0]['no_skki']);
            $('#mdl_tanggal').val(data[0]['tanggal']);
            $('#mdl_tahun').val(data[0]['tahun']).trigger('change');
            $('#mdl_jenis').val(data[0]['jenis']).trigger('change');
            $('#mdl_nilai_aki').val(data[0]['nilai_aki']);
            $('#add_data').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
        }
    });
}

function showDokumen(id) {
    $.ajax({
        url: "<?= base_url() ?>C_MonitoringAnggaran/getDokumen",
        type: "POST",
        data: {
            id: id,
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
            $("#ajaxContainer").html(response);
            $('#modal_dokumen_skki').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
        }
    });
}
</script>