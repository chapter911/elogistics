<div class="card mb-6">
    <div class="card-header header-elements">
        <h3 class="mb-0 me-2">Daftar PRK</h3>
        <div class="card-header-elements ms-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_data">
                <span class="tf-icon ti ti-plus ti-xs me-1"></span>Add PRK
            </button>
        </div>
    </div>
    <div class="collapse show">
        <div class="row">
            <div class="card-datatable p-10 text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white;"> NO </th>
                            <th style="text-align: center; color: white;"> TAHUN </th>
                            <th style="text-align: center; color: white;"> NOMOR PRK </th>
                            <th style="text-align: center; color: white;"> BASKET </th>
                            <th style="text-align: center; color: white;"> URAIAN </th>
                            <th style="text-align: center; color: white;"> STATUS </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> JASA </th>
                            <th style="text-align: center; color: white;"> ACTION </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $total_material = 0;
                        $total_jasa = 0;
                        foreach ($data as $d) { ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= html_escape($d->tahun); ?></td>
                            <td><?= html_escape($d->no_prk); ?></td>
                            <td><?= html_escape($d->basket); ?></td>
                            <td><?= html_escape($d->uraian_prk); ?></td>
                            <td><?= html_escape($d->is_murni) == 1 ? "MURNI" : "LUNCURAN"; ?></td>
                            <td style="text-align: right">
                                <?php
                                    $total_material += html_escape($d->material);
                                    echo number_format(html_escape($d->material), 0, ',', '.');
                                ?>
                            </td>
                            <td style="text-align: right">
                                <?php
                                    $total_jasa += html_escape($d->jasa);
                                    echo number_format(html_escape($d->jasa), 0, ',', '.');
                                ?>
                            </td>
                            <td style="text-align: center">
                                <div class="btn-group">
                                    <?php if($this->session->userdata("edit") == 1) { ?>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="getData(<?= html_escape($d->id); ?>)"><i class="ti ti-pencil"></i></button>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #008B8B">
                            <th colspan="6"></th>
                            <th style="text-align: right; color: white;">
                                <?= number_format($total_material, 0, ',', '.'); ?> </th>
                            <th style="text-align: right; color: white;">
                                <?= number_format($total_jasa, 0, ',', '.'); ?> </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Add PRK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kt_modal_add_user_form" class="form" action="<?= base_url(); ?>C_PRK/save" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select select2" data-placeholder="Pilih Tahun">
                                <?php for($i = (date("Y")); $i >= 2020; $i--) { ?>
                                <option value="<?= $i; ?>" <?= date("Y") == $i ? "selected" : "" ?>><?= $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Nomor PRK</label>
                            <input type="hidden" name="id" id="id" class="form-control mb-3 mb-lg-0" value="0"
                                required />
                            <input type="text" name="no_prk" id="no_prk" class="form-control mb-3 mb-lg-0"
                                placeholder="Nomor PRK" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Basket</label>
                            <select name="basket_id" id="basket_id" class="select2" data-placeholder="Pilih Basket"
                                required>
                                <?php foreach($basket as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->basket); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Uraian</label>
                            <input type="text" name="uraian_prk" id="uraian_prk" class="form-control mb-3 mb-lg-0"
                                placeholder="Uraian PRK" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Status</label>
                            <select name="is_murni" id="is_murni" class="select2" data-placeholder="Pilih Status"
                                required>
                                <option value="1">Murni</option>
                                <option value="2">Luncuran</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Material</label>
                            <input type="number" name="material" id="material" class="form-control mb-3 mb-lg-0"
                                placeholder="Total Material" value="0" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Jasa</label>
                            <input type="number" name="jasa" id="jasa" class="form-control mb-3 mb-lg-0"
                                placeholder="Total Jasa" value="0" required />
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

function getData(id) {
    $url = "<?= base_url() ?>C_PRK/getPRK/" + id;
    $.ajax({
        url: $url,
        type: "GET",
        data: "JSON",
        success: function(data) {
            var datas = JSON.parse(data)[0];
            console.log(datas);
            $("#id").val(datas.id);
            $("#tahun").val(datas.tahun).trigger('change');
            $("#no_prk").val(datas.no_prk);
            $("#basket_id").val(datas.basket_id).trigger('change');
            $("#uraian_prk").val(datas.uraian_prk);
            $("#material").val(datas.material);
            $("#is_murni").val(datas.is_murni).trigger('change');
            $("#jasa").val(datas.jasa);
            $('#add_data').modal('show');
        }
    });
}

function clearForm() {
    $("#id").val("");
    $("#tahun").val("");
    $("#no_prk").val("");
    $("#basket_id").val("");
    $("#uraian_prk").val("");
    $("#material").val("");
    $("#jasa").val("");
}
</script>