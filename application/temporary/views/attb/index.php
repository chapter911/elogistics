<script src="<?= base_url(); ?>assets/js/modal-create-app.js"></script>

<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">ATTB</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="#" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit</label>
                        <select name="unit" id="unit" class="form-selectb select2" data-placeholder="Pilih Unit"
                            onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($unit as $u) { ?>
                            <option value="<?= html_escape($u->id); ?>"><?= html_escape($u->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">No ATTB</label>
                        <input type="number" name="no_attb" id="no_attb" class="form-control" placeholder="No ATTB">
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Material</label>
                        <select name="material_id" id="material_id" class="form-control select2"
                            onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($material as $m) { ?>
                            <option value="<?= html_escape($m->id); ?>"><?= html_escape($m->material); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Lokasi Gudang</label>
                        <select name="location" id="location" class="form-control select2" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($gudang as $g) { ?>
                            <option value="<?= html_escape($g->nama_gudang); ?>"><?= html_escape($g->nama_gudang); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-1">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">&nbsp;</label>
                        <button type="button" class="btn btn-success btn-warning form-control waves-effect waves-light"
                            onclick="filterData()">
                            <i class="fa-solid fa-search"></i> &nbsp; Filter
                        </button>
                    </div>
                </div>
                <div class="col-1"></div>
                <div class="col-1">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Add</label>
                        <button type="button" class="btn btn-warning btn-block form-control" data-bs-toggle="modal"
                            data-bs-target="#add_data">
                            <i class="fa-solid fa-plus"></i> &nbsp; Input
                        </button>
                    </div>
                </div>
                <div class="col-1">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Import</label>
                        <button type="button" class="btn btn-primary btn-block form-control" data-bs-toggle="modal"
                            data-bs-target="#modal_upload">
                            <i class="fa-solid fa-file-excel"></i> &nbsp; Import
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
                            <th style="text-align: center; vertical-align: middle; color: white;"> NO </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> UNIT </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> NO ATTB </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TUG </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> SIPB </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> NORMALISASI </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> MATERIAL </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> KATEGORI </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> VOLUME </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> STATUS </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> DURASI </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> FOTO </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> LOKASI </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> ACTION </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header" id="kt_add_data_user_header">
                <h2 class="fw-bold" id="modal_title">Input Attb</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 my-7">
                <form class="form" action="<?= base_url(); ?>C_ATTB/Save" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_add_data_user_scroll"
                        data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_add_data_user_header"
                        data-kt-scroll-wrappers="#kt_add_data_user_scroll" data-kt-scroll-offset="300px">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">Nomor ATTB</label>
                                    <input type="number" name="no_attb" id="no_attb" class="form-control mb-3 mb-lg-0"
                                        placeholder="Nomor ATTB">
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">TUG 10</label>
                                    <input type="number" name="tug" id="tug" class="form-control mb-3 mb-lg-0"
                                        placeholder="TUG 10" required />
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">SIPB</label>
                                    <input type="number" name="sipb" id="sipb" class="form-control mb-3 mb-lg-0"
                                        placeholder="SIPB" required />
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">Normalisasi</label>
                                    <input type="number" name="material_id" id="material_id"
                                        class="form-control mb-3 mb-lg-0" placeholder="Normalisasi" required />
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">Volume</label>
                                    <input type="number" name="volume" id="volume" class="form-control mb-3 mb-lg-0"
                                        placeholder="Volume" required />
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="fw-semibold fs-6 mb-2">Pat</label>
                                    <input type="number" name="pat" id="pat" class="form-control mb-3 mb-lg-0"
                                        placeholder="Pat">
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="fw-semibold fs-6 mb-2">Serial Number</label>
                                    <input type="number" name="serial_number" id="serial_number"
                                        class="form-control mb-3 mb-lg-0" placeholder="Serial Number" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">Status</label>
                                    <select name="status_id" id="status_id" class="form-select" data-control="select2"
                                        data-dropdown-parent='#kt_add_data_form' data-placeholder="Pilih Status"
                                        required>
                                        <option></option>
                                        <option value="0">Belum DiUsulkan</option>
                                        <option value="1">AE 1</option>
                                        <option value="2">AE 2</option>
                                        <option value="3">AE 3</option>
                                        <option value="4">AE 4</option>
                                    </select>
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">Status Date</label>
                                    <input type="date" name="status_update_date" id="status_update_date"
                                        class="form-control mb-3 mb-lg-0" placeholder="Status Date" required />
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">Gudang</label>
                                    <select name="location" id="location" class="form-select" data-control="select2"
                                        data-dropdown-parent='#kt_add_data_form' data-placeholder="Pilih Gudang"
                                        required>
                                        <option></option>
                                        <?php foreach($gudang as $g) { ?>
                                        <option value="<?= html_escape($g->nama_gudang); ?>"><?= html_escape($g->nama_gudang); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Pindah Gudang</label>
                                    <input type="date" name="location_update_date" id="location_update_date"
                                        class="form-control mb-3 mb-lg-0" placeholder="Location Update Date" required />
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Foto 1</label>
                                            <input type="file" name="foto1" id="foto1" class="form-control mb-3 mb-lg-0"
                                                accept="image/png, image/gif, image/jpeg" placeholder="Foto 1"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Foto 2</label>
                                            <input type="file" name="foto2" id="foto2" class="form-control mb-3 mb-lg-0"
                                                accept="image/png, image/gif, image/jpeg" placeholder="Foto 2"
                                                required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Foto 3</label>
                                            <input type="file" name="foto3" id="foto3" class="form-control mb-3 mb-lg-0"
                                                accept="image/png, image/gif, image/jpeg" placeholder="Foto 3"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Foto 4</label>
                                            <input type="file" name="foto4" id="foto4" class="form-control mb-3 mb-lg-0"
                                                accept="image/png, image/gif, image/jpeg" placeholder="Foto 4"
                                                required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">File SIPB</label>
                                            <input type="file" name="sipb_file" id="sipb_file"
                                                class="form-control mb-3 mb-lg-0" accept="application/pdf"
                                                placeholder="File SIPB" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">File TUG</label>
                                            <input type="file" name="tug_file" id="tug_file"
                                                class="form-control mb-3 mb-lg-0" accept="application/pdf"
                                                placeholder="File TUG" required />
                                        </div>
                                    </div>
                                </div>
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
</div>

<div class="modal fade" id="modal_upload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Upload Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_ATTB/import" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">File Dokumen Stock (Excel)</label>
                            <input type="file" name="upload_file" id="upload_file" class="form-control"
                                accept=".xls,.xlsx" placeholder="xls,.xlsx" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <a href="<?= base_url(); ?>data_uploads/attb/Format Import Attb.xlsx" download target="_blank"
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

<div class="modal fade" id="modal_foto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Foto</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 py-10">
                <img src="" id="image" style="width: 100%; height: 100%; object-fit: cover;">
                <hr />
                <form action="<?= base_url() ?>C_ATTB/upload_foto" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    Update Foto
                    <div class="mb-10 d-flex align-items-center justify-content-end">
                        <input type="hidden" class="form-control" id="hdr_id" name="hdr_id" value="0">
                        <input type="hidden" class="form-control" id="foto" name="foto" value="0">
                        <input type="file" class="form-control" id="upload_foto" name="upload_foto"
                            accept=".jpg,.jpeg,.png" required>
                        <button type="submit" class="btn btn-primary me-2">
                            <span class="indicator-label">Update</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" data-bs-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createApp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Update ATTB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="wizard-create-app" class="bs-stepper vertical mt-2 shadow-none">
                    <div class="bs-stepper-header border-0 p-1">
                        <div class="step" data-target="#status_form">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Status</span>
                                    <span class="bs-stepper-subtitle">Enter Details</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#gudang_form">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-box"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Gudang</span>
                                    <span class="bs-stepper-subtitle">Enter Details</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content p-1">
                        <div id="status_form" class="content pt-4 pt-lg-0">
                            <form class="form" action="<?= base_url(); ?>C_ATTB/Update" method="POST">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <input type="hidden" name="type" value="status" required />
                                <input type="hidden" name="edit_id" id="edit_id_status" required />
                                <input type="hidden" name="edit_no_attb" id="edit_no_attb_status" required />
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Status</label>
                                    <select name="status_id" id="status_edit" class="form-select"
                                        data-placeholder="Pilih Status" required>
                                        <option></option>
                                        <option value="0">Belum DiUsulkan</option>
                                        <option value="1">AE 1</option>
                                        <option value="2">AE 2</option>
                                        <option value="3">AE 3</option>
                                        <option value="4">AE 4</option>
                                    </select>
                                </div>
                                <div class="mb-6">
                                    <label class="required fw-semibold fs-6 mb-2">Status Date</label>
                                    <input type="date" name="status_update_date" id="status_edit_date"
                                        class="form-control" placeholder="Status Date" required />
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
                        <div id="gudang_form" class="content pt-4 pt-lg-0">
                            <form class="form" action="<?= base_url(); ?>C_ATTB/Update" method="POST"
                                enctype="multipart/form-data">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <input type="hidden" name="type" value="location" required />
                                <input type="hidden" name="edit_id" id="edit_id_location" required />
                                <input type="hidden" name="edit_no_attb" id="edit_no_attb_location" required />
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">SIPB</label>
                                    <input type="number" name="sipb" class="form-control mb-3 mb-lg-0"
                                        placeholder="SIPB" required />
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">Gudang</label>
                                    <select name="location" id="location_edit" class="form-select"
                                        data-control="select2" data-dropdown-parent='#kt_modal_update'
                                        data-placeholder="Pilih Gudang" required>
                                        <option></option>
                                        <?php foreach($gudang as $g) { ?>
                                        <option value="<?= html_escape($g->nama_gudang); ?>"><?= html_escape($g->nama_gudang); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="fv-row mb-5">
                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Pindah Gudang</label>
                                    <input type="date" name="location_update_date" id="location_edit_date"
                                        class="form-control mb-3 mb-lg-0" placeholder="Location Update Date" required />
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Foto 1</label>
                                            <input type="file" name="foto1" class="form-control mb-3 mb-lg-0"
                                                accept="image/png, image/gif, image/jpeg" placeholder="Foto 1"
                                                required />
                                        </div>
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Foto 2</label>
                                            <input type="file" name="foto2" class="form-control mb-3 mb-lg-0"
                                                accept="image/png, image/gif, image/jpeg" placeholder="Foto 2"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Foto 3</label>
                                            <input type="file" name="foto3" class="form-control mb-3 mb-lg-0"
                                                accept="image/png, image/gif, image/jpeg" placeholder="Foto 3"
                                                required />
                                        </div>
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Foto 4</label>
                                            <input type="file" name="foto4" class="form-control mb-3 mb-lg-0"
                                                accept="image/png, image/gif, image/jpeg" placeholder="Foto 4"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">File SIPB</label>
                                            <input type="file" name="sipb_file" class="form-control mb-3 mb-lg-0"
                                                accept="application/pdf" placeholder="File SIPB" required />
                                        </div>
                                    </div>
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

<div class="modal fade" id="modal_update" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h2 class="fw-bold">Update Attb</h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <div class="row">
                    <div class="col-2">
                        <ul class="nav nav-tabs nav-pills border-0 flex-row flex-md-column me-5 mb-3 mb-md-0 fs-6">
                            <li class="nav-item w-md-200px me-0">
                                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Status</a>
                            </li>
                            <li class="nav-item w-md-200px me-0">
                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Gudang</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-10">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                                <form id="kt_modal_add_rencana_form" class="form"
                                    action="<?= base_url() ?>C_ATTB/Update" method="POST">
                                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                    <input type="hidden" name="type" value="status" required />
                                    <input type="hidden" name="edit_id" id="edit_id_status" required />
                                    <input type="hidden" name="edit_no_attb" id="edit_no_attb_status" required />
                                    <div class="d-flex flex-column scroll-y px-5 px-lg-10"
                                        id="kt_modal_add_rencana_scroll" data-kt-scroll="true"
                                        data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                                        data-kt-scroll-dependencies="#kt_modal_add_user_header"
                                        data-kt-scroll-wrappers="#kt_modal_add_user_scroll"
                                        data-kt-scroll-offset="300px">
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Status</label>
                                            <select name="status_id" id="status_edit" class="form-select"
                                                data-control="select2" data-dropdown-parent='#modal_update'
                                                data-placeholder="Pilih Status" required>
                                                <option></option>
                                                <option value="0">Belum DiUsulkan</option>
                                                <option value="1">AE 1</option>
                                                <option value="2">AE 2</option>
                                                <option value="3">AE 3</option>
                                                <option value="4">AE 4</option>
                                            </select>
                                        </div>
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Status Date</label>
                                            <input type="date" name="status_update_date" id="status_edit_date"
                                                class="form-control mb-3 mb-lg-0" placeholder="Status Date" required />
                                        </div>
                                    </div>
                                    <div class="text-center pt-10">
                                        <button type="reset" class="btn btn-light me-3"
                                            data-kt-users-modal-action="cancel">BATAL</button>
                                        <button type="submit" class="btn btn-primary"
                                            data-kt-users-modal-action="submit">
                                            <span class="indicator-label">SIMPAN</span>
                                            <span class="indicator-progress">Please wait...
                                                <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                                <form id="kt_modal_add_user_form" class="form" action="<?= base_url() ?>C_ATTB/Update"
                                    method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                    <input type="hidden" name="type" value="location" required />
                                    <input type="hidden" name="edit_id" id="edit_id_location" required />
                                    <input type="hidden" name="edit_no_attb" id="edit_no_attb_location" required />
                                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll"
                                        data-kt-scroll="true" data-kt-scroll-activate="true"
                                        data-kt-scroll-max-height="auto"
                                        data-kt-scroll-dependencies="#kt_modal_add_user_header"
                                        data-kt-scroll-wrappers="#kt_modal_add_user_scroll"
                                        data-kt-scroll-offset="300px">
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">SIPB</label>
                                            <input type="number" name="sipb" class="form-control mb-3 mb-lg-0"
                                                placeholder="SIPB" required />
                                        </div>
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Gudang</label>
                                            <select name="location" id="location_edit" class="form-select"
                                                data-control="select2" data-dropdown-parent='#modal_update'
                                                data-placeholder="Pilih Gudang" required>
                                                <option></option>
                                                <?php foreach($gudang as $g) { ?>
                                                <option value="<?= html_escape($g->nama_gudang); ?>"><?= html_escape($g->nama_gudang); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="fv-row mb-5">
                                            <label class="required fw-semibold fs-6 mb-2">Tanggal Pindah Gudang</label>
                                            <input type="date" name="location_update_date" id="location_edit_date"
                                                class="form-control mb-3 mb-lg-0" placeholder="Location Update Date"
                                                required />
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="fv-row mb-5">
                                                    <label class="required fw-semibold fs-6 mb-2">Foto 1</label>
                                                    <input type="file" name="foto1" class="form-control mb-3 mb-lg-0"
                                                        accept="image/png, image/gif, image/jpeg" placeholder="Foto 1"
                                                        required />
                                                </div>
                                                <div class="fv-row mb-5">
                                                    <label class="required fw-semibold fs-6 mb-2">Foto 2</label>
                                                    <input type="file" name="foto2" class="form-control mb-3 mb-lg-0"
                                                        accept="image/png, image/gif, image/jpeg" placeholder="Foto 2"
                                                        required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="fv-row mb-5">
                                                    <label class="required fw-semibold fs-6 mb-2">Foto 3</label>
                                                    <input type="file" name="foto3" class="form-control mb-3 mb-lg-0"
                                                        accept="image/png, image/gif, image/jpeg" placeholder="Foto 3"
                                                        required />
                                                </div>
                                                <div class="fv-row mb-5">
                                                    <label class="required fw-semibold fs-6 mb-2">Foto 4</label>
                                                    <input type="file" name="foto4" class="form-control mb-3 mb-lg-0"
                                                        accept="image/png, image/gif, image/jpeg" placeholder="Foto 4"
                                                        required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="fv-row mb-5">
                                                    <label class="required fw-semibold fs-6 mb-2">File SIPB</label>
                                                    <input type="file" name="sipb_file"
                                                        class="form-control mb-3 mb-lg-0" accept="application/pdf"
                                                        placeholder="File SIPB" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center pt-10">
                                        <button type="reset" class="btn btn-light me-3"
                                            data-kt-users-modal-action="cancel">BATAL</button>
                                        <button type="submit" class="btn btn-primary"
                                            data-kt-users-modal-action="submit">
                                            <span class="indicator-label">SIMPAN</span>
                                            <span class="indicator-progress">Please wait...
                                                <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
</div>

<div class="modal fade" id="modal_history" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">History</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 py-10">
                <div id="ajaxHistory"></div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" data-bs-dismiss="modal">TUTUP</button>
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
            "className": "dt-body-center",
            "targets": [3, 4, 5, 8]
        }],
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_ATTB/getData",
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
                    data.no_attb = $("#no_attb").val(),
                    data.material_id = $("#material_id").val(),
                    data.location = $("#location").val(),
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

function history(id) {
    $.ajax({
        url: "<?= base_url() ?>C_ATTB/getHistory",
        type: "post",
        data: {
            id: id,
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
            $("#ajaxHistory").html(response);
            $("#modal_history").modal("show");
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
        }
    });
}

function update(id) {
    $.ajax({
        url: "<?= base_url() ?>C_ATTB/getDetail",
        type: "post",
        data: {
            id: id,
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
            $("#edit_id_status").val(id);
            $("#edit_id_location").val(id);
            $("#edit_no_attb_status").val(data[0].no_attb)
            $("#edit_no_attb_location").val(data[0].no_attb)
            $("#status_edit").val(data[0].status_id).trigger('change');
            $("#location_edit").val(data[0].location).trigger('change');
            // $("#modal_update").modal("show");
            $("#createApp").modal("show");
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
        }
    });
}

function foto(id_hdr, urutan, image) {
    $("#hdr_id").val(id_hdr);
    $("#foto").val(urutan);
    $("#image").attr("src", "<?= base_url(); ?>" + image);
    $("#modal_history").modal("hide");
    $("#modal_foto").modal("show");
}

function deleteAttb() {
    $("#kt_add_data").modal('hide');
    Swal.fire({
        title: 'Konfirmasi',
        text: "Anda yakin ingin menghapus data ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        customClass: {
            cancelButton: 'btn btn-secondary',
            confirmButton: 'btn btn-danger'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= base_url() ?>C_ATTB/delete",
                type: "post",
                data: {
                    id: id_attb,
                    <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon Tunggu',
                        html: 'Sedang menghapus data',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                    Swal.showLoading();
                },
                success: function(response) {
                    console.log(response);
                    Swal.close();
                    if (response == "success") {
                        Swal.fire(
                            'Berhasil',
                            'Data berhasil dihapus',
                            'success'
                        ).then(function() {
                            table.ajax.reload();
                        });
                    } else {
                        Swal.fire(
                            'Gagal',
                            'Data gagal dihapus',
                            'error'
                        );
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                }
            });
        }
    });
}
</script>