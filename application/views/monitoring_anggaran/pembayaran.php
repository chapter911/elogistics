<script src="<?= base_url(); ?>assets/js/modal-create-app.js"></script>

<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Daftar Monitoring Pembayaran</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_MonitoringAnggaran/ExportPembayaran" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Basket</label>
                        <select name="basket" id="basket" class="form-select select2" data-placeholder="Pilih Basket"
                            onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach($basket as $d) { ?>
                            <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->basket); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Status Kirim</label>
                        <select name="status_kirim" id="status_kirim" class="form-select" data-control="select2"
                            data-placeholder="Pilih Status Kirim" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <option value="BELUM KIRIM">BELUM KIRIM</option>
                            <option value="PROSES KIRIM">PROSES KIRIM</option>
                            <option value="SELESAI KIRIM">SELESAI KIRIM</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Status Bayar</label>
                        <select name="status_bayar" id="status_bayar" class="form-select" data-control="select2"
                            data-placeholder="Pilih Status Bayar" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <option value="BELUM BAYAR">BELUM BAYAR</option>
                            <option value="SUDAH BAYAR">SUDAH BAYAR</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Rencana Bayar</label>
                        <input type="month" name="filter_rencana_bayar" id="filter_rencana_bayar" class="form-control"
                            onchange="filterData()" placeholder="Rencana Bayar" />
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Tanggal Bayar</label>
                        <input type="month" name="filter_tanggal_bayar" id="filter_tanggal_bayar" class="form-control"
                            onchange="filterData()" placeholder="Tanggal Bayar" />
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
                            <th style="text-align: center; color:white"> NO </th>
                            <th style="text-align: center; color:white"> BASKET </th>
                            <th style="text-align: center; color:white"> TAHUN </th>
                            <th style="text-align: center; color:white"> JENIS ANGGARAN </th>
                            <th style="text-align: center; color:white"> NO PRK </th>
                            <th style="text-align: center; color:white"> NO KHS </th>
                            <th style="text-align: center; color:white"> NO KONTRAK </th>
                            <th style="text-align: center; color:white"> VENDOR </th>
                            <th style="text-align: center; color:white"> KATEGORI MATERIAL </th>
                            <th style="text-align: center; color:white"> MATERIAL </th>
                            <th style="text-align: center; color:white"> AWAL KONTRAK </th>
                            <th style="text-align: center; color:white"> AKHIR KONTRAK </th>
                            <th style="text-align: center; color:white"> NILAI KONTRAK </th>
                            <th style="text-align: center; color:white"> AWAL BAE </th>
                            <th style="text-align: center; color:white"> AKHIR BAE </th>
                            <th style="text-align: center; color:white"> RENCANA BAYAR </th>
                            <th style="text-align: center; color:white"> TANGGAL TERIMA </th>
                            <th style="text-align: center; color:white"> TANGGAL BAYAR </th>
                            <th style="text-align: center; color:white"> DURASI </th>
                            <th style="text-align: center; color:white"> STATUS KIRIM </th>
                            <th style="text-align: center; color:white"> STATUS BAYAR </th>
                            <th style="text-align: center; color:white"> ACTIONS </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr style="background-color: #008B8B">
                            <th colspan="12" style="text-align: right; color:white"> TOTAL KONTRAK </th>
                            <th style="text-align: right; color:white" id="total_kontrak"> TOTAL </th>
                            <th colspan="5" style="text-align: right; color:white"> DURASI PEMBAYARAN </th>
                            <th style="text-align: center; color:white" id="durasi"> DURASI </th>
                            <th colspan="3" style="text-align: center; color:white"> </th>
                        </tr>
                    </tfoot>
                </table>
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
                        <div class="step" data-target="#rencana_bayar_form">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Rencana Bayar</span>
                                    <span class="bs-stepper-subtitle">Enter Details</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#status_pembayaran_form">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-box"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Status Pembayaran</span>
                                    <span class="bs-stepper-subtitle">Enter Details</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content p-1">
                        <div id="rencana_bayar_form" class="content pt-4 pt-lg-0">
                            <form class="form" action="<?= base_url(); ?>C_MonitoringAnggaran/updatePembayaran" method="POST">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Nomor Kontrak</label>
                                    <input type="text" name="no_kontrak" id="no_kontrak_rencana" class="form-control" placeholder="Nomor Kontrak" required readonly />
                                </div>
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Rencana Bayar</label>
                                    <input type="date" name="rencana_bayar" id="rencana_bayar" class="form-control" placeholder="Rencana Bayar" required />
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
                        <div id="status_pembayaran_form" class="content pt-4 pt-lg-0">
                            <form class="form" action="<?= base_url(); ?>C_MonitoringAnggaran/updatePembayaran" method="POST">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Nomor Kontrak</label>
                                    <input type="text" name="no_kontrak" id="no_kontrak" class="form-control" placeholder="Nomor Kontrak" required readonly />
                                </div>
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Bayar</label>
                                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Tanggal Bayar" required />
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
                targets: [10, 13, 14, 15, 16, 17, 19, 20, 21],
                className: 'dt-body-center'
            },
            {
                targets: [12, 18],
                className: 'dt-body-right'
            },
        ],
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_MonitoringAnggaran/ajaxPembayaran",
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
                data.basket = $("#basket").val(),
                    data.status_kirim = $("#status_kirim").val(),
                    data.status_bayar = $("#status_bayar").val(),
                    data.filter_rencana_bayar = $("#filter_rencana_bayar").val(),
                    data.filter_tanggal_bayar = $("#filter_tanggal_bayar").val(),
                    data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
            },
            "complete": function(response) {
                $("#total_kontrak").html(response.responseJSON.total_kontrak);
                $("#durasi").html(response.responseJSON.average);
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

function showUpdate(no_kontrak) {
    $('#no_kontrak_rencana').val(no_kontrak);
    $('#no_kontrak').val(no_kontrak);
    $("#createApp").modal('show');
}
</script>