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
    <span class="text-muted">AGO</span>
</h3>
<form class="form" action="<?= base_url(); ?>C_SIPB/Save" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
        value="<?=$this->security->get_csrf_hash();?>">
    <input type="hidden" name="is_insert" value="<?= isset($header) ? 0 : 1; ?>">
    <input type="hidden" name="form_name" value="ago">
    <div class="row mb-4">
        <h6>1. Data SIPB</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal_ago" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="No SIPB" value="<?= isset($header) ? $header[0]->tanggal : date('Y-m-d'); ?>" <?= isset($header) ? "readonly" : "";?> required />
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">NO SIPB</label>
            <input type="text" name="no_sipb" id="no_sipb_ago" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="No SIPB" value="<?= isset($header) ? $header[0]->no_sipb : $sipb; ?>" readonly required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Kode Gudang</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= $header[0]->storage_location; ?>" readonly />
            <?php } else { ?>
            <select name="storage_location" id="storage_location_ago" class="select2" data-placeholder="Pilih Kode Gudang">
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
            <input type="text" name="plat_no" id="plat_no_ago" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Plat Nomor" <?= isset($header) ? "value='" . $header[0]->plat_no . "'" : "" ?> required />
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Unit Asal</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= $header[0]->unit_asal_name; ?>" readonly />
            <?php } else { ?>
            <input type="hidden" name="unit_asal" id="unit_asal_ago"
                class="form-control form-control-solid mb-3 mb-lg-0" value="<?= $this->session->userdata('unit_id'); ?>"
                required />
            <select name="unit_name" id="unit_name_ago" class="select2" data-placeholder="Pilih Unit Asal" disabled>
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
            <select name="unit_tujuan" id="unit_tujuan_ago" class="select2" data-placeholder="Pilih Unit Tujuan">
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
            <select name="bidang_tujuan" id="bidang_tujuan_ago" class="select2" data-placeholder="Bidang Tujuan">
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
            <input type="text" name="ttd_team_leader_logistik" id="team_leader_logistik_ago"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Team Leader Logistik" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Pengawas Pekerjaan</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->ttd_pengawas_pekerjaan); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="ttd_pengawas_pekerjaan" id="pengawas_pekerjaan_ago"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Pengawas Pekerjaan" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Pembawa Barang</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->ttd_pembawa_barang); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="ttd_pembawa_barang" id="pembawa_barang_ago"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Pembawa Barang" required />
            <?php } ?>
        </div>
    </div>
    <hr />
    <div class="row mb-4">
        <h6>3. Data Material</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Reservasi</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->reservasi); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="reservasi" id="reservasi_ago" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Reservasi" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Slip Pengeluaran Barang (TUG 9 AGO)</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->slip); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="slip" id="slip_ago" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="SLIP" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">NO SPJ</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->no_spj); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="no_spj" id="no_spj_ago" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="NO SPJ" required />
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
            <input type="text" name="pekerjaan" id="pekerjaan_ago" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Pekerjaan" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Lokasi</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->lokasi); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="lokasi" id="lokasi_ago" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Lokasi" required />
            <?php } ?>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Vendor</label>
            <?php if(isset($header)){ ?>
            <input type="text" class="form-control form-control-solid mb-3 mb-lg-0"
                value="<?= strtoupper($header[0]->vendor); ?>" readonly />
            <?php } else { ?>
            <input type="text" name="vendor" id="vendor_ago" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Vendor" required />
            <?php } ?>
        </div>
    </div>
    <div class="row mb-4">
        <div class="table-responsive">
            <table id="table_insert_material_ago" class="table table-striped table-bordered tblMaterial"
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
                            <button type="button" class="btn btn-sm btn-primary" onclick="insert_row_ago()">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </td>
                        <td style="text-align: center;">
                            <select name="normalisasi[]" id="normalisasi_ago[]" class='form-control select2' required
                                data-dropdown-parent="#createApp" data-placeholder="Pilih Material">
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
                <label class="required fw-semibold fs-6 mb-2">Reservasi Aktivasi Material (AGO)</label>
                <input type="file" name="file_reservasi" id="file_reservasion_ago" class="form-control"
                    accept=".pdf" placeholder="Reservasi Aktivasi Material (AGO)" required />
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="required fw-semibold fs-6 mb-2">TUG 9 AGO</label>
                <input type="file" name="file_tug_9" id="file_tug9_ago" class="form-control" accept=".pdf"
                    placeholder="TUG 9" />
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
function insert_row_ago() {
    $('#table_insert_material_ago > tbody:last-child').append(
        `<tr>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-primary" onclick="insert_row_ago()">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </td>
            <td style="text-align: center;">
                <select name="normalisasi[]" id="normalisasi_ago[]" class='form-control select2' required>
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

    $('#table_insert_material_ago select[id="normalisasi_ago[]"]').last().select2({
        placeholder: "Pilih Material",
        dropdownParent: $('#createApp')
    });
}

function deleteRow(loc) {
    $(loc).parent().parent().remove();
}

<?php if(isset($header)) { ?>
$(document).ready(function() {
    $('#storage_location_ago_update').select2({
        dropdownParent: $('#createApp2')
    });
    $('#unit_name_ago_update').select2({
        dropdownParent: $('#createApp2')
    });
    $('#unit_tujuan_ago_update').select2({
        dropdownParent: $('#createApp2')
    });
    $('#bidang_tujuan_ago_update').select2({
        dropdownParent: $('#createApp2')
    });
});
<?php } ?>
</script>