<div class="card card-action mb-12">
    <div class="card-header">
        <h3 class="card-action-title mb-0">Daftar Kontrak</h3>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Kontrak/ExportKontrakRinciUP3" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
                value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Tahun</label>
                        <select name="tahun" id="tahun" class="select2" data-placeholder="Pilih Tahun"
                            onchange="filterData()">
                            <?php for($i = date('Y'); $i > 2020; $i--) { ?>
                            <option value="<?= $i; ?>"><?= $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-6"></div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">&nbsp;&nbsp;</label>
                        <button type="button" class="btn btn-success btn-block w-100" data-bs-toggle="modal"
                            data-bs-target="#modal_add">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>Add Kontrak
                        </button>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">&nbsp;&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block w-100">
                            <span class="fa-solid fa-file-excel"></span> &nbsp; Export
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="card-datatable text-nowrap">
                <table id="table_kontrak" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center;  color:white"> NO </th>
                            <th style="text-align: center;  color:white"> UNIT </th>
                            <th style="text-align: center;  color:white"> BASKET </th>
                            <th style="text-align: center;  color:white"> TAHUN </th>
                            <th style="text-align: center;  color:white"> JENIS ANGGARAN </th>
                            <th style="text-align: center;  color:white"> NO SKKI </th>
                            <th style="text-align: center;  color:white"> NO PRK </th>
                            <th style="text-align: center;  color:white"> NO KHS </th>
                            <th style="text-align: center;  color:white"> NO KONTRAK </th>
                            <th style="text-align: center;  color:white"> VENDOR </th>
                            <th style="text-align: center;  color:white"> LOKASI </th>
                            <th style="text-align: center;  color:white"> KATEGORI MATERIAL </th>
                            <th style="text-align: center;  color:white"> MATERIAL </th>
                            <th style="text-align: center;  color:white"> NILAI KONTRAK </th>
                            <th style="text-align: center;  color:white"> AWAL KONTRAK </th>
                            <th style="text-align: center;  color:white"> AKHIR KONTRAK </th>
                            <th style="text-align: center;  color:white"> NOMOR BAE </th>
                            <th style="text-align: center;  color:white"> AWAL BAE </th>
                            <th style="text-align: center;  color:white"> AKHIR BAE </th>
                            <th style="text-align: center;  color:white"> FILE KONTRAK </th>
                            <th style="text-align: center;  color:white"> FILE BAE </th>
                            <th style="text-align: center;  color:white"> STATUS KONTRAK </th>
                            <th style="text-align: center;  color:white"> STATUS KIRIM </th>
                            <th style="text-align: center;  color:white"> STATUS BAYAR </th>
                            <th style="text-align: center;  color:white"> ACTION </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr style="background-color:#008B8B">
                            <td style="text-align: center;" colspan="12"><b style="color:white"> TOTAL NILAI KONTRAK
                                </b> </td>
                            <td style="text-align: right; color:white;"> <b id="total_nilai_kontrak">0</b> </td>
                            <td style="text-align: center;" colspan="12"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen p-9" role="document">
        <div class="modal-content">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h5>Pembuatan Kontrak Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="<?= base_url() ?>C_Kontrak/SaveUP3" method="POST" class="form"
                    enctype="multipart/form-data">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
                        value="<?=$this->security->get_csrf_hash();?>">
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <label class="required form-label">Sumber Anggaran</label>
                            <select class="form-select select2" name="id_basket" id="id_basket"
                                data-dropdown-parent="#modal_add" data-placeholder="Select an option"
                                data-allow-clear="true" required>
                                <option></option>
                                <?php foreach ($basket as $d) { ?>
                                <option value="<?= html_escape(html_escape($d->id)); ?>">
                                    <?= html_escape(html_escape($d->basket)); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Tahun Anggaran</label>
                            <select class="form-select select2" name="tahun_anggaran" id="tahun_anggaran"
                                data-dropdown-parent="#modal_add" data-placeholder="Select an option"
                                data-allow-clear="true" required>
                                <?php for ($i = 0; $i < 3; $i++) { ?>
                                <option value="<?= date('Y') - $i ?>"><?= date('Y') - $i ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">Jenis Anggaran</label>
                            <select class="form-select select2" name="is_murni" id="is_murni"
                                data-dropdown-parent="#modal_add" data-placeholder="Select an option"
                                data-allow-clear="true" required>
                                <option></option>
                                <option value="1">MURNI</option>
                                <option value="0">LUNCURAN</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">No SKKI</label>
                            <select class="form-select select2" name="id_skki" data-dropdown-parent="#modal_add"
                                data-placeholder="Select an option" data-allow-clear="true" required>
                                <option></option>
                                <?php foreach ($skki as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->no_skki); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">Nomor PRK</label>
                            <select class="form-select select2" name="no_prk" id="no_prk"
                                data-dropdown-parent="#modal_add" data-placeholder="Select an option"
                                data-allow-clear="true" required>
                                <option></option>
                                <?php foreach ($prk as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>">
                                    <?= html_escape($d->no_prk) . ' ' . html_escape($d->uraian_prk); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor KHS / KR</label>
                            <select class="form-select select2" name="no_khs" id="no_khs"
                                data-dropdown-parent="#modal_add" data-placeholder="Select an option"
                                data-allow-clear="true">
                                <option></option>
                                <?php foreach ($khs as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>">
                                    <?= html_escape($d->nomor_khs) . " - " . html_escape($d->judul) . " - " . html_escape($d->vendor); ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">Nomor Kontrak</label>
                            <input type="text" class="form-control" name="no_kontrak" placeholder="" value=""
                                autocomplete="off" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor PO</label>
                            <input type="text" class="form-control" name="no_po" placeholder="" value=""
                                autocomplete="off" />
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">Vendor</label>
                            <select class="form-select select2" name="id_vendor" id="id_vendor"
                                data-dropdown-parent="#modal_add" data-placeholder="Select an option"
                                data-allow-clear="true" required>
                                <option></option>
                                <?php foreach ($vendor as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->vendor); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="required form-label">Tanggal Awal Kontrak</label>
                                    <input type="date" name="awal_kontrak" id="awal_kontrak"
                                        class="form-control datepicker" data-date-format="Y-m-d"
                                        placeholder="Tanggal Awal" autocomplete="off" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="required form-label">Durasi (hari)</label>
                                    <input type="number" name="durasi" id="durasi" onkeyup="hitungDurasi()"
                                        autocomplete="off" class="form-control m-b-5" placeholder="Durasi" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Akhir Kontrak</label>
                                    <input type="date" name="akhir_kontrak" id="akhir_kontrak"
                                        class="form-control form-control-solid" data-date-format="yyyy-mm-dd"
                                        placeholder="Tanggal Akhir" readonly="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="required form-label">Material</label>
                        <div class="table-responsive">
                            <table id="table" class="tblMaterial table table-striped table-bordered">
                                <thead>
                                    <tr style="background-color: #008B8B;">
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 10%;">
                                            UNIT </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 18%;">
                                            MATERIAL </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 8%;">
                                            VOLUME </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 9%;">
                                            HARGA SATUAN </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 9%;">
                                            ONGKOS KIRIM </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 9%;">
                                            TOTAL HARGA + PPN (11%) </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 9%;">
                                            LOKASI </th>
                                        <th
                                            style="text-align: center; vertical-align: middle; color: white; width: 6%;">
                                            ACTIONS </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="clone">
                                        <td style="text-align: center;">
                                            <select name='unit_tujuan_id[]' id='unit_tujuan_id[]'
                                                class='form-control select2 material' data-dropdown-parent='#modal_add'
                                                data-placeholder='Pilih Unit' readonly required>
                                                <option></option>
                                                <?php foreach ($unit as $d) { ?>
                                                <option value='<?= html_escape($d->id); ?>'
                                                    <?= html_escape($d->id) == $this->session->userdata('unit_id') ? 'selected' : 'disabled'; ?>>
                                                    <?= html_escape($d->name); ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td style="text-align: center;">
                                            <select name='material[]' id='material[]'
                                                class='form-control select2 material' data-dropdown-parent='#modal_add'
                                                data-placeholder='Pilih Material' required">
                                                <option></option>
                                                <?php foreach ($material as $d) { ?>
                                                <option value='<?= html_escape($d->id); ?>'><?= html_escape($d->id); ?>
                                                    - <?= html_escape($d->material); ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td style="text-align: center;">
                                            <input type='number' name='volume[]' onkeyup='kalkulasi(this)'
                                                class='form-control' required />
                                        </td>
                                        <td style="text-align: center;">
                                            <input type='number' name='harga[]' onkeyup='kalkulasi(this)'
                                                class='form-control' required />
                                        </td>
                                        <td style="text-align: center;">
                                            <input type='number' name='ongkos[]' onkeyup='kalkulasi(this)'
                                                class='form-control' required />
                                        </td>
                                        <td style="text-align: center;">
                                            <input type='text' name='total[]' class='form-control' readonly />
                                        </td>
                                        <td style="text-align: center;">
                                            <input type='text' name='lokasi[]' class='form-control'
                                                onclick="showMap(this)" readonly />
                                            <input type='hidden' name='latitude[]' class='form-control' readonly />
                                            <input type='hidden' name='longitude[]' class='form-control' readonly />
                                        </td>
                                        <td style="text-align: center;">
                                            <button type='button' class='btn btn-danger'
                                                onclick='deleteRow(this)'>-</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <button type='button' class='btn btn-primary' onclick='duplicateRow()'>TAMBAH</button>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">Nilai Kontrak</label>
                            <input type="text" name="nilai_kontrak" id="nilai_kontrak" autocomplete="off"
                                class="form-control m-b-5 mask-uang" placeholder="Nilai Kontrak"
                                value="<?php if (isset($kontrak)) { echo html_escape($kontrak[0]->nilai_kontrak); } ?>"
                                data-parsley-group="step-1" data-parsley-required="true" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dokumen KR (PDF)</label>
                            <input type="file" name="filekr" accept=".pdf" class="form-control"
                                placeholder="Dokumen KR">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <label class="form-label">Jaminan Pelaksanaan</label>
                            <div class="form-check form-check-custom form-check-solid">
                                <div class="col-md-3">
                                    <input name="is_using_jm" class="form-check-input" type="radio" value="1" id="use"
                                        required />
                                    <label class="form-check-label" for="use">
                                        Menggunakan Jaminan Pelaksanaan
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <input name="is_using_jm" class="form-check-input" type="radio" value="0"
                                        id="not_use" required />
                                    <label class="form-check-label" for="not_use">
                                        Tanpa Jaminan Pelaksanaan
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="required form-label">Nilai Jaminan</label>
                            <input type="text" id="nilai_jaminan" autocomplete="off" class="form-control m-b-5"
                                placeholder="Nilai Jaminan" readonly>
                        </div>
                    </div>
                    <!-- <div class="row mb-5">
                        <div class="col-md-6">
                            <button type="button" data-dismiss="modal" class="btn btn-danger" data-bs-dismiss="modal">BATAL</button>
                            <button type="submit" class="btn btn-primary">SIMPAN</button>
                        </div>
                    </div> -->

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" data-bs-dismiss="modal">BATAL</button>
                <button type="submit" class="btn btn-primary">SIMPAN</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen p-9" role="document">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h2>Edit Kontrak</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <form action="<?= base_url() ?>C_Kontrak/EditUP3" method="POST" class="form"
                    enctype="multipart/form-data">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
                        value="<?=$this->security->get_csrf_hash();?>">
                    <input type="hidden" class="form-control" name="id_edit" id="id_edit" />
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <label class=" form-label">Sumber Anggaran : </label>
                            <select class="form-select select2" name="id_basket_edit" id="id_basket_edit"
                                data-dropdown-parent="#modal_edit" data-placeholder="Select an option"
                                data-allow-clear="true">
                                <option></option>
                                <?php foreach ($basket as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->basket); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class=" form-label">Tahun Anggaran : </label>
                            <input type="text" class="form-control" name="tahun_anggaran_edit" id="tahun_anggaran_edit"
                                readonly />
                        </div>
                        <div class="col-md-3">
                            <label class=" form-label">Jenis Anggaran : </label>
                            <select class="form-select select2" name="is_murni_edit" id="is_murni_edit"
                                data-dropdown-parent="#modal_edit" data-placeholder="Select an option"
                                data-allow-clear="true" required>
                                <option></option>
                                <option value="1">MURNI</option>
                                <option value="0">LUNCURAN</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="required form-label">No SKKI</label>
                            <select class="form-select select2" name="id_skki_edit" id="id_skki_edit"
                                data-dropdown-parent="#modal_edit" data-placeholder="Select an option"
                                data-allow-clear="true" required>
                                <option></option>
                                <?php foreach ($skki as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->no_skki); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class=" form-label">Nomor PRK : </label>
                            <select class="form-select select2" name="no_prk_edit" id="no_prk_edit"
                                data-dropdown-parent="#modal_edit" data-placeholder="Select an option"
                                data-allow-clear="true">
                                <option></option>
                                <?php foreach ($prk as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>">
                                    <?= html_escape($d->no_prk) . ' ' . html_escape($d->uraian_prk); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class=" form-label">Nomor Kontrak : </label>
                            <input type="text" class="form-control form-control-solid" name="nomor_kontrak_edit"
                                id="nomor_kontrak_edit" readonly />
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class=" form-label">Nomor PO : </label>
                            <input type="text" class="form-control" name="nomor_po_edit" id="nomor_po_edit"
                                placeholder="" value="" autocomplete="off" />
                        </div>
                        <div class="col-md-6">
                            <label class=" form-label">File Dokumen KR (PDF) : </label>
                            <input type="file" class="form-control m-b-5" name="filekr" accept=".pdf">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <label class=" form-label">Nomor KHS : </label>
                            <select class="form-select select2" name="no_khs_edit" id="no_khs_edit"
                                data-dropdown-parent="#modal_edit" data-placeholder="Select an option"
                                data-allow-clear="true">
                                <option></option>
                                <?php foreach ($khs as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>">
                                    <?= html_escape($d->nomor_khs) . " - " . html_escape($d->judul) . " - " . html_escape($d->vendor); ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <label class="form-label">Jaminan Pelaksanaan</label>
                            <div class="form-check form-check-custom form-check-solid">
                                <div class="col-md-3">
                                    <input name="is_using_jm_edit" class="form-check-input" type="radio" value="1"
                                        id="use" required />
                                    <label class="form-check-label" for="use">
                                        Menggunakan Jaminan Pelaksanaan
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <input name="is_using_jm_edit" class="form-check-input" type="radio" value="0"
                                        id="not_use" required />
                                    <label class="form-check-label" for="not_use">
                                        Tanpa Jaminan Pelaksanaan
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-danger"
                            data-bs-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_import" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Import</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url() ?>C_Kontrak/ImportSaveUP3" method="POST" class="form"
                enctype="multipart/form-data">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
                    value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body scroll-y m-5">
                    <div class="col">
                        <label class="required form-label">File Excel</label>
                        <input type="file" name="fileimport" accept=".xls,.xlsx" class="form-control"
                            placeholder="Dokumen Import">
                        <br />
                        <a href="<?= base_url() ?>data_uploads/kontrak_up3/format/FormatKontrakRinci 2024-09-11.xlsx"
                            target="_blank" download class="btn btn-primary">Format Excel</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger"
                        data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="kt_modal_material" tabindex="-1">
    <div class="modal-dialog modal-fullscreen p-9">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h2>List Detail Material</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <div class="table-responsive" id="materialKontrak"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="kt_modal_nilai" tabindex="-1">
    <div class="modal-dialog modal-fullscreen p-9">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h2>Detail Nilai Kontrak</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <div class="table-responsive" id="nilaiKontrak"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_map" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Pick on Map</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <div id="map" style="width: 100%; height: 400px"></div>
                <br />
                <input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" readonly />
                <br />
                <div class="float-end">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var table_kontrak;
var map;
var loc_text;

$(document).ready(function() {
    table_kontrak = $("#table_kontrak").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 10,
        "columnDefs": [{
            targets: [3, 9, 12, 13, 17],
            className: 'dt-body-center'
        }, {
            targets: [11],
            className: 'dt-body-right'
        }],
        "ajax": {
            "url": "<?= base_url() ?>C_Kontrak/ajaxKontrakUP3",
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
                data.tahun = $("#tahun").val(),
                    data.<?=$this->security->get_csrf_token_name();?> =
                    "<?=$this->security->get_csrf_hash();?>"
            },
            "complete": function(response) {
                var data = response.responseJSON;
                $('#total_nilai_kontrak').html(data['total']);
                Swal.close();
            },
            "error": function(jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        }
    });
});

function filterData() {
    table_kontrak.ajax.reload();
}

function showMap(data) {
    loc_text = data;
    map.on('click', onMapClick);
    $('#modal_map').modal('show');
    setInterval(function() {
        map.invalidateSize();
    }, 400);
}


function onMapClick(e) {
    console.log(e);
    map.eachLayer((layer) => {
        if (layer instanceof L.Marker) {
            layer.remove();
        }
    });
    L.marker([e.latlng['lat'], e.latlng['lng']]).addTo(map).bindPopup("Click Ok untuk Set Lokasi.").openPopup();
    var loc = $(loc_text).closest('tr');
    $.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + e.latlng['lat'] + '&lon=' + e.latlng[
        'lng'] + '',
        function(data) {
            // console.log(data.display_name);
            $('#nama_lokasi').val(data.display_name);
            loc.find('input[name="lokasi[]"]').val(data.display_name);
        });
    loc.find('input[name="latitude[]"]').val(e.latlng['lat'].toFixed(6));
    loc.find('input[name="longitude[]"]').val(e.latlng['lng'].toFixed(6));
}
</script>

<?php
$this->load->view('kontrak_up3/index_js');
?>