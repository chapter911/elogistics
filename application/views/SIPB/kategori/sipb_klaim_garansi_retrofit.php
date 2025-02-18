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
    <span class="text-muted">KLAIM GARANSI RETROFIT</span>
</h3>
<form class="form" action="<?= base_url(); ?>C_SIPB/Save" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
        value="<?=$this->security->get_csrf_hash();?>">
    <input type="hidden" name="is_insert" value="<?= isset($header) ? 0 : 1; ?>">
    <input type="hidden" name="form_name" value="klaim_garansi_retrofit">
    <div class="row mb-4">
        <h6>1. Data SIPB</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal_klaim_garansi_retrofit"
                class="form-control form-control-solid mb-3 mb-lg-0 <?= isset($header) ? "not-active" : "" ?>"
                placeholder="Tanggal" value="<?= isset($header) ? $header[0]->tanggal : date('Y-m-d'); ?>"
                <?= isset($header) ? "readonly" : "";?> required />
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">NO SIPB</label>
            <input type="text" name="no_sipb" id="no_sipb_klaim_agaransi_retrofit"
                class="form-control form-control-solid mb-3 mb-lg-0 <?= isset($header) ? "not-active" : "" ?>"
                placeholder="No SIPB" value="<?= isset($header) ? $header[0]->no_sipb : $sipb; ?>" readonly required />
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Kode Gudang</label>
            <select name="storage_location"
                id="<?= isset($header) ? "storage_location_klaim_garansi_retrofit_update" : "storage_location_klaim_garansi_retrofit"; ?>"
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
            <input type="text" name="plat_no" id="plat_no_klaim_agaransi_retrofit"
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Plat Nomor"
                <?= isset($header) ? "value='" . $header[0]->plat_no . "'" : "" ?> required />
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Unit Asal</label>
            <input type="hidden" name="unit_asal" id="unit_asal_klaim_garansi_retrofit"
                class="form-control mb-3 mb-lg-0"
                value="<?= isset($header) ? $header[0]->unit_asal : $this->session->userdata('unit_id'); ?>" required />
            <select name="unit_name"
                id="<?= isset($header) ? "unit_name_klaim_garansi_retrofit_update" : "unit_name_klaim_garansi_retrofit"; ?>"
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
                <div
                    <?= isset($header) ? "id='select2_klaim_garansi_retrofit_container_update'" : "id='select2_klaim_garansi_retrofit_container'"?>>
                    <label class="required fw-semibold fs-6 mb-2">Unit Tujuan</label>
                    <select name="unit_tujuan"
                        <?= isset($header) ? "id='unit_tujuan_klaim_garansi_retrofit_update'" : "id='unit_tujuan_klaim_garansi_retrofit'"?>
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
                <div <?= isset($header) ? "id='manual_klaim_garansi_retrofit_container_update'" : "id='manual_klaim_garansi_retrofit_container'"?>
                    style="display: none;">
                    <label class="required fw-semibold fs-6 mb-2">Tujuan</label>
                    <input type="text" name="unit_tujuan_manual" id="unit_tujuan_manual_klaim_garansi_retrofit"
                        class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Tujuan"
                        <?= isset($header) && ($header[0]->unit_tujuan_manual != null) ? "value='" . $header[0]->unit_tujuan_manual  . "'" : "" ?> />
                </div>
                <div class="row mt-2">
                    <div>
                        <input type="checkbox" name="manual_destination" id="manual_destination_klaim_garansi_retrofit"
                            class="form-check-input"
                            <?= isset($header) && ($header[0]->unit_tujuan_manual != null) ? "checked" : "" ?>
                            onchange="setTujuanKlaimGaransiRetrofit(this)" />
                        <label class="required fw-semibold fs-6 mb-2">Gunakan Tujuan Manual</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Vendor</label>
            <input type="text" name="vendor" id="vendor_klaim_agaransi_retrofit"
                <?= isset($header) ? "value='" . $header[0]->vendor . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Vendor" required />

        </div>
    </div>
    <hr />
    <div class="row mb-4">
        <h6>2. PIC Tanda Tangan</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Team Leader Logistik</label>
            <input type="text" name="ttd_team_leader_logistik" id="team_leader_logistik_klaim_agaransi_retrofit"
                <?= isset($header) ? "value='" . $header[0]->ttd_team_leader_logistik . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Team Leader Logistik" required />

        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Pengawas Pekerjaan</label>
            <input type="text" name="ttd_pengawas_pekerjaan" id="pengawas_pekerjaan_klaim_agaransi_retrofit"
                <?= isset($header) ? "value='" . $header[0]->ttd_pengawas_pekerjaan . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Pengawas Pekerjaan" required />

        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Pembawa Barang</label>
            <input type="text" name="ttd_pembawa_barang" id="pembawa_barang_klaim_agaransi_retrofit"
                <?= isset($header) ? "value='" . $header[0]->ttd_pembawa_barang . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Pembawa Barang" required />

        </div>
    </div>
    <hr />
    <div class="row mb-4">
        <h6>3. Data Material</h6>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">No TUG 16</label>
            <input type="text" name="no_tug16" id="no_tug16_klaim_agaransi_retrofit"
                <?= isset($header) ? "value='" . $header[0]->no_tug16 . "'" : ""?>
                class="form-control form-control-solid mb-3 mb-lg-0" placeholder="No TUG 16" required />

        </div>
    </div>
    <div class="row mb-4">
        <div class="table-responsive">
            <table id="table_insert_material_klaim_garansi_retrofit"
                class="table table-striped table-bordered tblMaterial" style="width: 100%">
                <thead>
                    <tr style="background-color: #008B8B;">
                        <th style="text-align: center; vertical-align: middle; color: white; width: 5%;">
                            # </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 30%;">
                            Material </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 10%;">
                            Volume </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 15%;">
                            Merk Material </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 15%;">
                            Nomor Seri Material </th>
                        <th style="text-align: center; vertical-align: middle; color: white; width: 10%;">
                            Nama Pabrikan </th>
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
                        <td style="text-align: center;"><?= $d->merk; ?></td>
                        <td style="text-align: center;"><?= $d->no_seri; ?></td>
                        <td style="text-align: center;"><?= $d->pabrikan; ?></td>
                        <td style="text-align: center;"></td>
                    </tr>
                    <?php }
                        } else { ?>
                    <tr>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-sm btn-primary"
                                onclick="insert_row_klaim_agaransi_retrofit()">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </td>
                        <td style="text-align: center;">
                            <select name="normalisasi[]" id="normalisasi_klaim_garansi_retrofit[]"
                                class='form-control select2' required data-dropdown-parent="#createApp"
                                data-placeholder="Pilih Material">
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
                        <td style="text-align: center;">
                            <input type='text' name="merk[]" class='form-control' required />
                        </td>
                        <td style="text-align: center;">
                            <input type='text' name="no_seri[]" class='form-control' />
                        </td>
                        <td style="text-align: center;">
                            <input type='text' name="pabrikan[]" class='form-control' required />
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
                <label class="required fw-semibold fs-6 mb-2">TUG 16</label>
                <input type="file" name="file_tug_16" id="file_tug16" class="form-control" accept=".pdf"
                    placeholder="TUG 16" required />
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
function insert_row_klaim_agaransi_retrofit() {
    $('#table_insert_material_klaim_garansi_retrofit > tbody:last-child').append(
        `<tr>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-primary" onclick="insert_row_klaim_agaransi_retrofit()">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </td>
            <td style="text-align: center;">
                <select name="normalisasi[]" id="normalisasi_klaim_garansi_retrofit[]" class='form-control select2' required
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
            <td style="text-align: center;">
                <input type='text' name="merk[]" class='form-control' required />
            </td>
            <td style="text-align: center;">
                <input type='text' name="no_seri[]" class='form-control' />
            </td>
            <td style="text-align: center;">
                <input type='text' name="pabrikan[]" class='form-control' required />
            </td>
            <td style="text-align: center;">
                <button type='button' class='btn btn-danger btn-sm' onclick='deleteRow(this)'><i class="fa-solid fa-minus"></i></button>
            </td>
        </tr>`
    );

    $('#table_insert_material_klaim_garansi_retrofit select[id="normalisasi_klaim_garansi_retrofit[]"]')
        .last()
        .select2({
            placeholder: "Pilih Material",
            dropdownParent: $('#createApp')
        });
}

function deleteRow(loc) {
    $(loc).parent().parent().remove();
}

function setTujuanKlaimGaransiRetrofit(isManual) {
    $('#unit_tujuan_klaim_garansi_retrofit').val('').trigger('change');
    if (isManual.checked) {
        $('#select2_klaim_garansi_retrofit_container').hide();
        $('#select2_klaim_garansi_retrofit_container_update').hide();
        $('#manual_klaim_garansi_retrofit_container').show();
        $('#manual_klaim_garansi_retrofit_container_update').show();
        $('#unit_tujuan_manual_klaim_garansi_retrofit').attr('required', true);
    } else {
        $('#select2_klaim_garansi_retrofit_container').show();
        $('#select2_klaim_garansi_retrofit_container_update').show();
        $('#manual_klaim_garansi_retrofit_container').hide();
        $('#manual_klaim_garansi_retrofit_container_update').hide();
        $('#unit_tujuan_manual_klaim_garansi_retrofit').attr('required', false);
    }
}

$(document).ready(function() {
    if (<?= isset($header) ? 1 : 0; ?> == 1) {
        $('#storage_location_klaim_garansi_retrofit_update').select2({
            dropdownParent: $('#createApp2')
        });
        $('#unit_name_klaim_garansi_retrofit_update').select2({
            dropdownParent: $('#createApp2')
        });
        $('#unit_tujuan_klaim_garansi_retrofit_update').select2({
            dropdownParent: $('#createApp2')
        });
        $('#bidang_tujuan_klaim_garansi_retrofit_update').select2({
            dropdownParent: $('#createApp2')
        });
        if (<?= isset($header) && ($header[0]->unit_tujuan_manual != null) ? 1 : 0; ?> == 1) {
            $('#select2_klaim_garansi_retrofit_container_update').hide();
            $('#manual_klaim_garansi_retrofit_container_update').show();
            $('#unit_tujuan_manual_klaim_garansi_retrofit').attr('required', true);
        } else {
            $('#select2_klaim_garansi_retrofit_container_update').show();
            $('#manual_klaim_garansi_retrofit_container_update').hide();
            $('#unit_tujuan_manual_klaim_garansi_retrofit').attr('required', false);
        }
    }
});
</script>