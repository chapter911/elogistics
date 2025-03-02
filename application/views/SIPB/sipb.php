<script src="<?= base_url(); ?>assets/js/modal-create-app.js"></script>
<script src="<?= base_url(); ?>assets/js/modal-create-app2.js"></script>

<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">SIPB</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Analisis/exportDataPemakaian" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit Asal</label>
                        <select name="unit_asal" id="unit_asal" class="form-select select2" data-placeholder="Pilih Unit"
                            onchange="filterData()">
                            <option value="*">ALL</option>
                            <?php foreach ($unit as $u) { ?>
                            <option value="<?= html_escape($u->id); ?>"><?= html_escape($u->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit Tujuan</label>
                        <select name="unit_tujuan" id="unit_tujuan" class="form-select select2" data-placeholder="Pilih Unit"
                            onchange="filterData()">
                            <option value="*">ALL</option>
                            <?php foreach ($unit as $u) { ?>
                            <option value="<?= html_escape($u->id); ?>"><?= html_escape($u->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-4"></div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">&nbsp;</label>
                        <button type="button" class="btn btn-warning btn-block form-control" data-bs-toggle="modal" data-bs-target="#createApp">
                            <i class="fa-solid fa-add"></i>Add Data
                        </button>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">&nbsp;</label>
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
                            <th style="text-align: center; color: white">NO SIPB</th>
                            <th style="text-align: center; color: white">KATEGORI</th>
                            <th style="text-align: center; color: white">UNIT</th>
                            <th style="text-align: center; color: white">TUJUAN</th>
                            <th style="text-align: center; color: white">BIDANG TUJUAN</th>
                            <th style="text-align: center; color: white">VENDOR</th>
                            <th style="text-align: center; color: white">DOKUMEN</th>
                            <th style="text-align: center; color: white">STATUS</th>
                            <th style="text-align: center; color: white">ACTION</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createApp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen p-9" role="document">
        <div class="modal-content">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h5>Input Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="wizard-create-app" class="bs-stepper vertical mt-2 shadow-none">
                    <div class="bs-stepper-header border-0 p-1">
                        <div class="step" data-target="#sipb_reservasi">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Reservasi (TUG 9)</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#sipb_antar_unit">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Permintaan<br/>Antar Unit (PR)</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#sipb_ago">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">AGO</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#sipb_garansi_retrofit">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Klaim Garansi dan<br/>Retrofit</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#sipb_attb">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">ATTB</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#sipb_limbah">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Limbah</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#sipb_manual">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Manual</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content p-1" style="overflow-y: auto; max-height: calc(100vh - 200px);">
                        <div id="sipb_reservasi" class="content pt-4 pt-lg-0">
                            <?php $this->load->view('SIPB/kategori/sipb_reservasi'); ?>
                        </div>
                        <div id="sipb_ago" class="content pt-4 pt-lg-0">
                            <?php $this->load->view('SIPB/kategori/sipb_ago'); ?>
                        </div>
                        <div id="sipb_antar_unit" class="content pt-4 pt-lg-0">
                            <?php $this->load->view('SIPB/kategori/sipb_antar_unit'); ?>
                        </div>
                        <div id="sipb_manual" class="content pt-4 pt-lg-0">
                            <?php $this->load->view('SIPB/kategori/sipb_manual'); ?>
                        </div>
                        <div id="sipb_attb" class="content pt-4 pt-lg-0">
                            <?php $this->load->view('SIPB/kategori/sipb_attb'); ?>
                        </div>
                        <div id="sipb_limbah" class="content pt-4 pt-lg-0">
                            <?php $this->load->view('SIPB/kategori/sipb_limbah'); ?>
                        </div>
                        <div id="sipb_garansi_retrofit" class="content pt-4 pt-lg-0">
                            <?php $this->load->view('SIPB/kategori/sipb_klaim_garansi_retrofit'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createApp2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen p-9" role="document">
        <div class="modal-content">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h5>Detail Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="wizard-create-app2" class="bs-stepper vertical mt-2 shadow-none">
                    <div class="bs-stepper-header border-0 p-1">
                        <div class="step" data-target="#detail_sipb">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase" id="label_sipb">Label SIPB</span>
                                </span>
                            </button>
                        </div>
                        <div id="visible_tug_9">
                            <div class="line"></div>
                            <div class="step" data-target="#detail_tug_9">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title text-uppercase">TUG 9</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div id="visible_sipb">
                            <div class="line"></div>
                            <div class="step" data-target="#update_sipb">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title text-uppercase">Upload SIPB</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="bs-stepper-content p-1" style="overflow-y: auto; max-height: calc(100vh - 200px);">
                        <div id="detail_sipb" class="content pt-4 pt-lg-0">
                            <div id="detail_container"></div>
                        </div>
                        <div id="detail_tug_9" class="content pt-4 pt-lg-0">
                            <div class="col-md-4">
                                <form class="form" action="<?= base_url(); ?>C_SIPB/Update" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                    <div class="row">
                                        <div id="file_dokumen" class="row mb-4">
                                            <div class="col">
                                                <label class="required fw-semibold fs-6 mb-2">TUG 9</label>
                                                <input type="hidden" name="no_sipb_update" class="form-control" placeholder="NO SIPB" required />
                                                <input type="hidden" name="form_name" class="form-control" value="file_tug9" required />
                                                <input type="file" name="file_tug9" id="file_tug9" class="form-control" accept=".pdf" placeholder="TUG 9" required />
                                            </div>
                                        </div>
                                    </div>
                                    <br/>
                                    <div id="btn_simpan_tug" class="text-end">
                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="update_sipb" class="content">
                            <div class="col-md-4">
                                <form class="form" action="<?= base_url(); ?>C_SIPB/Update" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                    <div class="row mb-4">
                                        <div id="file_dokumen">
                                            <div class="col">
                                                <label class="required fw-semibold fs-6 mb-2">SIPB</label>
                                                <input type="hidden" name="no_sipb_update" class="form-control" placeholder="NO SIPB" required />
                                                <input type="hidden" name="form_name" class="form-control" value="file_sipb" required />
                                                <input type="file" name="file_sipb" id="file_sipb" class="form-control" accept=".pdf" placeholder="SIPB" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <a href="#" id="downloadSIPB" class="btn btn-primary" id="download" target="_blank">
                                            <i class="fa fa-download"></i> Download SIPB
                                        </a>
                                        <div id="btn_simpan" class="text-end">
                                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
            "targets": [0, 1, 7, 8],
            "className": "text-center"
        }],
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_SIPB/ajaxSIPB",
            "type": "post",
            "data": function(data) {
                data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>",
                data.unit_asal = $('#unit_asal').val(),
                data.unit_tujuan = $('#unit_tujuan').val(),
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

function resetForm(){
    $('#no_sipb').val('<?= html_escape($sipb); ?>');
    $('#kode').val('<?= $this->session->userdata('unit_id'); ?>');
    $('#reservasi').val('').attr('readonly', false);
    $('#slip').val('').attr('readonly', false);
    $('#plat_no').val('').attr('readonly', false);
    $('#unit_name').val('<?= $this->session->userdata('unit_id'); ?>').trigger('change');
    $('#unit_tujuan').val('').trigger('change').attr('disabled', false);
    $('#bidang_tujuan').val('').trigger('change').attr('disabled', false);
    $('#pembawa_barang').val('').attr('readonly', false);
    $('#pengawas_pekerjaan').val('').attr('readonly', false);
    $('#supervisor_logistik').val('').attr('readonly', false);
    $('#no_spj').val('').attr('readonly', false);
    $('#lokasi').val('').attr('readonly', false);
    $('#pekerjaan').val('').attr('readonly', false);
    $('#vendor').val('').attr('readonly', false);
    $('#file_dokumen').show();
    $('#btn_simpan').show();
    $('#add_data').modal('show');
}

function setKategori(loc) {
    var kategori = $(loc).val();
    $.ajax({
        url: "<?= base_url() ?>C_SIPB/getKategori",
        type: "post",
        data: {
            kategori: kategori,
            <?=$this->security->get_csrf_token_name();?>: "<?=$this->security->get_csrf_hash();?>"
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
            $('#ajaxContainer').html(response);
            $('#table-insert-material tbody').empty();
            insertRow();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}

function detail(no_sipb, form_name, is_selesai){
    $.ajax({
        url: "<?= base_url() ?>C_SIPB/detailSIPB",
        type: "post",
        data: {
            no_sipb: no_sipb,
            <?=$this->security->get_csrf_token_name();?>: "<?=$this->security->get_csrf_hash();?>"
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
            $('#file_dokumen').hide();
            $('#btn_simpan').hide();
            $('#btn_simpan_tug').hide();
            $('#detail_container').html(response);
            $('#downloadSIPB').attr('href', '<?= base_url() ?>C_SIPB/downloadSIPB/' + no_sipb);
            $('input[name="no_sipb_update"]').val(no_sipb);
            $('#label_sipb').html("Edit SIPB / " + form_name);
            if(is_selesai == false){
                if(form_name == "reservasi" || form_name == "ago" || form_name == "manual"){
                    $('#file_dokumen').show();
                    $('#btn_simpan_tug').show();
                    $('#visible_tug_9').show();
                } else {
                    $('#visible_tug_9').hide();
                }
                $('#btn_simpan').show();
                $('#visible_sipb').show();
            } else {
                $('#visible_sipb').hide();
            }
            $('#createApp2').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}
</script>