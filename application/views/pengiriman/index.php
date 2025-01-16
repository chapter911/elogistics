<script src="<?= base_url(); ?>assets/js/modal-create-app.js"></script>

<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Daftar Pengiriman</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Pengiriman/exportIndex" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Status Kirim</label>
                        <select name="status_kirim" id="status_kirim" class="form-select select2"
                            data-placeholder="Pilih Status Kirim" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <option value="BELUM KIRIM">BELUM KIRIM</option>
                            <option value="PROSES KIRIM">PROSES KIRIM</option>
                            <option value="SELESAI KIRIM">SELESAI KIRIM</option>
                        </select>
                    </div>
                </div>
                <div class="col-7"></div>
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
                            <th style="text-align: center; color: white;"> AWAL KONTRAK </th>
                            <th style="text-align: center; color: white;"> AKHIR KONTRAK </th>
                            <th style="text-align: center; color: white;"> AWAL BAE </th>
                            <th style="text-align: center; color: white;"> AKHIR BAE </th>
                            <th style="text-align: center; color: white;"> RENCANA KIRIM </th>
                            <th style="text-align: center; color: white;"> TANGGAL TERIMA </th>
                            <th style="text-align: center; color: white;"> VOLUME KONTRAK </th>
                            <th style="text-align: center; color: white;"> VOLUME KIRIM </th>
                            <th style="text-align: center; color: white;"> PROGRESS </th>
                            <th style="text-align: center; color: white;"> ACTIONS </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_material" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen p-10">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h5>List Detail Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <div id="detail"></div>
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

<div class="modal fade" id="createApp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Data Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="wizard-create-app" class="bs-stepper vertical mt-2 shadow-none">
                    <div class="bs-stepper-header border-0 p-1">
                        <div class="step" data-target="#rencana_kirim_form">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Rencana Kirim</span>
                                    <span class="bs-stepper-subtitle">Enter Details</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#input_pengiriman_form">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-box"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Input Pengiriman</span>
                                    <span class="bs-stepper-subtitle">Enter Details</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content p-1">
                        <div id="rencana_kirim_form" class="content pt-4 pt-lg-0">
                            <form class="form" action="<?= base_url(); ?>C_Pengiriman/updateRencanaKirim" method="POST">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Nomor Kontrak</label>
                                    <input type="text" name="no_kontrak" id="no_kontrak_kirim" class="form-control"
                                        placeholder="Nomor Kontrak" required readonly />
                                </div>
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Rencana Kirim</label>
                                    <input type="date" name="rencana_kirim" id="rencana_kirim" class="form-control"
                                        placeholder="Rencana Kirim" required />
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-6">
                                    <button type="reset" class="btn btn-label-secondary btn-prev" disabled>
                                        <span class="align-middle d-sm-inline-block d-none">Reset</span>
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-next">
                                        <i class="ti ti-device-floppy ti-xs"></i>
                                        <span class="align-middle d-sm-inline-block d-none me-sm-2">Simpan</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div id="input_pengiriman_form" class="content pt-4 pt-lg-0">
                            <form class="form" action="<?= base_url(); ?>C_Pengiriman/Save" method="POST"
                                enctype="multipart/form-data">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Nomor Kontrak</label>
                                    <input type="text" name="no_kontrak" id="no_kontrak" class="form-control"
                                        placeholder="Nomor Kontrak" required readonly />
                                    <input type="hidden" name="kontrak_dtl_id" id="kontrak_dtl_id" class="form-control"
                                        required readonly />
                                </div>
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Vendor</label>
                                    <input type="text" name="vendor" id="vendor" class="form-control"
                                        placeholder="Vendor" required readonly />
                                </div>
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Basket</label>
                                    <input type="text" name="basket" id="basket" class="form-control"
                                        placeholder="Basket" required readonly />
                                </div>
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Penerimaan</label>
                                    <input type="date" name="tanggal_penerimaan" id="tanggal_penerimaan"
                                        class="form-control" placeholder="Tanggal Penerimaan" required />
                                </div>
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Status Material</label>
                                    <select name="status" id="status" class="select2"
                                        data-placeholder="Pilih Status Material" required>
                                        <option value="KARANTINA">KARANTINA</option>
                                        <option value="PERSEDIAAN">PERSEDIAAN</option>
                                    </select>
                                </div>
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Nomor Slip Penerimaan</label>
                                    <input type="text" name="slip_penerimaan" id="slip_penerimaan" class="form-control"
                                        placeholder="Nomor Slip Penerimaan" required />
                                </div>
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Surat Jalan</label>
                                    <input type="file" name="surat_jalan" id="surat_jalan" class="form-control"
                                        accept=".pdf" placeholder="Surat Jalan" required />
                                </div>
                                <div class="mb-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="required fw-semibold fs-6 mb-2">Foto 1</label>
                                            <input type="file" name="foto1" id="foto1" class="form-control"
                                                accept=".png, .jpg, .jpeg" placeholder="Foto1" required />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="required fw-semibold fs-6 mb-2">Foto 2</label>
                                            <input type="file" name="foto2" id="foto2" class="form-control"
                                                accept=".png, .jpg, .jpeg" placeholder="Foto2" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Material</label>
                                    <table id="datatable_input_pengiriman" class="table table-striped table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr style="background-color: #008B8B">
                                                <th style="text-align: center; color: white;"> UNIT TUJUAN </th>
                                                <th style="text-align: center; color: white;"> ID MATERIAL </th>
                                                <th style="text-align: center; color: white;"> MATERIAL </th>
                                                <th style="text-align: center; color: white;"> VOLUME KONTRAK </th>
                                                <th style="text-align: center; color: white;"> VOLUME KIRIM </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-6">
                                    <button type="reset" class="btn btn-label-secondary btn-prev" disabled>
                                        <span class="align-middle d-sm-inline-block d-none">Reset</span>
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-next">
                                        <i class="ti ti-device-floppy ti-xs"></i>
                                        <span class="align-middle d-sm-inline-block d-none me-sm-2">Simpan</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                targets: [0, 1, 2, 4, 5, 6, 7, 8, 14, 15],
                className: 'dt-body-center'
            },{
                targets: [12, 13],
                className: 'dt-body-right'
            },
        ],
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_Pengiriman/ajaxIndex",
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

function getMaterialKontrak($id) {
    $url = "<?= base_url() ?>C_Kontrak/getMaterialKontrak?id=" + $id;
    $.ajax({
        url: $url,
        type: "GET",
        data: "text",
        success: function(d) {
            $("#modal_material").modal('show');
            $("#detail").html(d);
        }
    });
}

function detailPengiriman(no_kontrak) {
    $.ajax({
        url: "<?= base_url() ?>C_Pengiriman/getDetailPengiriman",
        type: "post",
        data: {
            no_kontrak: no_kontrak,
            unit: <?= $this->session->userdata('unit_id'); ?>,
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

function inputPengiriman(id) {
    tabledetail = $("#datatable_input_pengiriman").DataTable({
        "destroy": true,
        "bPaginate": false,
        "ajax": {
            "url": "<?= base_url() ?>C_Pengiriman/getDetailInputPengiriman",
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
                data.id = id,
                data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
            },
            "complete": function(response) {
                Swal.close();
                console.log(response);
                var data = JSON.parse(response.responseText).kontrak[0];
                $('#kontrak_dtl_id').val(data.id);
                $('#no_kontrak').val(data.no_kontrak);
                $('#no_kontrak_kirim').val(data.no_kontrak);
                $('#vendor').val(data.vendor);
                $('#basket').val(data.basket);
                $('#rencana_kirim').val(data.rencana_kirim);
                $("#createApp").modal('show');
            },
            "error": function(jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        }
    });
}
</script>