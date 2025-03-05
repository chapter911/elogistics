<script src="<?= base_url(); ?>assets/js/modal-create-app.js"></script>

<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Pemasaran</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Pemasaran/exportIndex" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit</label>
                        <select name="unit" id="unit" class="form-control select2" data-control="select2"
                            data-placeholder="Pilih Unit" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($unit as $u) { ?>
                            <option value="<?= html_escape($u->id); ?>"><?= html_escape($u->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Status</label>
                        <select name="status" id="status" class="form-control select2" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <option value="0">BELUM MENYALA</option>
                            <option value="1">SUDAH MENYALA</option>
                        </select>
                    </div>
                </div>
                <div class="col-4"></div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Add</label>
                        <button type="button" class="btn btn-warning btn-block form-control" data-bs-toggle="modal"
                            data-bs-target="#add_data" onclick="insertRow()">
                            <i class="fa-solid fa-plus"></i> &nbsp; Tambah data
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
                <table id="table" class="table dt-fixedcolumns">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; vertical-align: middle; color: white; background-color: #008B8B;"> NO </th>
                            <th style="text-align: center; vertical-align: middle; color: white; background-color: #008B8B;"> UNIT </th>
                            <th style="text-align: center; vertical-align: middle; color: white; background-color: #008B8B;"> PELANGGAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> LAYANAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> JENIS PENGUKURAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TEGANGAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> PEKERJAAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TARIF </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> DAYA LAMA</th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> DAYA BARU</th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TANGGAL MOHON </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TANGGAL BAYAR </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> RENCANA NYALA </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TANGGAL NYALA </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> DURASI </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> MATERIAL </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> STATUS </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> KETERANGAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TANGGAL INPUT </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> ACTION </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_data" tabindex="-1">
    <div class="modal-dialog modal-fullscreen p-9">
        <div class="modal-content modal-rounded">
            <div class="modal-header">
                <h5>Pembuatan Pemasaran Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <form action="<?= base_url() ?>C_Pemasaran/Save" method="POST" class="form">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" name="pelanggan" id="pelanggan" placeholder=""
                                value="" maxlength="25" autocomplete="off" required />
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <label class="required form-label">Unit</label>
                            <select class="form-control select2" name="unit_add" id="unit_add" required readonly>
                                <option value="<?= $this->session->userdata('unit_id') ?>">
                                    <?= $this->session->userdata('unit_name') ?></option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Segment Tegangan</label>
                            <select class="form-control select2" name="segment_tegangan" id="segment_tegangan" required>
                                <option></option>
                                <option value="rendah">Tegangan Rendah</option>
                                <option value="menengah">Tegangan Menengah</option>
                                <option value="tinggi">Tegangan Tinggi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Jenis Pengukuran</label>
                            <select class="form-control select2" name="pengukuran" id="pengukuran" required>
                                <option></option>
                                <option value="1">Langsung</option>
                                <option value="0">Tidak Langsung</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Layanan</label>
                            <select class="form-control select2" name="layanan" id="layanan" required>
                                <option></option>
                                <option value="premium">Premium</option>
                                <option value="reguler">Reguler</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <label class="required form-label">Jenis Pekerjaan</label>
                            <select class="form-control select2" name="pekerjaan" id="pekerjaan" required>
                                <option></option>
                                <option value="Pasang Baru">Pasang Baru</option>
                                <option value="Perubahan Daya">Perubahan Daya</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Tarif</label>
                            <select class="form-control select2" name="tarif" id="tarif" required>
                                <option></option>
                                <option value="Rumah Tangga">Rumah Tangga</option>
                                <option value="Bisnis">Bisnis</option>
                                <option value="Industri">Industri</option>
                                <option value="Sosial">Sosial</option>
                                <option value="Pemerintahan">Pemerintahan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Daya Lama</label>
                            <select class="form-control select2" name="daya_lama" id="daya_lama" required>
                                <option></option>
                                <?php foreach ($daya as $d) { ?>
                                <option value="<?= html_escape($d->daya); ?>"><?= number_format(html_escape($d->daya), 0, ",", "."); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Daya Baru</label>
                            <select class="form-control select2" name="daya" id="daya" required>
                                <option></option>
                                <?php foreach ($daya as $d) { ?>
                                <option value="<?= html_escape($d->daya); ?>"><?= number_format(html_escape($d->daya), 0, ",", "."); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <label class="required form-label">Tanggal Permohonan</label>
                            <input type="date" name="tanggal_permohonan" id="tanggal_permohonan"
                                class="form-control"
                                placeholder="Tanggal Permohonan">
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Status Bayar</label>
                            <select class="form-control select2" name="is_bayar" id="is_bayar"
                                onchange="set_visible(this)" required>
                                <option></option>
                                <option value="1">SUDAH BAYAR</option>
                                <option value="0">BELUM BAYAR</option>
                            </select>
                        </div>
                        <div class="col-md-3 visible_tanggal">
                            <label class="required form-label">Tanggal SIP</label>
                            <input type="date" name="tanggal_sip" id="tanggal_sip" class="form-control" placeholder="Tanggal SIP">
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Rencana Nyala</label>
                            <input type="date" name="rencana_nyala" id="rencana_nyala" class="form-control" placeholder="Rencana Nyala" required>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="required form-label">Material</label>
                        <div class="table-responsive">
                            <table id="table-insert-material" class="table table-striped table-bordered tblMaterial"
                                style="width: 100%">
                                <thead>
                                    <tr style="background-color: #008B8B;">
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 5%;">
                                            # </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 30%;">
                                            MATERIAL </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 5%;">
                                            VOLUME </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 10%;">
                                            RASIO CT </th>
                                        <!-- <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 10%;">
                                            KETERANGAN </th> -->
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 10%;">
                                            ACTIONS </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-danger"
                            data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_keterangan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-rounded">
            <div class="modal-header">
                <h5>Keterangan Belum Menyala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <span id="keterangan_belum_nyala"></span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_material" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modal-rounded">
            <div class="modal-header">
                <h5>List Detail Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <div id="materialPemasaran"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createApp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen p-9">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="wizard-create-app" class="bs-stepper vertical mt-2 shadow-none">
                    <div class="bs-stepper-header border-0 p-1">
                        <div class="step" data-target="#material_form">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Material</span>
                                    <span class="bs-stepper-subtitle">Enter Details</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#tanggal_sip_form">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-box"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Tanggal SIP</span>
                                    <span class="bs-stepper-subtitle">Enter Details</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#rencana_nyala_form">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-box"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Rencana Nyala</span>
                                    <span class="bs-stepper-subtitle">Rencana Nyala</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#status_form">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-box"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Update Status</span>
                                    <span class="bs-stepper-subtitle">Enter Details</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#keterangan_form">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-box"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Keterangan</span>
                                    <span class="bs-stepper-subtitle">Belum Menyala</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content p-1">
                        <div id="material_form" class="content pt-4 pt-lg-0">
                            <form class="form" action="<?= base_url(); ?>C_Pemasaran/update_approved_volume" method="POST">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <div class="mb-6">
                                    <input type="hidden" name="id_pemasaran" id="id_pemasaran" class="form-control mb-3 mb-lg-0" required/>
                                    <div class="table-responsive" id="update_material_pemasaran"></div>
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
                        <div id="tanggal_sip_form" class="content pt-4 pt-lg-0">
                            <form class="form" action="<?= base_url(); ?>C_Pemasaran/updateTanggalSIP" method="POST">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <div class="col">
                                    <label class="required form-label">Tanggal SIP</label>
                                    <input type="hidden" name="id_sip" id="id_sip" class="form-control mb-3 mb-lg-0" required/>
                                    <input type="date" name="update_tanggal_sip" id="update_tanggal_sip" class="form-control mb-3 mb-lg-0" placeholder="Tanggal SIP" required />
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
                        <div id="status_form" class="content pt-4 pt-lg-0">
                            <form class="form" action="<?= base_url(); ?>C_Pemasaran/updateStatus" method="POST">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Status</label>
                                    <input type="hidden" name="id_status" id="id_status" class="form-control mb-3 mb-lg-0" required/>
                                    <input type="text" class="form-control mb-3 mb-lg-0" value="SUDAH NYALA" readonly />
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Nyala</label>
                                    <input type="date" name="update_tanggal_nyala" id="update_tanggal_nyala" class="form-control mb-3 mb-lg-0" placeholder="Tanggal Nyala" required />
                                    <label class="fw-semibold fs-6 mb-2" style="color: red;">* Pastikan tanggal SIP di update terlebih dahulu</label>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-6">
                                    <button type="reset" class="btn btn-label-secondary btn-prev" disabled>
                                        <span class="align-middle d-sm-inline-block d-none">Reset</span>
                                    </button>
                                    <button type="submit" id="btn_update_status" class="btn btn-primary btn-next">
                                        <i class="ti ti-device-floppy ti-xs"></i>
                                        <span class="align-middle d-sm-inline-block d-none me-sm-2">Simpan</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div id="rencana_nyala_form" class="content pt-4 pt-lg-0">
                            <form class="form" action="<?= base_url(); ?>C_Pemasaran/updateRencanaNyala" method="POST">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Rencana Nyala</label>
                                    <input type="hidden" name="id_rencana_nyala" id="id_rencana_nyala" class="form-control mb-3 mb-lg-0" required/>
                                    <input type="date" class="form-control mb-3 mb-lg-0" name="update_rencana_nyala" id="update_rencana_nyala" required/>
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
                        <div id="keterangan_form" class="content pt-4 pt-lg-0">
                            <form class="form" action="<?= base_url(); ?>C_Pemasaran/updateKeterangan" method="POST">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Keterangan</label>
                                    <input type="hidden" name="id_keterangan" id="id_keterangan" class="form-control mb-3 mb-lg-0" required/>
                                    <input type="text" class="form-control mb-3 mb-lg-0" name="keterangan" required/>
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
        "pageLength": 25,
        "columnDefs": [{
            "className": "dt-body-center",
            "targets": [5, 6, 7, 15, 16, 17, 19]
        },{
            "className": "dt-body-right",
            "targets": [8, 9, 14]
        }],
        "language": {
            "decimal": ",",
        },
        "lengthMenu": [
            [10, 25, -1],
            [10, 25, 'All']
        ],
        "ajax": {
            "url": "<?= base_url() ?>C_Pemasaran/ajaxIndex",
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
                data.status = $("#status").val(),
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
    new $.fn.dataTable.FixedColumns(table, { leftColumns: 3});
});

function filterData() {
    table.ajax.reload();
}

function insertRow() {
    $('#table-insert-material > tbody:last-child').append(
        `<tr>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-primary" onclick="insertRow()">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </td>
            <td style="text-align: center;">
                <select name='material[]' id='material[]' onchange='setRasio(this)' class='form-control select2' required>
                    <option></option>
                    <?php foreach ($material as $d) { ?>
                        <option value='<?= html_escape($d->id); ?>'><?= html_escape($d->id); ?> - <?= html_escape($d->material); ?></option>
                    <?php } ?>
                </select>
            </td>
            <td style="text-align: center;">
                <input type='number' name='volume[]' onkeyup='kalkulasi(this)' class='form-control' required/>
            </td>
            <td style="text-align: center;">
                <select name='rasio[]' id='rasio[]' class='form-control'>
                    <option value="">-</option>
                    <option value="10/5">10/5</option>
                    <option value="15/5">15/5</option>
                    <option value="20/5">20/5</option>
                    <option value="30/5">30/5</option>
                    <option value="40/5">40/5</option>
                    <option value="50/5">50/5</option>
                    <option value="60/5">60/5</option>
                    <option value="75/5">75/5</option>
                    <option value="100/5">100/5</option>
                    <option value="150/5">150/5</option>
                    <option value="200/5">200/5</option>
                    <option value="300/5">300/5</option>
                    <option value="400/5">400/5</option>
                    <option value="800/1">800/1</option>
                    <option value="800/5">800/5</option>
                    <option value="2000/1">2000/1</option>
                    <option value="2000/5">2000/5</option>
                </select>
            </td>
            <td style="text-align: center;">
                <button type='button' class='btn btn-danger btn-sm' onclick='deleteRow(this)'>HAPUS</button>
            </td>
        </tr>`
    );

    $('#table-insert-material select[name="material[]"]').last().select2({
        placeholder: "Pilih Material",
        dropdownParent: $('#add_data')
    });
}

function deleteRow(loc) {
    $(loc).parent().parent().remove();
}

function set_visible(selectElement) {
    if (selectElement.value == 1) {
        $("#tanggal_sip").prop('readonly', false);
        $("#tanggal_sip").val("");
    } else {
        $("#tanggal_sip").prop('readonly', true);
        $("#tanggal_sip").val("BELUM BAYAR");
    }
}

function getMaterialPemasaran(id) {
    $.ajax({
        url: "<?= base_url() ?>C_Pemasaran/materialPemasaran/" + id,
        type: "GET",
        success: function(d) {
            $("#modal_material").modal('show');
            $("#materialPemasaran").html(d);
        }
    });
}

function updatePemasaran(id, tanggal_sip, rencana_nyala) {
    $.ajax({
        url: "<?= base_url() ?>C_Pemasaran/materialPemasaran/" + id,
        type: "GET",
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
        success: function(d) {
            Swal.close();
            $("#update_material_pemasaran").html(d);
            $("#id_pemasaran").val(id);
            $("#id_sip").val(id);
            $("#id_status").val(id);
            $("#id_rencana_nyala").val(id);
            $("#id_keterangan").val(id);
            if (tanggal_sip == "0000-00-00" || tanggal_sip == null) {
                $("#btn_update_tanggal_sip").show();
                $("#btn_update_status").hide();
            } else {
                $("#btn_update_tanggal_sip").hide();
                $("#btn_update_status").show();
            }
            $('#update_rencana_nyala').val(rencana_nyala);
            $("#createApp").modal("show");
        }
    });
}

function deletePemasaran(id) {
    Swal.fire({
        title: 'Apakah Anda yakin menghapus data ini ?',
        text: "Data ini tidak dapat dikembalikan!",
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
                url: "<?= base_url() ?>C_Pemasaran/delete",
                type: "POST",
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
    });
}

function showKeterangan(keterangan) {
    $("#keterangan_belum_nyala").html(keterangan);
    $("#modal_keterangan").modal("show");
}

function setRasio(data) {
    var material_id = $(data).val();

    $.ajax({
        url: "<?= base_url() ?>C_Pemasaran/cekUsingRatio",
        type: "POST",
        data: {
            material_id: material_id,
            <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
        },
        beforeSend: function() {
            Swal.fire({
                title: 'Mohon Tunggu',
                html: 'Cek Rasio',
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
            Swal.showLoading();
        },
        success: function(response) {
            Swal.close();
            if(response == 0){
                $(data).closest('tr').find('select[name="rasio[]"]').val('').trigger('change').attr("disabled", true).prop('required', false);
            } else {
                $(data).closest('tr').find('select[name="rasio[]"]').attr("disabled", false).prop('required', true);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}
</script>