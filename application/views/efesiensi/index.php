<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Efesiensi</h5>
    </div>
    <div class="card-body pt-3">
        <form class="form" action="<?= base_url(); ?>C_Efesiensi/exportIndex" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
                value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit</label>
                        <select name="unit" id="unit" class="form-control select2" data-control="select2"
                            data-placeholder="Pilih Unit" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($unit as $u) { ?>
                            <option value="<?= html_escape($u->id); ?>"><?= html_escape($u->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-6"></div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Add</label>
                        <button type="button" class="btn btn-warning btn-block form-control" data-bs-toggle="modal"
                            data-bs-target="#add_data" onclick="insertRow()">
                            <i class="fa-solid fa-plus"></i> &nbsp; Tambah data
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
                            <th style="text-align: center; vertical-align: middle; color: white;"> NO </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> UNIT </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> PELANGGAN / LOKASI
                            </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> JENIS PENGUKURAN
                            </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> ANGGARAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> MATERIAL </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> STATUS </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TANGGAL INPUT </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> ACTION </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen p-9" role="document">
        <form action="<?= base_url() ?>C_Efesiensi/Save" method="POST" class="form">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
                value="<?=$this->security->get_csrf_hash();?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Pembuatan Efesiensi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <label class="required form-label">Unit</label>
                            <select class="form-control select2" name="unit_add" id="unit_add" required>
                                <option></option>
                                <?php foreach ($unit as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->name); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Segment Tegangan</label>
                            <select class="form-control select2" name="segment_tegangan" id="segment_tegangan" required>
                                <option></option>
                                <option value="rendah">Tegangan Rendah</option>
                                <option value="menengah">Tegangan Menengah</option>
                                <option value="tinggi">Tegangan Tinggi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Jenis Pengukuran</label>
                            <select class="form-control select2" name="pengukuran" id="pengukuran" required>
                                <option></option>
                                <option value="1">Langsung</option>
                                <option value="0">Tidak Langsung</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Anggaran</label>
                            <select class="form-control select2" name="anggaran" id="anggaran" required>
                                <option></option>
                                <option value="investasi">Investasi</option>
                                <option value="operasi">Operasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">Pelanggan / Lokasi</label>
                            <input type="text" class="form-control" name="pelanggan" id="pelanggan" placeholder=""
                                value="" autocomplete="off" />
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="required form-label">Material</label>
                        <div class="table-responsive">
                            <table id="table-insert-material" class="table table-striped table-bordered tblMaterial"
                                style="width: 100%">
                                <thead>
                                    <tr style="background-color: #008B8B;">
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 5%;">
                                            # </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 30%;">
                                            MATERIAL </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 5%;">
                                            VOLUME </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 10%;">
                                            RASIO CT </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 10%;">
                                            KETERANGAN </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 10%;">
                                            ACTIONS </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger"
                        data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_material" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h5>List Detail Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <div id="materialEfesiensi"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_update" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h5>Update Pemenuhan Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <form id="kt_modal_tanggal_sip" class="form"
                    action="<?= base_url() ?>C_Efesiensi/update_approved_volume" method="POST">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
                        value="<?=$this->security->get_csrf_hash();?>">
                    <input type="hidden" name="id_efesiensi" id="id_efesiensi" class="form-control mb-3 mb-lg-0"
                        required />
                    <div class="table-responsive" id="updateMaterialEfesiensi"></div>
                    <input class="form-check-input" type="checkbox" name="is_selesai" id="is_selesai"
                        onchange="updateVolume()"> Selesaikan Pemenuhan </input>
                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                    </div>
                </form>
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
        "pageLength": 25,
        "columnDefs": [{
            "className": "dt-body-center",
            "targets": [3, 4, 5, 6, 7, 8]
        }],
        "lengthMenu": [
            [10, 25, -1],
            [10, 25, 'All']
        ],
        "ajax": {
            "url": "<?= base_url() ?>C_Efesiensi/ajaxIndex",
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

function insertRow() {
    $('#table-insert-material > tbody:last-child').append(
        `<tr>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-primary" onclick="insertRow()">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </td>
            <td style="text-align: center;">
                <select name='material[]' id='material[]' onchange='setRasio(this)' class='form-control select2' required>
                    <option></option>
                    <?php foreach ($material as $d) { ?>
                        <option value='<?= html_escape($d->id); ?>'><?= $d->id; ?> - <?= html_escape($d->material); ?></option>
                    <?php } ?>
                </select>
            </td>
            <td style="text-align: center;">
                <input type='number' name='volume[]' onkeyup='kalkulasi(this)' class='form-control' required/>
            </td>
            <td style="text-align: center;">
                <select name='rasio[]' id='rasio[]' class='form-control'>
                    <option value="">-</option>
                    <option value="10/5">10/5</option>
                    <option value="15/5">15/5</option>
                    <option value="20/5">20/5</option>
                    <option value="30/5">30/5</option>
                    <option value="40/5">40/5</option>
                    <option value="50/5">50/5</option>
                    <option value="60/5">60/5</option>
                    <option value="75/5">75/5</option>
                    <option value="100/5">100/5</option>
                    <option value="150/5">150/5</option>
                    <option value="200/5">200/5</option>
                    <option value="300/5">300/5</option>
                    <option value="400/5">400/5</option>
                    <option value="800/1">800/1</option>
                    <option value="800/5">800/5</option>
                    <option value="2000/1">2000/1</option>
                    <option value="2000/5">2000/5</option>
                </select>
            </td>
            <td style="text-align: center;">
                <input type='text' name='keterangan[]' class='form-control' required/>
            </td>
            <td style="text-align: center;">
                <button type='button' class='btn btn-danger btn-sm' onclick='deleteRow(this)'>HAPUS</button>
            </td>
        </tr>`
    );

    $('#table-insert-material select[name="material[]"]').last().select2({
        placeholder: "Pilih Material",
        dropdownParent: $('#add_data')
    });
}

function deleteRow(loc) {
    $(loc).parent().parent().remove();
}

function getMaterialEfesiensi(id) {
    $.ajax({
        url: "<?= base_url() ?>C_Efesiensi/materialEfesiensi/" + id,
        type: "GET",
        success: function(d) {
            $("#modal_material").modal('show');
            $("#materialEfesiensi").html(d);
        }
    });
}

function updateEfesiensi(id) {
    $.ajax({
        url: "<?= base_url() ?>C_Efesiensi/materialEfesiensi/" + id,
        type: "GET",
        success: function(d) {
            $('#is_selesai').prop('checked', false);
            $("#id_efesiensi").val(id);
            $("#updateMaterialEfesiensi").html(d);
            $("#modal_update").modal("show");
        }
    });
}

function updateVolume() {
    if ($('#is_selesai').is(':checked')) {
        for (var i = 0; i < $('input[name="volume[]"]').length; i++) {
            $('input[name="approved_volume[]"]').eq(i).val($('input[name="volume[]"]').eq(i).val());
        }
    }
}

function deleteEfesiensi(id) {
    Swal.fire({
        title: 'Apakah Anda yakin menghapus data ini ?',
        text: "Data ini tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        customClass: {
            cancelButton: 'btn btn-label-danger',
            confirmButton: 'btn btn-primary'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= base_url() ?>C_Efesiensi/delete",
                type: "POST",
                data: {
                    id: id,
                    <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon Tunggu',
                        html: 'Menghapus',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                    Swal.showLoading();
                },
                success: function(response) {
                    Swal.close();
                    table.ajax.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                    console.log(textStatus, errorThrown);
                }
            });
        }
    });
}

function setRasio(data) {
    var material_id = $(data).val();

    $.ajax({
        url: "<?= base_url() ?>C_Efesiensi/cekUsingRatio",
        type: "POST",
        data: {
            material_id: material_id,
            <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
        },
        beforeSend: function() {
            Swal.fire({
                title: 'Mohon Tunggu',
                html: 'Cek Rasio',
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
            Swal.showLoading();
        },
        success: function(response) {
            Swal.close();
            if(response == 0){
                $(data).closest('tr').find('select[name="rasio[]"]').val('').trigger('change').attr("disabled", true).prop('required', false);
            } else {
                $(data).closest('tr').find('select[name="rasio[]"]').attr("disabled", false).prop('required', true);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}
</script>