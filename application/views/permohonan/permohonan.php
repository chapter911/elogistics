<div class="card mb-6">
    <div class="card-header header-elements">
        <h3 class="mb-0 me-2">Daftar Permohonan</h3>
        <div class="card-header-elements ms-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_data"
                onclick="resetPermohonan();">
                <span class="tf-icon ti ti-plus ti-xs me-1"></span>Buat Permohonan
            </button>
            <a href="<?= base_url('C_Permohonan/exportPermohonan') ?>" class="btn btn-primary">
                <span class="fa-solid fa-file-excel"></span> &nbsp; Export
            </a>
        </div>
    </div>
    <div class="collapse show">
        <div class="row">
            <div class="card-datatable p-10 text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white;"> NO </th>
                            <th style="text-align: center; color: white;"> UNIT </th>
                            <th style="text-align: center; color: white;"> NO PR </th>
                            <th style="text-align: center; color: white;"> TANGGAL PR </th>
                            <th style="text-align: center; color: white;"> ANGGARAN </th>
                            <th style="text-align: center; color: white;"> NOMOR ANGGARAN </th>
                            <th style="text-align: center; color: white;"> PEKERJAAN </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> NO STO </th>
                            <th style="text-align: center; color: white;"> STATUS </th>
                            <th style="text-align: center; color: white;"> FILE </th>
                            <th style="text-align: center; color: white;"> ACTIONS </th>
                            <th style="text-align: center; color: white;"> LACAK </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($data as $d) { ?>
                        <tr>
                            <td style="text-align: center;"> <?= $i++ . "."; ?> </td>
                            <td> <?= html_escape($d->unit_name); ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->no_pr); ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->tanggal_pr); ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->basket); ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->no_anggaran); ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->pekerjaan); ?> </td>
                            <td style="text-align: center;">
                                <button class="btn btn-secondary btn-sm w-100" onclick="showMaterial(<?= html_escape($d->no_pr); ?>);"><?= html_escape($d->material); ?></button>
                            </td>
                            <td style="text-align: center;"> <?= html_escape($d->no_sto); ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->is_sto_released) ? "STO TERBIT" : html_escape($d->status); ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if (!empty(html_escape($d->file_tug))) { ?>
                                <a href="<?= base_url() . html_escape($d->file_tug_location) . '/' . html_escape($d->file_tug)  ?>.pdf"
                                    class="btn btn-text-danger btn-hover-light-danger btn-sm" target="_blank">
                                    <i class="fa fa-file-pdf"></i> TUG 5
                                <?php } ?>
                                <?php if (!empty(html_escape($d->file_surat))) { ?>
                                <a href="<?= base_url() . html_escape($d->file_tug_location) . '/' . html_escape($d->file_surat)  ?>.pdf"
                                    class="btn btn-text-danger btn-hover-light-danger btn-sm" target="_blank">
                                    <i class="fa fa-file-pdf"></i> SURAT
                                <?php } ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if($d->status == "PERMOHONAN"){ ?>
                                    <button class="btn btn-outline-secondary btn-sm waves-effect waves-light" onclick="uploadSurat(<?= $d->no_pr; ?>)">
                                        <i class="fa-solid fa-upload"></i> UPLOAD SURAT
                                    </button>
                                <?php } ?>
                            </td>
                            </td>
                            <td style="text-align: center;">
                                <button class="btn btn-outline-secondary btn-sm waves-effect waves-light" onclick="alert('Fitur Sedang DiKembangkan')">
                                    <i class='fa-regular fa-eye'></i>
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Form Permohonan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="add_data_form" class="form" action="<?= base_url(); ?>C_Permohonan/PermohonanSave" method="POST"
                enctype="multipart/form-data">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Nomor PR</label>
                            <input type="number" name="no_pr" id="no_pr" class="form-control" placeholder="Nomor PR" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Tanggal PR</label>
                            <input type="date" name="tanggal_pr" id="tanggal_pr" onclick="this.showPicker()"
                                class="form-control" placeholder="Tanggal PR" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Sumber Anggaran</label>
                            <select name="basket_id" id="basket_id" class="select2"
                                data-placeholder="Pilih Sumber Anggaran" required>
                                <option></option>
                                <?php foreach($basket as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->basket); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Jenis Anggaran</label>
                            <select name="jenis_anggaran" id="jenis_anggaran" class="select2"
                                data-placeholder="Pilih Sumber Anggaran" required>
                                <option></option>
                                <option value="MURNI">MURNI</option>
                                <option value="LANJUTAN">LANJUTAN</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Nomor Anggaran</label>
                            <input type="text" name="no_anggaran" id="no_anggaran" class="form-control"
                                placeholder="Nomor Anggaran" maxlength="40" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Pekerjaan</label>
                            <select name="pekerjaan" id="pekerjaan" class="select2" data-placeholder="Pilih Pekerjaan" required>
                                <option></option>
                                <?php foreach($jenis_pekerjaan as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->jenis); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Dokumen Pendukung TUG 5 (PDF)</label>
                            <input type="file" name="file_tug" id="file_tug" accept=".pdf" class="form-control"
                                placeholder="File Pendukung TUG" required/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Surat</label>
                            <input type="file" name="file_surat" id="file_surat" accept=".pdf" class="form-control"
                                placeholder="File Surat"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Material</label>
                            <table id="table_insert_material" class="table" style="width:100%">
                                <thead>
                                    <tr style="background-color: #008B8B">
                                        <th style="text-align: center; color: white; min-width: 300px;"> MATERIAL </th>
                                        <th style="text-align: center; color: white;"> VOLUME </th>
                                        <th style="text-align: center; color: white;"> ACTIONS </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <button type="button" class="btn btn-primary" onclick="insertMaterial();">Tambah
                                Material</button>
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

<div class="modal fade" id="modal_material" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h5>List Detail Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y">
                <table id="table_material" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color:white;"> NO </th>
                            <th style="text-align: center; color:white;"> KATEGORI </th>
                            <th style="text-align: center; color:white;"> MATERIAL </th>
                            <th style="text-align: center; color:white;"> VOLUME PERMINTAAN </th>
                            <th style="text-align: center; color:white;"> VOLUME DISETUJUI </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_upload_surat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Upload Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_Permohonan/update_surat" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">File Surat</label>
                            <input type="hidden" name="no_pr_surat" id="no_pr_surat" class="form-control" required/>
                            <input type="file" name="file_surat" id="file_surat" class="form-control"
                                accept=".pdf" placeholder="pdf" required />
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
var tabledetail;

$(document).ready(function() {
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 10,
    });
});

function showMaterial(no_pr) {
    tabledetail = $("#table_material").DataTable({
        "destroy": true,
        "bPaginate": false,
        "ajax": {
            "url": "<?= base_url() ?>C_Permohonan/getMaterialPermohonan",
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
                data.no_pr = no_pr,
                data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
            },
            "complete": function(response) {
                console.log(response);
                Swal.close();
                $("#modal_material").modal('show');
            },
            "error": function(jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        }
    });
}

function resetPermohonan() {
    insertMaterial();
}

function insertMaterial() {
    $('#table_insert_material > tbody:last-child').append(
        "<tr>" +
        "<td style='text-align: center;'>" +
        "<select name='material[]' id='material[]' class='form-select' required>" +
        "<option></option>" +
        <?php foreach($material as $d) { ?> "<option value='<?= html_escape($d->id); ?>'><?= html_escape($d->id); ?> - <?= html_escape($d->material); ?></option>" +
        <?php } ?> "</select>" +
        "</td>" +
        "<td style='text-align: center'><input type='number' name='volume[]' class='form-control' required/></td>" +
        "<td style='text-align: center'><button type='button' class='btn btn-danger btn-sm' onclick='deleteRow(this)'>HAPUS</button>" +
        "</td>" +
        "</tr>"
    );

    $('#material\\[\\]').select2({
        placeholder: "Pilih Material",
        dropdownParent: $('#add_data')
    });
}


$('#add_data_form').on('submit', function(e) {
    var material_values = $("select[name='material[]']").map(function() {
        return $(this).val();
    }).get();
    var duplicates = material_values.some(function(item, idx) {
        return material_values.indexOf(item) != idx;
    });
    if (duplicates) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Material tidak boleh duplikat!',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
        });
        e.preventDefault();
        return false;
    } else {
        $('#add_data_form').submit();
    }
});

function deleteRow(loc) {
    $(loc).parent().parent().remove();
}

function uploadSurat(no_pr) {
    $('#no_pr_surat').val(no_pr);
    $("#modal_upload_surat").modal('show');
}
</script>