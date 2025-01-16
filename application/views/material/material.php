<div class="card mb-6">
    <div class="card-header header-elements">
        <h3 class="mb-0 me-2">Daftar Material</h3>
        <div class="card-header-elements ms-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_data">
                <span class="tf-icon ti ti-plus ti-xs me-1"></span>Add Material
            </button>
        </div>
    </div>
    <div class="collapse show">
        <div class="row">
            <div class="card-datatable p-10 text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color:white"> NO NORMALISASI </th>
                            <th style="text-align: center; color:white"> KATEGORI </th>
                            <th style="text-align: center; color:white"> MATERIAL </th>
                            <th style="text-align: center; color:white"> SATUAN </th>
                            <th style="text-align: center; color:white"> PEMAKAIAN MINGGUAN </th>
                            <th style="text-align: center; color:white"> SAFETY STOCK </th>
                            <th style="text-align: center; color:white"> STOCK </th>
                            <th style="text-align: center; color:white"> DASHBOARD </th>
                            <th style="text-align: center; color:white"> ACTION </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $d) { ?>
                        <tr>
                            <td style="text-align: center;"> <?= html_escape($d->id) ?> </td>
                            <td> <?= html_escape($d->kategori); ?> </td>
                            <td> <?= html_escape($d->material); ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->satuan); ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->leadtime); ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->safety); ?> </td>
                            <td style="text-align: center;">
                                <?php if(html_escape($d->is_highlight) == 1){ ?>
                                <span class='badge bg-success'> HIGHLIGHTED </span>
                                <?php } else { ?>
                                <span class='badge bg-danger'> NOT HIGHLIGHTED </span>
                                <?php } ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if(html_escape($d->is_dashboard) == 1){ ?>
                                <span class='badge bg-success'> DASHBOARD </span>
                                <?php } else { ?>
                                <span class='badge bg-danger'> NOT DASHBOARD </span>
                                <?php } ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <?php if($this->session->userdata("edit") == 1) {
                                        if(html_escape($d->kategori) == "-"){ ?>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="registerMaterial('<?= html_escape($d->id); ?>', '<?= html_escape($d->material); ?>')"><i class="ti ti-pencil"></i></button>
                                    <?php } else {?>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="editMaterial('<?= html_escape($d->id); ?>')"><i class="ti ti-pencil"></i></button>
                                    <?php }
                                    }?>
                                </div>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Add Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kt_modal_add_user_form" class="form" action="<?= base_url(); ?>/C_Material/MaterialSave"
                method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Nomor Material</label>
                            <input type="text" name="id" id="id" class="form-control" data-dropdown-parent='#add_data'
                                placeholder="Nomor Material" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Kategori Material</label>
                            <select name="kategori_id" id="kategori_id" class="form-control select2" data-dropdown-parent='#add_data'
                                data-placeholder="Pilih Kategori" required>
                                <option></option>
                                <?php foreach($kategori as $k) { ?>
                                <option value="<?= html_escape($k->id); ?>"><?= html_escape($k->kategori); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Material</label>
                            <input type="text" name="material" id="material"
                                class="form-control" placeholder="Material" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Satuan</label>
                            <select name="satuan_id" id="satuan_id" class="form-control select2" data-dropdown-parent='#add_data'
                                data-placeholder="Pilih Satuan" required>
                                <option></option>
                                <?php foreach($satuan as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->satuan); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Lead Time</label>
                            <input type="number" name="leadtime" id="leadtime" class="form-control mb-3 mb-lg-0"
                                placeholder="LeadTime" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Safety Stock</label>
                            <input type="number" name="safety" id="safety" class="form-control mb-3 mb-lg-0"
                                placeholder="Safety Stock" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">HIGHLIGHT</label>
                            <select name="is_highlight" id="is_highlight" class="form-control select2" data-dropdown-parent='#add_data'
                                data-placeholder="Pilih Highlight" required>
                                <option>PILIH</option>
                                <option value="1">HIGHLIGHT</option>
                                <option value="0">BUKAN HIGHLIGHT</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">DASHBOARD</label>
                            <select name="is_dashboard" id="is_dashboard" class="form-control select2" data-dropdown-parent='#add_data'
                                data-placeholder="Pilih Dashboard" required>
                                <option>PILIH</option>
                                <option value="1">DASHBOARD</option>
                                <option value="0">BUKAN DASHBOARD</option>
                            </select>
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
        "pageLength": 10,
    });
});

function addMaterial() {
    $('#id').val('');
    $('#id').prop('readonly', false);
    $('#material').val('');
    $("#kategori_id").select2().val("").trigger("change");
    $("#is_highlight").select2().val("").trigger("change");
    $("#is_dashboard").select2().val("").trigger("change");
    $("#satuan_id").select2().val("").trigger("change");
    $('#leadtime').val("");
    $("#add_data").modal('show');
}

function registerMaterial(id, material) {
    $('#id').val(id);
    $('#id').prop('readonly', true);
    $('#material').val(material);
    $("#add_data").modal('show');
}

function editMaterial(material) {
    $.ajax({
        url: "<?= base_url() ?>C_Material/getMaterial",
        type: "post",
        data: {
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
            $('#id').val(data[0]['id']);
            $('#id').prop('readonly', true);
            $("#kategori_id").select2().val(data[0]['kategori_id']).trigger("change");
            $('#material').val(data[0]['material']);
            $("#satuan_id").select2().val(data[0]['satuan_id']).trigger("change");
            $("#is_highlight").select2().val(data[0]['is_highlight']).trigger("change");
            $("#is_dashboard").select2().val(data[0]['is_dashboard']).trigger("change");
            $('#leadtime').val(data[0]['leadtime']);
            $('#safety').val(data[0]['safety']);
            $("#add_data").modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}
</script>