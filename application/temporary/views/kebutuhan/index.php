<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Dashboard Kebutuhan Material</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Kebutuhan/exportDashboard" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Basket</label>
                        <select name="kebutuhan" id="kebutuhan" class="form-select select2"
                            data-placeholder="Pilih Kebutuhan" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <option value="keandalan">KEANDALAN</option>
                            <option value="efisiensi">EFISIENSI</option>
                            <option value="pemasaran">PEMASARAN</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Kategori</label>
                        <select name="kategori" id="kategori" class="form-select select2" data-placeholder="Pilih Kategori" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($kategori as $d) { ?>
                                <option value="<?= html_escape($d->kategori); ?>"><?= html_escape($d->kategori); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Filter</label>
                        <select name="filter" id="filter" class="form-select" data-control="select2"
                            data-placeholder="Pilih Filter" onchange="filterData()">
                            <option value="*">TOTAL</option>
                            <option value="approved_volume">SUDAH DIPENUHI</option>
                            <option value="sisa">BELUM DIPENUHI</option>
                        </select>
                    </div>
                </div>
                <div class="col-4"></div>
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
                            <th style="text-align: center; color: white;"> NORMALISASI </th>
                            <th style="text-align: center; color: white;"> KATEGORI </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> SATUAN </th>
                            <th style="text-align: center; color: white;"> TOTAL </th>
                            <th style="text-align: center; color: white;"> SUDAH DIPENUHI </th>
                            <th style="text-align: center; color: white;"> BELUM DIPENUHI </th>
                            <th style="text-align: center; color: white; min-width:50px"> UID </th>
                            <th style="text-align: center; color: white; min-width:50px"> BDG </th>
                            <th style="text-align: center; color: white; min-width:50px"> BLG </th>
                            <th style="text-align: center; color: white; min-width:50px"> BTR </th>
                            <th style="text-align: center; color: white; min-width:50px"> CKG </th>
                            <th style="text-align: center; color: white; min-width:50px"> CPP </th>
                            <th style="text-align: center; color: white; min-width:50px"> CPT </th>
                            <th style="text-align: center; color: white; min-width:50px"> CRC </th>
                            <th style="text-align: center; color: white; min-width:50px"> JTN </th>
                            <th style="text-align: center; color: white; min-width:50px"> KBJ </th>
                            <th style="text-align: center; color: white; min-width:50px"> KJT </th>
                            <th style="text-align: center; color: white; min-width:50px"> LTA </th>
                            <th style="text-align: center; color: white; min-width:50px"> MRD </th>
                            <th style="text-align: center; color: white; min-width:50px"> MTG </th>
                            <th style="text-align: center; color: white; min-width:50px"> PDG </th>
                            <th style="text-align: center; color: white; min-width:50px"> PDK </th>
                            <th style="text-align: center; color: white; min-width:50px"> TJP </th>
                            <th style="text-align: center; color: white; min-width:50px"> UP2D </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_material" tabindex="-1">
    <div class="modal-dialog modal-fullscreen p-9">
        <div class="modal-content modal-rounded">
            <div class="modal-header d-flex justify-content-between">
                <h5>List Detail Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y">
                <form class="form" action="<?= base_url(); ?>C_Kebutuhan/exportDashboard" method="POST">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    <div class="row">
                        <div class="col-2">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Basket</label>
                                <input type="hidden" name="detail_material" id="detail_material" class="form-control"/>
                                <select name="detail_kebutuhan" id="detail_kebutuhan" class="form-select select2" data-placeholder="Pilih Kebutuhan">
                                    <option value="*">- SEMUA -</option>
                                    <option value="keandalan">KEANDALAN</option>
                                    <option value="efisiensi">EFISIENSI</option>
                                    <option value="pemasaran">PEMASARAN</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Filter</label>
                                <select name="detail_filter" id="detail_filter" class="form-select select2" data-placeholder="Pilih Filter" onchange="filterMaterial()">
                                    <option value="*">TOTAL</option>
                                    <option value="approved_volume">SUDAH DIPENUHI</option>
                                    <option value="sisa">BELUM DIPENUHI</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <table id="table_detail" class="table table_detail align-middle table-bordered table-hover table-striped table-row-bordered" style="white-space: nowrap;">
                    <thead>
                        <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B">
                            <th style="text-align: center; color: white;"> NO </th>
                            <th style="text-align: center; color: white;"> NORMALISASI </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> KATEGORI </th>
                            <th style="text-align: center; color: white;"> SATUAN </th>
                            <th style="text-align: center; color: white;"> RASIO </th>
                            <th style="text-align: center; color: white;"> TOTAL </th>
                            <th style="text-align: center; color: white;"> SUDAH DIPENUHI </th>
                            <th style="text-align: center; color: white;"> BELUM DIPENUHI </th>
                            <th style="text-align: center; color: white; min-width:50px"> UID </th>
                            <th style="text-align: center; color: white; min-width:50px"> BDG </th>
                            <th style="text-align: center; color: white; min-width:50px"> BLG </th>
                            <th style="text-align: center; color: white; min-width:50px"> BTR </th>
                            <th style="text-align: center; color: white; min-width:50px"> CKG </th>
                            <th style="text-align: center; color: white; min-width:50px"> CPP </th>
                            <th style="text-align: center; color: white; min-width:50px"> CPT </th>
                            <th style="text-align: center; color: white; min-width:50px"> CRC </th>
                            <th style="text-align: center; color: white; min-width:50px"> JTN </th>
                            <th style="text-align: center; color: white; min-width:50px"> KBJ </th>
                            <th style="text-align: center; color: white; min-width:50px"> KJT </th>
                            <th style="text-align: center; color: white; min-width:50px"> LTA </th>
                            <th style="text-align: center; color: white; min-width:50px"> MRD </th>
                            <th style="text-align: center; color: white; min-width:50px"> MTG </th>
                            <th style="text-align: center; color: white; min-width:50px"> PDG </th>
                            <th style="text-align: center; color: white; min-width:50px"> PDK </th>
                            <th style="text-align: center; color: white; min-width:50px"> TJP </th>
                            <th style="text-align: center; color: white; min-width:50px"> UP2D </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Breakdown Kebutuhan Material</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Kebutuhan/exportDashboardRencana" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Basket</label>
                        <select name="kebutuhan_rencana" id="kebutuhan_rencana" class="form-select select2"
                            data-placeholder="Pilih Kebutuhan" onchange="filterRencana()">
                            <option value="*">- SEMUA -</option>
                            <option value="keandalan">KEANDALAN</option>
                            <option value="efisiensi">EFISIENSI</option>
                            <option value="pemasaran">PEMASARAN</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Tahun</label>
                        <select name="tahun_rencana" id="tahun_rencana" class="form-select select2"
                            data-placeholder="Pilih Tahun" onchange="filterRencana()">
                            <?php for ($i= date('Y') + 5; $i >= 2020; $i--) { ?>
                                <option value="<?= $i ?>" <?= date('Y') == $i ? "selected" : "" ?>><?= $i ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Status Nyala</label>
                        <select name="is_menyala" id="is_menyala" class="form-select select2"
                            data-placeholder="Pilih Status" onchange="filterRencana()">
                            <option value="*">- SEMUA -</option>
                            <option value="0" selected>Belum Menyala</option>
                            <option value="1">Menyala</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Status Bayar</label>
                        <select name="is_bayar" id="is_bayar" class="form-select select2"
                            data-placeholder="Pilih Status" onchange="filterRencana()">
                            <option value="*">- SEMUA -</option>
                            <option value="1">Sudah Bayar</option>
                            <option value="0">Belum Bayar</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Filter</label>
                        <select name="filter_rencana" id="filter_rencana" class="form-select select2" data-placeholder="Pilih Filter" onchange="filterRencana()">
                            <option value="volume">TOTAL</option>
                            <option value="approved_volume">SUDAH DIPENUHI</option>
                            <option value="sisa" selected>BELUM DIPENUHI</option>
                        </select>
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
                <table id="table_rencana" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white;"> NO </th>
                            <th style="text-align: center; color: white;"> NORMALISASI </th>
                            <th style="text-align: center; color: white;"> KATEGORI </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> SATUAN </th>
                            <th style="text-align: center; color: white;"> TOTAL </th>
                            <th style="text-align: center; color: white;"> SUDAH DIPENUHI </th>
                            <th style="text-align: center; color: white;"> BELUM DIPENUHI </th>
                            <th style="text-align: center; color: white;"> NON </th>
                            <th style="text-align: center; color: white;"> JAN </th>
                            <th style="text-align: center; color: white;"> FEB </th>
                            <th style="text-align: center; color: white;"> MAR </th>
                            <th style="text-align: center; color: white;"> APR </th>
                            <th style="text-align: center; color: white;"> MEI </th>
                            <th style="text-align: center; color: white;"> JUN </th>
                            <th style="text-align: center; color: white;"> JUL </th>
                            <th style="text-align: center; color: white;"> AGTS </th>
                            <th style="text-align: center; color: white;"> SEP </th>
                            <th style="text-align: center; color: white;"> OKT </th>
                            <th style="text-align: center; color: white;"> NOV </th>
                            <th style="text-align: center; color: white;"> DES </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
var table;
var table_detail;
var table_rencana;

$(document).ready(function() {
    getDashboard();
    getRencana();
    getDetail();
});

function getDashboard(){
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "columnDefs": [{
                targets: [0, 1, 2, 4],
                className: 'dt-body-center'
            },
            {
                targets: [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
                className: 'dt-body-right'
            },
        ],
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_Kebutuhan/ajaxDashboard",
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
                data.kebutuhan = $("#kebutuhan").val(),
                data.kategori = $("#kategori").val(),
                data.tahun = $("#tahun").val(),
                data.filter = $("#filter").val(),
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
}

function filterData() {
    table.ajax.reload();
}

function getDetail(){
    table_detail = $("#table_detail").DataTable({
        "scrollX": true,
        "pageLength": 10,
        "columnDefs": [{
                targets: [0, 1, 2, 4],
                className: 'dt-body-center'
            },
            {
                targets: [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26],
                className: 'dt-body-right'
            },
        ],
        "ajax": {
            "url": "<?= base_url() ?>C_Kebutuhan/detailKebutuhanMaterial",
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
                data.kebutuhan = $("#detail_kebutuhan").val(),
                data.material_id = $("#detail_material").val(),
                data.filter = $("#detail_filter").val(),
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
}

function getDetailKebutuhanMaterial(material) {
    $("#detail_kebutuhan").val($("#kebutuhan").val()).change().prop('disabled', true);
    // $("#detail_tahun").val($("#tahun").val()).change().prop('disabled', true);
    $("#detail_material").val(material);
    table_detail.ajax.reload();
    $("#modal_material").modal('show');
}

function filterMaterial() {
    table_detail.ajax.reload();
}

function getRencana(){
    table_rencana = $("#table_rencana").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "columnDefs": [{
                targets: [0, 1, 4],
                className: 'dt-body-center'
            },
            {
                targets: [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
                className: 'dt-body-right'
            },
        ],
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_Kebutuhan/ajaxDashboardRencana",
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
                data.kebutuhan = $("#kebutuhan_rencana").val(),
                data.tahun = $("#tahun_rencana").val(),
                data.is_bayar = $("#is_bayar").val(),
                data.is_menyala = $("#is_menyala").val(),
                data.filter_rencana = $("#filter_rencana").val(),
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
}

function filterRencana(){
    table_rencana.ajax.reload();
}
</script>