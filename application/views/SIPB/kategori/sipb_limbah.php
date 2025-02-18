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
    <span class="text-muted">LIMBAH</span>
</h3>
<form class="form" action="<?= base_url(); ?>C_SIPB/Save" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
        value="<?=$this->security->get_csrf_hash();?>">
    <input type="hidden" name="is_insert" value="<?= isset($header) ? 0 : 1; ?>">
    <input type="hidden" name="form_name" value="limbah">
    <div class="row mb-4">
        <h6>1. Data SIPB</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal_limbah"
                class="form-control mb-3 mb-lg-0 <?= isset($header) ? "not-active" : "" ?>" placeholder="Tanggal"
                <?= isset($header) ? "value='" . $header[0]->tanggal . "'" : "value='" . date('Y-m-d') . "'" ?>
                <?= isset($header) ? "readonly" : "";?> required />
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">NO SIPB</label>
            <input type="text" name="no_sipb" id="no_sipb_limbah"
                class="form-control mb-3 mb-lg-0 <?= isset($header) ? "not-active" : "" ?>" placeholder="No SIPB"
                <?= isset($header) ? "value='" . $header[0]->no_sipb . "'" : "value='$sipb'" ?> readonly required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Kode Gudang</label>
            <select name="storage_location"
                id="<?= isset($header) ? "storage_location_limbah_update" : "storage_location_limbah"; ?>"
                class="select2" data-placeholder="Pilih Kode Gudang">
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
            <input type="text" name="plat_no" id="plat_no_limbah" class="form-control mb-3 mb-lg-0"
                placeholder="Plat Nomor" <?= isset($header) ? "value='" . $header[0]->plat_no . "'" : "" ?> required />
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Unit Asal</label>
            <input type="hidden" name="unit_asal" id="unit_asal_limbah" class="form-control mb-3 mb-lg-0"
                value="<?= isset($header) ? $header[0]->unit_asal : $this->session->userdata('unit_id'); ?>" required />
            <select name="unit_name" id="<?= isset($header) ? "unit_name_limbah_update" : "unit_name_limbah"; ?>"
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
            <div class="row">
                <div <?= isset($header) ? "id='select2_limbah_container_update'" : "id='select2_limbah_container'"?>>
                    <label class="required fw-semibold fs-6 mb-2">Unit Tujuan</label>
                    <select name="unit_tujuan"
                        <?= isset($header) ? "id='unit_tujuan_limbah_update'" : "id='unit_tujuan_limbah'"?>
                        class="select2" data-placeholder="Pilih Unit Tujuan">
                        <option value="">- PILIH -</option>
                        <?php foreach ($unit as $d) { ?>
                        <option value="<?= html_escape($d->id); ?>"
                            <?= isset($header) && ($header[0]->unit_tujuan == $d->id) ? "selected" : ""?>>
                            <?= html_escape($d->name); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div <?= isset($header) ? "id='manual_limbah_container_update'" : "id='manual_limbah_container'"?>
                    style="display: none;">
                    <label class="required fw-semibold fs-6 mb-2">Tujuan</label>
                    <input type="text" name="unit_tujuan_manual" id="unit_tujuan_manual_limbah"
                        class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Tujuan"
                        <?= isset($header) && ($header[0]->unit_tujuan_manual != null) ? "value='" . $header[0]->unit_tujuan_manual  . "'" : "" ?> />
                </div>
                <div class="row mt-2">
                    <div>
                        <input type="checkbox" name="manual_destination" id="manual_destination_limbah"
                            class="form-check-input"
                            <?= isset($header) && ($header[0]->unit_tujuan_manual != null) ? "checked" : "" ?>
                            onchange="setTujuanLimbah(this)" />
                        <label class="required fw-semibold fs-6 mb-2">Gunakan Tujuan Manual</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Bidang Tujuan</label>
            <select name="bidang_tujuan"
                id="<?= isset($header) ? "bidang_tujuan_limbah_update" : "bidang_tujuan_limbah"; ?>" class="select2"
                data-placeholder="Bidang Tujuan">
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
            <input type="text" name="ttd_team_leader_logistik" id="team_leader_logistik_limbah"
                class="form-control mb-3 mb-lg-0"
                value="<?= isset($header) ? strtoupper($header[0]->ttd_team_leader_logistik) : ""; ?>"
                placeholder="Team Leader Logistik" required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Pengawas Pekerjaan</label>
            <input type="text" name="ttd_pengawas_pekerjaan" id="pengawas_pekerjaan_limbah"
                class="form-control mb-3 mb-lg-0"
                value="<?= isset($header) ? strtoupper($header[0]->ttd_pengawas_pekerjaan) : ""; ?>"
                placeholder="Pengawas Pekerjaan" required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Pembawa Barang</label>
            <input type="text" name="ttd_pembawa_barang" id="pembawa_barang_limbah" class="form-control mb-3 mb-lg-0"
                value="<?= isset($header) ? strtoupper($header[0]->ttd_pembawa_barang) : ""; ?>"
                placeholder="Pembawa Barang" required />
        </div>
    </div>
    <hr />
    <div class="row mb-4">
        <h6>3. Data Material</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Nomor Surat Penghapusan Limbah</label>
            <input type="text" name="surat_penghapusan_limbah" id="surat_penghapusan_limbah_limbah"
                class="form-control mb-3 mb-lg-0"
                value="<?= isset($header) ? strtoupper($header[0]->surat_penghapusan_limbah) : ""; ?>"
                placeholder="Nomor Surat Penghapusan Limbah" required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Vendor</label>
            <input type="text" name="vendor" id="vendor_limbah" class="form-control mb-3 mb-lg-0"
                value="<?= isset($header) ? strtoupper($header[0]->vendor) : ""; ?>" placeholder="Vendor" required />
        </div>
    </div>
    <div class="row mb-4">
        <div class="table-responsive">
            <table id="table_insert_material_limbah" class="table table-striped table-bordered tblMaterial"
                style="width: 100%">
                <thead>
                    <tr style="background-color: #008B8B;">
                        <th style="text-align: center; vertical-align: middle; color: white; width: 5%;">
                            # </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 50%;">
                            Material </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 20%;">
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
                        <td style="text-align: center;"><?= $d->volume; ?></td>
                        <td style="text-align: center;"></td>
                    </tr>
                    <?php }
                    } else { ?>
                    <tr>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-sm btn-primary" onclick="insert_row_limbah()">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </td>
                        <td style="text-align: center;">
                            <select name="normalisasi[]" id="normalisasi_limbah[]" class='form-control select2' required
                                data-dropdown-parent="#createApp" data-placeholder="Pilih Material">
                                <option></option>
                                <?php foreach ($material as $d) { ?>
                                <option value='<?= html_escape($d->id); ?>'><?= html_escape($d->id); ?> -
                                    <?= html_escape($d->material); ?></option>
                                <?php } ?>
                            </select>
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
                <label class="required fw-semibold fs-6 mb-2">TUG 3</label>
                <input type="file" name="file_tug_3" id="file_tug3_limbah" class="form-control" accept=".pdf"
                    placeholder="TUG 3" required />
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="required fw-semibold fs-6 mb-2">TUG 8</label>
                <input type="file" name="file_tug_8" id="file_tug8_limbah" class="form-control" accept=".pdf"
                    placeholder="TUG 8" required />
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="required fw-semibold fs-6 mb-2">Surat Usulan Limbah</label>
                <input type="file" name="file_surat_usulan_limbah" id="file_surat_usulan_limbah" class="form-control"
                    accept=".pdf" placeholder="Surat AE 1" required />
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
function insert_row_limbah() {
    $('#table_insert_material_limbah > tbody:last-child').append(
        `<tr>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-primary" onclick="insert_row_limbah()">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </td>
            <td style="text-align: center;">
                <select name="normalisasi[]" id="normalisasi_limbah[]" class='form-control select2' required
                    data-dropdown-parent="#createApp" data-placeholder="Pilih Material">
                    <option></option>
                    <?php foreach ($material as $d) { ?>
                    <option value='<?= html_escape($d->id); ?>'><?= html_escape($d->id); ?> -
                        <?= html_escape($d->material); ?></option>
                    <?php } ?>
                </select>
            </td>
            <td style="text-align: center;">
                <input type='number' name="volume[]" class='form-control' required/>
            </td>
            <td style="text-align: center;">
                <button type='button' class='btn btn-danger btn-sm' onclick='deleteRow(this)'><i class="fa-solid fa-minus"></i></button>
            </td>
        </tr>`
    );

    $('#table_insert_material_limbah select[id="normalisasi_limbah[]"]').last().select2({
        placeholder: "Pilih Material",
        dropdownParent: $('#createApp')
    });
}

function deleteRow(loc) {
    $(loc).parent().parent().remove();
}

function setTujuanLimbah(isManual) {
    $('#unit_tujuan_limbah').val('').trigger('change');
    if (isManual.checked) {
        $('#select2_limbah_container').hide();
        $('#select2_limbah_container_update').hide();
        $('#manual_limbah_container').show();
        $('#manual_limbah_container_update').show();
        $('#unit_tujuan_manual_limbah').attr('required', true);
    } else {
        $('#select2_limbah_container').show();
        $('#select2_limbah_container_update').show();
        $('#manual_limbah_container').hide();
        $('#manual_limbah_container_update').hide();
        $('#unit_tujuan_manual_limbah').attr('required', false);
    }
}

$(document).ready(function() {
    if (<?= isset($header) ? 1 : 0; ?> == 1) {
        $('#storage_location_limbah_update').select2({
            dropdownParent: $('#createApp2')
        });
        $('#unit_name_limbah_update').select2({
            dropdownParent: $('#createApp2')
        });
        $('#unit_tujuan_limbah_update').select2({
            dropdownParent: $('#createApp2')
        });
        $('#bidang_tujuan_limbah_update').select2({
            dropdownParent: $('#createApp2')
        });
        if (<?= isset($header) && ($header[0]->unit_tujuan_manual != null) ? 1 : 0; ?> == 1) {
            $('#select2_limbah_container_update').hide();
            $('#manual_limbah_container_update').show();
            $('#unit_tujuan_manual_limbah').attr('required', true);
        } else {
            $('#select2_limbah_container_update').show();
            $('#manual_limbah_container_update').hide();
            $('#unit_tujuan_manual_limbah').attr('required', false);
        }
    }
});
</script>