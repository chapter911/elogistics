<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Daftar STO</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Permohonan/exportSTO" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-md-2">
                    <div class="row">
                        <label class="fw-semibold fs-6 mb-2">UNIT</label>
                        <select name="unit_id" id="unit_id" class="form-select select2" data-placeholder="Pilih Site"
                            required>
                            <option value="*">- SEMUA -</option>
                            <?php foreach($unit as $d) { ?>
                            <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="fw-semibold fs-6 mb-2">Nomor PR</label>
                    <input type="number" class="form-control" name="no_pr" id="no_pr" />
                </div>
                <div class="col-md-2">
                    <label class="fw-semibold fs-6 mb-2">Nomor STO</label>
                    <input type="number" class="form-control" name="no_sto" id="no_sto" />
                </div>
                <div class="col-4"></div>
                <div class="col-1">
                    <label class="fw-semibold fs-6 mb-2">Filter</label>
                    <button type="button" class="btn btn-success btn-warning form-control" onclick="filterData()">
                        <i class="fa-solid fa-search"></i> &nbsp; Filter
                    </button>
                </div>
                <div class="col-1">
                    <label class="fw-semibold fs-6 mb-2">Export</label>
                    <button type="submit" class="btn btn-primary btn-block form-control">
                        <i class="fa-solid fa-file-excel"></i>Export
                    </button>
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
                            <th style="text-align: center; color: white;"> NO TLSK </th>
                            <th style="text-align: center; color: white;"> NO PR </th>
                            <th style="text-align: center; color: white;"> TANGGAL PR </th>
                            <th style="text-align: center; color: white;"> ANGGARAN </th>
                            <th style="text-align: center; color: white;"> JENIS </th>
                            <th style="text-align: center; color: white;"> NO ANGGARAN </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> STATUS </th>
                            <th style="text-align: center; color: white;"> NO STO </th>
                            <th style="text-align: center; color: white;"> TANGGAL STO </th>
                            <th style="text-align: center; color: white;"> FILE TUG </th>
                            <th style="text-align: center; color: white;"> ACTION </th>
                        </tr>
                    </thead>
                </table>
            </div>
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
            <div class="modal-body scroll-y m-5">
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

<div class="modal fade" id="modal_penerbitan_sto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <form class="form" action="<?= base_url(); ?>C_Permohonan/SaveSTO" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Penerbitan STO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="required fw-semibold fs-6">Nomor STO</label>
                        <input type="number" name="no_sto" id="modal_no_sto" class="form-control" placeholder="Nomor STO"
                            required />
                    </div>
                    <div class="mb-3">
                        <label class="required fw-semibold fs-6">Tanggal STO</label>
                        <input type="date" name="tanggal_sto" id="modal_tanggal_sto" class="form-control"
                            placeholder="Tanggal STO" required />
                    </div>
                    <div class="mb-3">
                        <label class="required fw-semibold fs-6">Nomor PR</label>
                        <input type="number" name="modal_no_pr" id="modal_no_pr" class="form-control"
                            placeholder="Nomor PR" readonly />
                    </div>
                    <table id="table-material-sto" class="table" style="white-space: nowrap;">
                        <thead>
                            <tr style="background-color: #008B8B">
                                <th style="text-align: center; color: white;"> ID MATERIAL </th>
                                <th style="text-align: center; color: white;"> MATERIAL </th>
                                <th style="text-align: center; color: white;"> STOCK </th>
                                <th style="text-align: center; color: white;"> STOCK UNIT</th>
                                <th style="text-align: center; color: white;"> VOLUME PERMINTAAN </th>
                                <th style="text-align: center; color: white;"> VOLUME DISETUJUI </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="text-center pt-10">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">BATAL</button>
                        <button type='button' class='btn btn-danger' onclick="tolakPenerbitan()">TOLAK</button>
                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_tolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Tolak Permohonan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_Permohonan/approvalPermohonan" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body scroll-y m-5">
                    <div class="mb-6">
                        <label class="required fw-semibold fs-6 mb-2">Nomor PR</label>
                        <input type="number" name="no_pr" id="modal_no_pr_tolak" class="form-control" placeholder="Nomor PR"
                            readonly required />
                        <input type="hidden" name="status" value="DITOLAK" readonly />
                    </div>
                    <div class="mb-6">
                        <label class="required fw-semibold fs-6 mb-2">Keterangan</label>
                        <select name="approval_comment" id="approval_comment" class="form-control select2"
                            data-placeholder="Pilih Keterangan" required>
                            <option></option>
                            <option value="STOCK MATERIAL KOSONG">STOCK MATERIAL KOSONG</option>
                            <option value="DOKUMEN TIDAK LENGKAP">DOKUMEN TIDAK LENGKAP</option>
                            <option value="LAIN-LAIN">LAIN-LAIN</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="fw-semibold fs-6 mb-2">Komentar Tambahan</label>
                        <input type="text" name="additional_comment" id="additional_comment"
                            class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Keterangan Tambahan" />
                    </div>
                    <div class="text-center pt-10">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-danger">SIMPAN</button>
                    </div>
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
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url() ?>C_Permohonan/AjaxSTO",
            "type": "POST",
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
                data.unit_id = $('#unit_id').val(),
                data.no_pr = $('#no_pr').val(),
                data.no_sto = $('#no_sto').val(),
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
});

function filterData() {
    table.ajax.reload(null, false);
}

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
                Swal.close();
                $("#modal_material").modal('show');
            },
            "error": function(jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        }
    });
}

function showPenerbitanSTO(no_pr) {
    tableSto = $("#table-material-sto").DataTable({
        "destroy": true,
        "bPaginate": false,
        "ajax": {
            "url": "<?= base_url() ?>C_Permohonan/getDetailSTO",
            "type": "post",
            "data": function(data) {
                data.no_pr = no_pr,
                data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
            },
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
            "complete": function(response) {
                Swal.close();
                var data = JSON.parse(response.responseText).detail[0];
                $('#modal_no_pr').val(data.no_pr);
                $('#modal_no_pr_tolak').val(data.no_pr);
                $('#modal_no_sto').val("");
                $('#modal_tanggal_sto').val("");
                $("#modal_penerbitan_sto").modal('show');
            },
            "error": function(jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        }
    });
}

function tolakPenerbitan() {
    $("#modal_penerbitan_sto").modal('hide');
    $("#modal_tolak").modal('show');
}
</script>