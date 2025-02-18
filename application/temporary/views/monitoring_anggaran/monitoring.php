<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">SKKI <?= $this->uri->segment(2); ?></h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_MonitoringAnggaran/exportMonitoring" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">No SKKI</label>
                        <select id="id_skki" name="id_skki" class='form-select select2' data-placeholder='Pilih No SKKI'
                            onchange="getSKKITable()">
                            <?php foreach ($no_skki as $d) { ?>
                            <option value='<?= html_escape($d->id); ?>'><?= html_escape($d->no_skki); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-6"></div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Add</label>
                        <button type="button" class="btn btn-warning btn-block form-control" data-bs-toggle="modal"
                            data-bs-target="#add_data">
                            <i class="fa-solid fa-plus"></i> &nbsp; Tambah Anggaran
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
        <br />
        <div id="ajaxContainer"></div>
    </div>
</div>

<div class="modal fade" id="add_data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Tambah Anggaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_MonitoringAnggaran/Save" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Nomor SKKI</label>
                            <select name="id_skki" class='form-select select2' data-placeholder='Pilih SKKI'>
                                <?php foreach($no_skki as $d){ ?>
                                <option value='<?= html_escape($d->id); ?>'><?= html_escape($d->no_skki); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required form-label">Material</label>
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered tblMaterial">
                                    <thead>
                                        <tr style="background-color: #008B8B;">
                                            <th width="120px" style="text-align: center; color: white;"> MATERIAL </th>
                                            <th width="120px" style="text-align: center; color: white;"> JENIS </th>
                                            <th width="120px" style="text-align: center; color: white;"> VOLUME </th>
                                            <th width="120px" style="text-align: center; color: white;"> HARGA SATUAN
                                            </th>
                                            <th width="120px" style="text-align: center; color: white;"> KETERANGAN
                                            </th>
                                            <th width="120px" style="text-align: center; color: white;"> ACTIONS </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group row m-b-10">
                                <div class="col-md-9">
                                    <button type="button" class="btn btn-primary" onclick="insertMaterial()">Tambah
                                        Material</button>
                                </div>
                            </div>
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

<div class="modal fade" id="edit_data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Edit Anggaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_MonitoringAnggaran/Update" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Material</label>
                            <input type="hidden" name="id_skki" id="id_skki_edit" class="form-control" required
                                readonly />
                            <input type="hidden" name="material_id" id="material_id_edit" class="form-control" required
                                readonly />
                            <input type="text" id="material_edit" class="form-control" placeholder="Material" required
                                readonly />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Jenis</label>
                            <select name='is_mdu' id='is_mdu_edit' class='form-select select2'
                                data-placeholder='Pilih Jenis' required>
                                <option value='1'>MDU</option>
                                <option value='0'>NON MDU</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Volume</label>
                            <input type="number" step="0.01" name="volume" id="volume_edit" class="form-control"
                                placeholder="Volume" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Harga Satuan</label>
                            <input type="number" name="harga" id="harga_edit" class="form-control"
                                placeholder="Harga Satuan" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    getSKKITable();
});

function getSKKITable() {
    $.ajax({
        url: "<?= base_url() ?>C_MonitoringAnggaran/getSKKITable",
        type: "POST",
        data: {
            id_skki: $("#id_skki").val(),
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
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
        }
    });
}

function insertMaterial() {
    $('.tblMaterial tbody:last-child').append(
        "<tr> " +
        " <td style = 'text-align: center' > " +
        "<select name='material[]' id='material[]' class='form-select material' data-dropdown-parent='#add_data' data-control='select2' data-placeholder='Pilih Material' required>" +
        "<option></option>" +
        <?php foreach ($material as $d) { ?> "<option value='<?= html_escape($d->id); ?>'><?= html_escape($d->id); ?> - <?= html_escape($d->material); ?></option>" +
        <?php } ?> "</select>" +
        "</td>" +
        " <td style = 'text-align: center' > " +
        "<select name='is_mdu[]' id='is_mdu[]' class='form-select material' data-dropdown-parent='#add_data' data-control='select2' data-placeholder='Pilih Jenis' required>" +
        "<option value='1'>MDU</option>" +
        "<option value='0'>NON MDU</option>" +
        "</select>" +
        "</td>" +
        "<td style='text-align: center;'><input type='number'  name='volume[]' class='form-control' required/></td>" +
        "<td style='text-align: center;'><input type='number'  name='harga[]' class='form-control ' required/></td>" +
        "<td style='text-align: center;'><input type='text'  name='keterangan[]' class='form-control '/></td>" +
        "<td style='text-align: center;'><button type='button' style='width:80px' class='btn btn-danger' onclick='deleteRow(this)'>HAPUS</button></td>" +
        "</tr>"
    );

    $('.material').select2({
        placeholder: "Pilih Material",
    });
}

function deleteRow(loc) {
    $(loc).parent().parent().remove();
}

function editSKKI(id_skki, material) {
    $.ajax({
        url: "<?= base_url() ?>C_MonitoringAnggaran/getMaterialSKKI",
        type: "post",
        data: {
            id_skki: id_skki,
            material: material,
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
            $('#id_skki_edit').val(id_skki);
            $('#material_id_edit').val(data[0]['material_id']);
            $('#material_edit').val(data[0]['material_id'] + " - " + data[0]['material']);
            $('#is_mdu_edit').val(data[0]['is_mdu'] == "MDU" ? 1 : 0).trigger('change');
            $('#volume_edit').val(data[0]['volume_skki']);
            $('#harga_edit').val(data[0]['harga_skki']);
            $("#edit_data").modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}

function hapusMaterialSKKI(id_skki, material) {
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Anda tidak dapat mengembalikan data ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        customClass: {
            cancelButton: 'btn btn-secondary',
            confirmButton: 'btn btn-danger'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= base_url() ?>C_MonitoringAnggaran/hapusMaterialSKKI",
                type: "post",
                data: {
                    id_skki: id_skki,
                    material: material,
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
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                    console.log(textStatus, errorThrown);
                }
            });
        }
    })
}

function removeSpace() {
    $('#no_skki').val($.trim($('#no_skki').val().toUpperCase()));
}
</script>