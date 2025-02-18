<style>
    .modal-image {
        max-width: 100%; /* Allow image to span the modal width */
        height: auto; /* Maintain aspect ratio */
        display: block; /* Ensure image takes up space */
        margin: 0 auto; /* Center the image horizontally */
    }
</style>

<div class="card mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">Lokasi Layout</span>
        </h3>
    </div>
    <div class="card-body pt-3">
        <form class="form" action="<?= base_url(); ?>C_Locator/export_layout" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit</label>
                        <select name="unit" id="unit" class="form-select select2" data-placeholder="Pilih Unit" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($unit as $u) { ?>
                                <option value="<?= html_escape($u->id); ?>"><?= html_escape($u->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Gudang</label>
                        <select name="gudang" id="gudang" class="form-control select2" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($gudang as $d) { ?>
                                    <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">No Gudang</label>
                        <select name="no_gudang" id="no_gudang" class="form-control select2" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($no_gudang as $d) { ?>
                                <option value="<?= html_escape($d->no_gudang); ?>"><?= html_escape($d->no_gudang); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Rak </label>
                        <select name="rak" id="rak" class="form-control select2" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($rak as $d) { ?>
                                <option value="<?= html_escape($d->rak); ?>"><?= html_escape($d->rak); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">&nbsp;</label>
                        <button type="button" class="btn btn-warning btn-block form-control" data-bs-toggle="modal" data-bs-target="#modal_upload">
                            <i class="fa-solid fa-plus"></i>&nbsp;Upload Layout
                        </button>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block form-control">
                            <i class="fa-solid fa-file-excel"></i>&nbsp;Export
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="table-responsive">
                <table id="table" class="table align-middle table-bordered table-hover table-row-bordered" style="white-space: nowrap;">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white;"> NO </th>
                            <th style="text-align: center; color: white;"> UNIT </th>
                            <th style="text-align: center; color: white;"> GUDANG </th>
                            <th style="text-align: center; color: white;"> NOMOR GUDANG </th>
                            <th style="text-align: center; color: white;"> RAK </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> ACTIONS </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_upload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="fw-bold" id="nama_material">Upload Layout </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_modal_layout" action="<?= base_url() ?>C_Locator/layout_save" method="POST" class="form" enctype="multipart/form-data">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body px-5 my-7">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit</label>
                        <select name="unit" id="mdl_unit" class="form-select select2" data-placeholder="Pilih Kategori">
                            <option value="*">- SEMUA UNIT -</option>
                            <?php foreach ($unit as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>" <?= html_escape($d->id) == $this->session->userdata('unit_id') ? "selected" : ""; ?>><?= html_escape($d->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Gudang</label>
                        <select name="id_gudang" id="mdl_id_gudang" class="form-select select2" data-placeholder="Pilih Kategori">
                            <option value="*">- PILIH GUDANG -</option>
                            <?php foreach ($gudang as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Nomor Gudang</label>
                        <input type="number" name="no_gudang" id="mdl_no_gudang" min="0" max="10" class="form-control mb-3 mb-lg-0" placeholder="Nomor Gudang" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Rak</label>
                        <input type="text" name="rak" id="mdl_rak" maxlength="2" oninput="this.value=this.value.toUpperCase()" class="form-control mb-3 mb-lg-0" placeholder="Rak" value="" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">File Layout</label>
                        <input type="file" name="upload_file" accept=".png,.jpg,.jpeg" class="form-control mb-3 mb-lg-0" placeholder="File Dokumen" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_layout" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="fw-bold" id="nama_material">Layout </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="image_layout" src="https://e-logisticspln.com/assets/app_logo_new.png" alt="Layout Gudang" class="img-responsive modal-image"/>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="rotasi(-90)">PUTAR KIRI</button>
                <button class="btn btn-primary" onclick="rotasi(90)">PUTAR KANAN</button>
                <button type="button" data-dismiss="modal" class="btn btn-danger" data-bs-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_material" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="fw-bold" id="nama_material">Material </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 my-7">
                <div id="ajaxContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" data-bs-dismiss="modal">TUTUP</button>
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
            "columnDefs": [
                {
                    targets: [0, 2, 3, 4, 5, 6],
                    className: 'dt-body-center'
                },
            ],
            "pageLength": 25,
            "ajax": {
                "url": "<?= base_url() ?>C_Locator/getAjaxLayout",
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
                    data.unit      = $("#unit").val(),
                    data.gudang    = $("#gudang").val(),
                    data.no_gudang = $("#no_gudang").val(),
                    data.rak       = $("#rak").val(),
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

    function filterData(){
        table.ajax.reload();
    }

    function uploadLayout(unit, id_gudang, no_gudang, rak){
        $('#mdl_unit').val(unit).trigger('change');
        $('#mdl_id_gudang').val(id_gudang).trigger('change');
        $('#mdl_no_gudang').val(no_gudang);
        $('#mdl_rak').val(rak);
        $('#kt_modal_upload').modal('show');
    }

    function showImage(image_url) {
        $("#image_layout").attr("src", image_url);
        $('#modal_layout').modal('show');
    }

    function getMaterial(unit, gudang, no_gudang, rak){
        $.ajax({
            url: "<?= base_url() ?>C_Locator/getMaterialByLayout",
            type: "post",
            data: {
                unit: unit,
                gudang: gudang,
                no_gudang: no_gudang,
                rak: rak,
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
                $('#modal_material').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                console.log(textStatus, errorThrown);
            }
        });
    }

    var derajat = 0;

    function rotasi(arah){
        derajat += arah;
        $("#image_layout").css({
            "transform": "rotate(" + derajat + "deg)" // Apply rotation with CSS transform
        });
    }

    function deleteLayout(id){
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Anda tidak dapat mengembalikan data ini!",
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
                    url: "<?= base_url() ?>C_Locator/layout_delete",
                    type: "post",
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
        })
    }
</script>