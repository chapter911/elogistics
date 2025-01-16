<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Dashboard Pengiriman Material
        </h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Pengiriman/exportDashboard" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Status Kirim</label>
                        <select name="status_kirim" id="status_kirim" class="select2"
                            data-placeholder="Pilih Status Kirim" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <option value="BELUM KIRIM">BELUM KIRIM</option>
                            <option value="PROSES KIRIM">PROSES KIRIM</option>
                            <option value="SELESAI KIRIM">SELESAI KIRIM</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Kategori</label>
                        <select name="kategori" id="kategori" class="select2" data-placeholder="Pilih Status Kirim"
                            onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($kategori as $d) { ?>
                            <option value="<?= html_escape($d->kategori); ?>"><?= html_escape($d->kategori); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
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
                            <th style="text-align: center; color: white;"> NO KONTRAK </th>
                            <th style="text-align: center; color: white;"> ANGGARAN </th>
                            <th style="text-align: center; color: white;"> VENDOR </th>
                            <th style="text-align: center; color: white;"> KATEGORI MATERIAL </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> SATUAN </th>
                            <th style="text-align: center; color: white;"> VOLUME KONTRAK </th>
                            <th style="text-align: center; color: white;"> VOLUME KIRIM </th>
                            <th style="text-align: center; color: white;"> AWAL KONTRAK </th>
                            <th style="text-align: center; color: white;"> TANGGAL TERIMA </th>
                            <th style="text-align: center; color: white;"> DURASI </th>
                            <th style="text-align: center; color: white;"> PROGRESS </th>
                            <th style="text-align: center; color: white;"> DETAIL </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #008B8B">
                            <th colspan="11" style="text-align: right; color: white;"> AVG DURASI </th>
                            <th id="avg_durasi" style="text-align: center; color: white;"> DURASI </th>
                            <th colspan="2" style="text-align: center; color: white;"> </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Daftar Material Belum Masuk Persediaan</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Pengiriman/exportKarantina" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit</label>
                        <select name="unit_karantina" id="unit_karantina" class="select2" onchange="filterKarantina()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($unit as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-7">
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
                <table id="table_karantina" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white;"> NO </th>
                            <th style="text-align: center; color: white;"> NO KONTRAK </th>
                            <th style="text-align: center; color: white;"> VENDOR </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> SATUAN </th>
                            <th style="text-align: center; color: white;"> VOLUME </th>
                            <th style="text-align: center; color: white;"> UNIT </th>
                            <th style="text-align: center; color: white;"> TANGGAL TERIMA </th>
                            <th style="text-align: center; color: white;"> STATUS </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_pengiriman" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen p-10" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Detail Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <div id="ajaxresult"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_pengiriman_unit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen p-10" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Detail Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <div id="ajaxresult_unit"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_material" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen p-10" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">List Detail Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <div id="detail"></div>
            </div>
        </div>
    </div>
</div>

<script>
var table;
var table_karantina;
$(document).ready(function() {
    $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn';
    getPengiriman();
    getKarantina();
});

function getPengiriman() {
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 10,
        "columnDefs": [{
            targets: [7, 8, 11],
            className: 'dt-body-right'
        }],
        "ajax": {
            "url": "<?= base_url() ?>C_Pengiriman/ajaxDashboard",
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
                data.status_kirim = $("#status_kirim").val(),
                data.kategori = $("#kategori").val(),
                data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
            },
            "complete": function(response) {
                $('#avg_durasi').text(response.responseJSON.avg_durasi);
                Swal.close();
            },
            "error": function(jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        }
    });
}

function getKarantina() {
    table_karantina = $("#table_karantina").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_Pengiriman/ajaxKarantina",
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
                data.unit_karantina = $("#unit_karantina").val(),
                data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
            },
            "complete": function(response) {
                console.log(response);
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

function filterKarantina() {
    table_karantina.ajax.reload();
}

function detailPengirimanDashboard(no_kontrak) {
    $.ajax({
        url: "<?= base_url() ?>C_Pengiriman/detailPengirimanDashboard",
        type: "post",
        data: {
            no_kontrak: no_kontrak,
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
            $("#ajaxresult").html(response);
            $("#modal_pengiriman").modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}

function detailPengirimanUnit(no_kontrak, unit) {
    $("#modal_pengiriman").modal('hide');
    $.ajax({
        url: "<?= base_url() ?>C_Pengiriman/getDetailPengiriman",
        type: "post",
        data: {
            no_kontrak: no_kontrak,
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
            Swal.close();
            $("#ajaxresult_unit").html(response);
            $("#modal_pengiriman_unit").modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}

function getMaterialKontrak(id) {
    $.ajax({
        url: "<?= base_url() ?>C_Kontrak/getMaterialKontrak?id=" + id,
        type: "GET",
        data: "text",
        success: function(d) {
            $("#detail").html(d);
            $("#modal_material").modal('show');
        }
    });
}
</script>