<style>
    .modal-image {
        max-width: 100%; /* Allow image to span the modal width */
        height: auto; /* Maintain aspect ratio */
        display: block; /* Ensure image takes up space */
        margin: 0 auto; /* Center the image horizontally */
    }
</style>

<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Lokasi Material
        </h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Stock/Export" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit</label>
                        <select name="unit" id="unit" class="select2" data-placeholder="Pilih Unit"
                            onchange="filterData()">
                            <option value="*">- SEMUA UNIT -</option>
                            <?php foreach($unit as $d) { ?>
                            <option value="<?= html_escape($d->id); ?>"
                                <?= html_escape($d->id) == $this->session->userdata('unit_id') ? "selected" : "";?>><?= html_escape($d->name); ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-3"></div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">QR</label>
                        <button type="button" class="btn btn-danger btn-block form-control" data-bs-toggle="modal"
                            data-bs-target="#modal_qr">
                            <i class="fa-solid fa-qrcode"></i>&nbsp; Cetak QR
                        </button>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Upload</label>
                        <button type="button" class="btn btn-warning btn-block form-control" data-bs-toggle="modal"
                            data-bs-target="#modal_upload">
                            <i class="fa-solid fa-upload"></i> &nbsp; Upload Lokasi
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
                            <th style="text-align: center; color: white;"> NORMALISASI </th>
                            <th style="text-align: center; color: white;"> KATEGORI </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> SATUAN </th>
                            <th style="text-align: center; color: white;"> STOCK </th>
                            <th style="text-align: center; color: white;"> LOKASI </th>
                            <th style="text-align: center; color: white;"> DENAH </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_detail_lokasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h5 class="fw-bold" id="nama_material">Detail Lokasi Material </h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form id="form_detail_lokasi" action="<?= base_url() ?>C_Locator/UpdateLokasiMaterial" method="POST" class="form">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body px-5 my-7">
                    <input type="hidden" class="form-control" name="unit" id="mdl_unit" value="" required />
                    <input type="hidden" class="form-control" name="id_material" id="id_material" value="" required />
                    <div class="row">
                        <table id="table_detail_lokasi" class="table table-bordered table-hover table-striped table-row-bordered" style="white-space: nowrap;">
                            <thead>
                                <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B">
                                    <th style="text-align: center; color: white;"> HAPUS </th>
                                    <th style="text-align: center; color: white;"> GUDANG </th>
                                    <th style="text-align: center; color: white;"> NO GUDANG </th>
                                    <th style="text-align: center; color: white;"> RAK </th>
                                    <th style="text-align: center; color: white;"> LANTAI </th>
                                    <th style="text-align: center; color: white;"> PETAK </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="addRow()">TAMBAH ROW</button>
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

<div class="modal fade" id="modal_qr" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="nama_material">Cetak QR </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form target="_blank" action="<?= base_url() ?>C_Locator/qr_export" method="POST" class="form">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body px-5 my-7">
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Jenis</label>
                        <select name="jenis" class="form-control select2" data-placeholder="Pilih Unit">
                            <option value="full">QR Code dan Label</option>
                            <option value="qr">QR Code</option>
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Normalisasi</label>
                        <textarea name="normalisasi" class="form-control" rows="5" placeholder="Format : &#10;1030097&#10;1030088&#10;1030023" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_upload_stock" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Upload Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_Stock/import" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="form-label">Tanggal Stock</label>
                            <input type="date" name="tanggal_stock" id="tanggal_stock" class="form-control"
                                placeholder="Tanggal Stock" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">File Dokumen Stock (Excel)</label>
                            <input type="file" name="upload_file" id="upload_file" class="form-control"
                                accept=".xls,.xlsx" placeholder="xls,.xlsx" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <a href="<?= base_url(); ?>data_uploads/stockmaterial/format.xlsx" download target="_blank"
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

<div class="modal fade" id="modal_detail_stock" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Detail Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>/C_WebUser/Save" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="card-datatable text-nowrap">
                        <table id="tabledetail" class="dt-fixedheader table">
                            <thead>
                                <tr style="background-color: #008B8B">
                                    <th style="text-align: center; color: white;"> NO </th>
                                    <th style="text-align: center; color: white;"> NORMALISASI </th>
                                    <th style="text-align: center; color: white;"> MATERIAL </th>
                                    <th style="text-align: center; color: white;"> UNIT </th>
                                    <th style="text-align: center; color: white;"> SATUAN </th>
                                    <th style="text-align: center; color: white;"> JUMLAH </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #008B8B">
                                    <th style="text-align: center; color: white;" colspan="5"> TOTAL </th>
                                    <th style="text-align: center; color: white;" id="total_detail_stock"> 0 </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var table;
var table_detail = $('#table_detail_lokasi').DataTable();
var stock = 0;

$(document).ready(function() {
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 10,
        "columnDefs": [{
                targets: [0, 5, 7, 8],
                className: 'dt-body-center'
            },
            {
                targets: [6],
                className: 'dt-body-right'
            },
        ],
        "ajax": {
            "url": "<?= base_url() ?>C_Locator/getData",
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

function showLokasi(id_material, material, stock_material, unit) {
    $.ajax({
        url: "<?= base_url() ?>C_Locator/getLokasiMaterial",
        type: "post",
        data: {
            id_material: id_material,
            unit: unit,
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
            table_detail.clear().draw();
            Swal.close();
            var data = JSON.parse(response);
            if (data.length > 0) {
                stock = stock_material;
                for (var i = 0; i < data.length; i++) {
                    table_detail.row.add([
                        '<button class="btn btn-danger btn-block" onclick="hapusRow(this)">HAPUS</button>',
                        '<select class="form-select" name="gudang[]" data-control="select2" data-dropdown-parent="#modal_detail_lokasi" required>' +
                        '<option>- PILIH -</option>' +
                        <?php foreach ($gudang as $g) { ?> '<option value="<?= html_escape($g->id); ?>" ' + (
                            data[i]['id_gudang'] == "<?= html_escape($g->id); ?>" ? 'selected' : '') +
                        '><?= html_escape($g->name); ?></option>' +
                        <?php } ?> '</select>',
                        '<input type="number" class="form-control" name="no_gudang[]" min="0" value="' +
                        data[i]['no_gudang'] + '" placeholder="" autocomplete="off" required />',
                        '<input type="text" class="form-control" name="rak[]" maxlength="2" value="' +
                        data[i]['rak'] +
                        '" placeholder="" autocomplete="off" oninput="this.value = this.value.toUpperCase()" onkeypress="return onlyText(event)" required/>',
                        '<input type="number" class="form-control" name="lantai[]" min="0" value="' +
                        data[i]['lantai'] + '" placeholder="" autocomplete="off"/>',
                        '<input type="number" class="form-control" name="petak[]" min="0" value="' +
                        data[i]['petak'] + '" placeholder="" autocomplete="off"/>',
                        '<input type="number" class="form-control" name="volume[]" min="0" value="' +
                        data[i]['volume'] + '" placeholder="" autocomplete="off" required />'
                    ]).draw(false);
                }
            }
            $('#mdl_unit').val(unit);
            $('#id_material').val(id_material);
            $('#nama_material').html('Detail Lokasi Material ' + id_material + ' - ' + material);
            $('#modal_detail_lokasi').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}

function addRow() {
    table_detail.row.add([
        '<button class="btn btn-danger btn-block" onclick="hapusRow(this)">HAPUS</button>',
        '<select class="form-control select2" name="gudang[]" data-dropdown-parent="#modal_detail_lokasi" required>' +
        '<option>- PILIH -</option>' +
        <?php foreach ($gudang as $g) { ?> '<option value="<?= html_escape($g->id); ?>"><?= html_escape($g->name); ?></option>' +
        <?php } ?> '</select>',
        '<input type="number" class="form-control" name="no_gudang[]" min="0" placeholder="" autocomplete="off" required />',
        '<input type="text" class="form-control" name="rak[]" maxlength="2" placeholder="" autocomplete="off" oninput="this.value = this.value.toUpperCase()" onkeypress="return onlyText(event)" required/>',
        '<input type="number" class="form-control" name="lantai[]" min="0" placeholder="" autocomplete="off"/>',
        '<input type="number" class="form-control" name="petak[]" min="0" placeholder="" autocomplete="off"/>',
    ]).draw(false);

    $('#table_detail_lokasi select[name="gudang[]"]').last().select2({
        placeholder: "- PILIH -",
        dropdownParent: $('#modal_detail_lokasi')
    });
}

function onlyText(event) {
    var char = String.fromCharCode(event.which);
    if (!char.match(/[a-zA-Z]/)) {
        event.preventDefault();
    }
    return true;
}

function hapusRow(loc) {
    table_detail.row($(loc).parents('tr')).remove().draw();
}

function showDenah(image_url) {
    $("#image_layout").attr("src", image_url);
    $('#modal_layout').modal('show');
}

var derajat = 0;

function rotasi(arah){
    derajat += arah;
    $("#image_layout").css({
        "transform": "rotate(" + derajat + "deg)" // Apply rotation with CSS transform
    });
}
</script>