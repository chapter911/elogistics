<style>
    .not-active {
        color: #acaab1;
        background-color: #f3f2f3;
        border-color: #cdccd0;
        opacity: 1;
    }
</style>

<h3>
    <span class="text-dark">SIPB</span>
    <span class="text-muted">/</span>
    <span class="text-muted">ANTAR UNIT (PR)</span>
</h3>
<form class="form" action="<?= base_url(); ?>C_SIPB/Save" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
        value="<?=$this->security->get_csrf_hash();?>">
    <input type="hidden" name="is_insert" value="<?= isset($header) ? 0 : 1; ?>">
    <input type="hidden" name="form_name" value="antar_unit">
    <div class="row mb-4">
        <h6>1. Data SIPB</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal_antar_unit" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="No SIPB" value="<?= isset($header) ? $header[0]->tanggal : date('Y-m-d'); ?>" <?= isset($header) ? "readonly" : "";?> required />
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">NO SIPB</label>
            <input type="text" name="no_sipb" id="no_sipb_antar_unit"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="No SIPB"
                value="<?= isset($header) ? $header[0]->no_sipb : $sipb; ?>" readonly required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Kode Gudang</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= $header[0]->storage_location; ?>" readonly />
            <?php } else { ?>
            <select name="storage_location" id="storage_location_antar_unit" class="select2" data-placeholder="Pilih Kode Gudang">
                <option value="">- PILIH -</option>
                <?php foreach ($storage_location as $d) { ?>
                <option value="<?= html_escape($d->storage_location); ?>">
                    <?= html_escape($d->storage_location); ?></option>
                <?php } ?>
            </select>
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Nomor Kendaraan</label>
            <input type="text" name="plat_no" id="plat_no_antar_unit"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Plat Nomor"
                <?= isset($header) ? "value='" . $header[0]->plat_no . "'" : "" ?> required />
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Unit Asal</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= $header[0]->unit_asal_name; ?>" readonly />
            <?php } else { ?>
            <input type="hidden" name="unit_asal" id="unit_asal_antar_unit"
                class="form-control form-control-solid mb-3 mb-lg-0" value="<?= $this->session->userdata('unit_id'); ?>"
                required />
            <select name="unit_name" id="unit_name_antar_unit" class="select2" data-placeholder="Pilih Unit Asal"
                disabled>
                <option value="">- PILIH -</option>
                <?php foreach ($unit as $d) { ?>
                <option value="<?= html_escape($d->id); ?>"
                    <?= html_escape($d->id) == $this->session->userdata("unit_id") ? "selected" : ""?>>
                    <?= html_escape($d->name); ?></option>
                <?php } ?>
            </select>
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Unit Tujuan</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= $header[0]->unit_tujuan_name; ?>" readonly />
            <?php } else { ?>
            <select name="unit_tujuan" id="unit_tujuan_antar_unit" class="select2" data-placeholder="Pilih Unit Tujuan">
                <option value="">- PILIH -</option>
                <?php foreach ($unit as $d) { ?>
                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->name); ?></option>
                <?php } ?>
            </select>
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Bidang Tujuan</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->bidang_tujuan); ?>" readonly />
            <?php } else { ?>
            <select name="bidang_tujuan" id="bidang_tujuan_antar_unit" class="select2" data-placeholder="Bidang Tujuan">
                <option value="">- PILIH -</option>
                <?php foreach ($bidang as $d) { ?>
                <option value="<?= html_escape(strtolower($d->bidang_name)); ?>">
                    <?= html_escape(strtoupper($d->bidang_name)); ?></option>
                <?php } ?>
            </select>
            <?php } ?>
        </div>
    </div>
    <hr />
    <div class="row mb-4">
        <h6>2. PIC Tanda Tangan</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Team Leader Logistik</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->ttd_team_leader_logistik); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="ttd_team_leader_logistik" id="team_leader_logistik_antar_unit"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Team Leader Logistik" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Pengawas Pekerjaan</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->ttd_pengawas_pekerjaan); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="ttd_pengawas_pekerjaan" id="pengawas_pekerjaan_antar_unit"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Pengawas Pekerjaan" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Pembawa Barang</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->ttd_pembawa_barang); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="ttd_pembawa_barang" id="pembawa_barang_antar_unit"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Pembawa Barang" required />
            <?php } ?>
        </div>
    </div>
    <hr />
    <div class="row mb-4">
        <h6>3. Data Material</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">No TUG 5 (Permintaan Unit)</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->no_tug5); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="no_tug5" id="no_tug5_antar_unit"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="No TUG 5" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">No TUG 7 (STO)</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->no_tug7); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="no_tug7" id="no_tug7_antar_unit"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="No TUG 7" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">No TUG 8 (Slip Pengeluaran)</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->no_tug8); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="no_tug8" id="no_tug8_antar_unit"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="No TUG 8" required />
            <?php } ?>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Jenis Pekerjaan</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->pekerjaan); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="pekerjaan" id="pekerjaan_antar_unit"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Pekerjaan" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Lokasi</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->lokasi); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="lokasi" id="lokasi_antar_unit" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Lokasi" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Vendor</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->vendor); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="vendor" id="vendor_antar_unit" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Vendor" required />
            <?php } ?>
        </div>
    </div>
    <div class="row mb-4">
        <div class="table-responsive">
            <table id="table_insert_material_antar_unit" class="table table-striped table-bordered tblMaterial"
                style="width: 100%">
                <thead>
                    <tr style="background-color: #008B8B;">
                        <th style="text-align: center; vertical-align: middle; color: white; width: 5%;">
                            # </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 40%;">
                            Material </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 20%;">
                            Merk Material </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 20%;">
                            Nomor Seri Material </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 10%;">
                            Volume </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 5%;">
                            Actions </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(isset($header)){
                        $i = 1;
                        foreach ($detail as $d) { ?>
                    <tr>
                        <td style="text-align: center;"><?= $i++; ?></td>
                        <td><?= $d->material; ?></td>
                        <td style="text-align: center;"><?= $d->merk; ?></td>
                        <td style="text-align: center;"><?= $d->no_seri; ?></td>
                        <td style="text-align: center;"><?= $d->volume; ?></td>
                        <td style="text-align: center;"></td>
                    </tr>
                    <?php }
                    } else { ?>
                    <tr>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-sm btn-primary" onclick="insert_row_antar_unit()">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </td>
                        <td style="text-align: center;">
                            <select name="normalisasi[]" id="normalisasi_antar_unit[]" class='form-control select2'
                                required data-dropdown-parent="#createApp" data-placeholder="Pilih Material">
                                <option></option>
                                <?php foreach ($material as $d) { ?>
                                <option value='<?= html_escape($d->id); ?>'><?= html_escape($d->id); ?> -
                                    <?= html_escape($d->material); ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td style="text-align: center;">
                            <input type='text' name="merk[]" class='form-control' required />
                        </td>
                        <td style="text-align: center;">
                            <input type='text' name="no_seri[]" class='form-control' required />
                        </td>
                        <td style="text-align: center;">
                            <input type='number' name="volume[]" class='form-control' required />
                        </td>
                        <td style="text-align: center;"></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if(!isset($header)){ ?>
    <hr />
    <div class="row mb-4">
        <h6>4. File Pendukung</h6>
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="required fw-semibold fs-6 mb-2">TUG 5</label>
                <input type="file" name="file_tug_5" id="file_tug5" class="form-control" accept=".pdf"
                    placeholder="TUG 5" required />
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="required fw-semibold fs-6 mb-2">TUG 7</label>
                <input type="file" name="file_tug_7" id="file_tug7" class="form-control" accept=".pdf"
                    placeholder="TUG 7" required />
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="required fw-semibold fs-6 mb-2">TUG 8</label>
                <input type="file" name="file_tug_8" id="file_tug8" class="form-control" accept=".pdf"
                    placeholder="TUG 8" required />
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if(!isset($header) || $header[0]->is_selesai == 0) { ?>
        <div class="row mb-4">
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary"><?= !isset($header) ? "Simpan" : "Update";?></button>
            </div>
        </div>
    <?php } ?>
</form>

<script>
function insert_row_antar_unit() {
    $('#table_insert_material_antar_unit > tbody:last-child').append(
        `<tr>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-primary" onclick="insert_row_antar_unit()">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </td>
            <td style="text-align: center;">
                <select name="normalisasi[]" id="normalisasi_antar_unit[]" class='form-control select2' required>
                    <option></option>
                    <?php foreach ($material as $d) { ?>
                        <option value='<?= html_escape($d->id); ?>'><?= html_escape($d->id); ?> - <?= html_escape($d->material); ?></option>
                    <?php } ?>
                </select>
            </td>
            <td style="text-align: center;">
                <input type='text' name="merk[]" class='form-control' required/>
            </td>
            <td style="text-align: center;">
                <input type='text' name="no_seri[]" class='form-control' required/>
            </td>
            <td style="text-align: center;">
                <input type='number' name="volume[]" class='form-control' required/>
            </td>
            <td style="text-align: center;">
                <button type='button' class='btn btn-danger btn-sm' onclick='deleteRow(this)'><i class="fa-solid fa-minus"></i></button>
            </td>
        </tr>`
    );

    $('#table_insert_material_antar_unit select[id="normalisasi_antar_unit[]"]').last().select2({
        placeholder: "Pilih Material",
        dropdownParent: $('#createApp')
    });
}

function deleteRow(loc) {
    $(loc).parent().parent().remove();
}

<?php if(isset($header)) { ?>
$(document).ready(function() {
    $('#storage_location_antar_unit_update').select2({
        dropdownParent: $('#createApp2')
    });
    $('#unit_name_antar_unit_update').select2({
        dropdownParent: $('#createApp2')
    });
    $('#unit_tujuan_antar_unit_update').select2({
        dropdownParent: $('#createApp2')
    });
    $('#bidang_tujuan_antar_unit_update').select2({
        dropdownParent: $('#createApp2')
    });
});
<?php } ?>
</script>