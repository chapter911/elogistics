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
            <input type="date" name="tanggal" id="tanggal_ago"
                class="form-control form-control-solid mb-3 mb-lg-0 <?= isset($header) ? "not-active" : "" ?>"
                placeholder="Tanggal" value="<?= isset($header) ? $header[0]->tanggal : date('Y-m-d'); ?>"
                <?= isset($header) ? "readonly" : "";?> required />
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">NO SIPB</label>
            <input type="text" name="no_sipb" id="no_sipb_ago"
                class="form-control form-control-solid mb-3 mb-lg-0 <?= isset($header) ? "not-active" : "" ?>"
                placeholder="No SIPB" value="<?= isset($header) ? $header[0]->no_sipb : $sipb; ?>" readonly required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Kode Gudang</label>
            <select name="storage_location"
                id="<?= isset($header) ? "storage_location_ago_update" : "storage_location_ago"; ?>" class="select2"
                data-placeholder="Pilih Kode Gudang">
                <option value="">- PILIH -</option>
                <?php foreach ($storage_location as $d) { ?>
                <option value="<?= html_escape($d->storage_location); ?>"
                    <?= isset($header) && ($header[0]->storage_location == $d->storage_location) ? "selected" : "" ?>>
                    <?= html_escape($d->storage_location); ?></option>
                <?php } ?>
            </select>
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
            <input type="hidden" name="unit_asal" id="unit_asal_ago" class="form-control mb-3 mb-lg-0"
                value="<?= isset($header) ? $header[0]->unit_asal : $this->session->userdata('unit_id'); ?>" required />
            <select name="unit_name" id="<?= isset($header) ? "unit_name_ago_update" : "unit_name_ago"; ?>"
                class="select2" data-placeholder="Pilih Unit Asal" disabled>
                <option value="">- PILIH -</option>
                <?php foreach ($unit as $d) { ?>
                <option value="<?= html_escape($d->id); ?>" <?php if(isset($header)){
                        if($header[0]->unit_asal == $d->id){
                            echo "selected";
                        }
                    } else if(html_escape($d->id) == $this->session->userdata("unit_id")){
                        echo "selected";
                    } ?>>
                    <?= html_escape($d->name); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Unit Tujuan</label>
            <select name="unit_tujuan" id="<?= isset($header) ? "unit_tujuan_ago_update" : "unit_tujuan_ago"; ?>"
                class="select2" data-placeholder="Pilih Unit Tujuan">
                <option value="">- PILIH -</option>
                <?php foreach ($unit as $d) { ?>
                <option value="<?= html_escape($d->id); ?>" <?php if(isset($header)){
                        if($header[0]->unit_tujuan == $d->id){
                            echo "selected";
                        }
                    } else if(html_escape($d->id) == $this->session->userdata("unit_id")){
                        echo "selected";
                    } ?>>
                    <?= html_escape($d->name); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Bidang Tujuan</label>
            <select name="bidang_tujuan" id="<?= isset($header) ? "bidang_tujuan_ago_update" : "bidang_tujuan_ago"; ?>"
                class="select2" data-placeholder="Bidang Tujuan">
                <option value="">- PILIH -</option>
                <?php foreach ($bidang as $d) { ?>
                <option value="<?= html_escape(strtolower($d->bidang_name)); ?>" <?php if(isset($header)){
                        if(strtolower($header[0]->bidang_tujuan) == strtolower($d->bidang_name)){
                            echo "selected";
                        }
                    } ?>>
                    <?= html_escape(strtoupper($d->bidang_name)); ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <hr />
    <div class="row mb-4">
        <h6>2. PIC Tanda Tangan</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Team Leader Logistik</label>
            <input type="text" name="ttd_team_leader_logistik" id="team_leader_logistik_ago"
                <?= isset($header) ? "value='" . $header[0]->ttd_team_leader_logistik . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Team Leader Logistik" required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Pengawas Pekerjaan</label>
            <input type="text" name="ttd_pengawas_pekerjaan" id="pengawas_pekerjaan_ago"
                <?= isset($header) ? "value='" . $header[0]->ttd_pengawas_pekerjaan . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Pengawas Pekerjaan" required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Pembawa Barang</label>
            <input type="text" name="ttd_pembawa_barang" id="pembawa_barang_ago"
                <?= isset($header) ? "value='" . $header[0]->ttd_pembawa_barang . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Pembawa Barang" required />
        </div>
    </div>
    <hr />
    <div class="row mb-4">
        <h6>3. Data Material</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Reservasi</label>
            <input type="text" name="reservasi" id="reservasi_ago"
                <?= isset($header) ? "value='" . $header[0]->reservasi . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Reservasi" required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Slip Pengeluaran Barang (TUG 9 AGO)</label>
            <input type="text" name="slip" id="slip_ago" <?= isset($header) ? "value='" . $header[0]->slip . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="SLIP" required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">NO SPJ</label>
            <select name="no_spj"
                id="<?= isset($header) ? "no_spj_ago_update" : "no_spj_ago"; ?>"
                class="select2" data-placeholder="NO SPJ" onchange="getVendor(this)">
                <option value="">- PILIH -</option>
                <?php foreach ($kr as $d) { ?>
                <option value="<?= html_escape(strtolower($d->no_kr)); ?>" <?php if(isset($header)){
                        if(strtolower($header[0]->no_spj) == strtolower($d->no_kr)){
                            echo "selected";
                        }
                    } ?>>
                    <?= html_escape(strtoupper($d->no_kr)); ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Jenis Pekerjaan</label>
            <input type="text" name="pekerjaan" id="pekerjaan_ago"
                <?= isset($header) ? "value='" . $header[0]->pekerjaan . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Pekerjaan" required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi_ago"
                <?= isset($header) ? "value='" . $header[0]->lokasi . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Lokasi" required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Vendor</label>
            <input type="text" name="vendor" id="vendor_ago"
                <?= isset($header) ? "value='" . $header[0]->vendor . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Vendor" required readonly/>
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
                <input type="file" name="file_reservasi" id="file_reservasion_ago" class="form-control" accept=".pdf"
                    placeholder="Reservasi Aktivasi Material (AGO)" required />
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
    $('#no_spj_ago_update').select2({
        dropdownParent: $('#createApp2')
    });
});
<?php } ?>
</script>