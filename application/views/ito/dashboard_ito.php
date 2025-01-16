<div class="row mb-7">
    <div class="col-md-12">
        <div class="card" style="justify-content: center">
            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-3">
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Unit</label>
                            <select name="unit" id="unit" class="form-select select2" data-placeholder="Pilih Unit"
                                onchange="getDataIto()">
                                <option value="*">- ALL UNIT -</option>
                                <?php foreach($unit as $d) { ?>
                                <option value="<?= html_escape($d->kode_unit); ?>"><?= html_escape($d->kode_unit) . ' - ' . html_escape($d->name); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">Periode</label>
                            <input type="month" name="periode" id="periode" class="form-control"
                                value="<?= date('Y-m'); ?>" onchange="getDataIto()" onfocus="this.showPicker()" required />
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">Tampilan</label>
                            <select name="tampilan" id="tampilan" class="form-select select2" data-placeholder="Tampilan"
                                onchange="getDataIto()">
                                <option value="GRAFIK">GRAFIK</option>
                                <option value="TABEL">TABEL</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="fv-row">
                            <label class="fw-semibold fs-6 mb-2">Filter</label>
                            <button type="button" class="btn btn-primary btn-block form-control"
                                onclick="getDataIto()">TAMPILKAN</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ajax_realisasiito" class="row"></div>
<hr/>
<div id="ajax_saldopersedianmaterialpie" class="row"></div>
<hr/>
<div id="ajax_saldopersedianmaterialbar" class="row"></div>
<hr/>
<div id="ajax_rencanapemakaian" class="row"></div>

<script>
$(document).ready(function() {
    getDataIto();
});

function getDataIto(){
    getDataRealisasiIto();
    getDataSaldoPersedianMaterialPie();
    getDataSaldoPersedianMaterialBar();
    getDataRencanaPemakaian();
}

function getDataRealisasiIto() {
    $.ajax({
        url: "<?= base_url() ?>C_ITO/realisasi_ito",
        type: "post",
        data: {
            unit: $('#unit').val(),
            periode: $('#periode').val(),
            tampilan: $('#tampilan').val(),
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
            $("#ajax_realisasiito").html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}

function getDataSaldoPersedianMaterialPie() {
    $.ajax({
        url: "<?= base_url() ?>C_ITO/saldo_persediaan_material_pie",
        type: "post",
        data: {
            unit: $('#unit').val(),
            periode: $('#periode').val(),
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
            $("#ajax_saldopersedianmaterialpie").html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}

function getDataSaldoPersedianMaterialBar() {
    $.ajax({
        url: "<?= base_url() ?>C_ITO/saldo_persediaan_material_bar",
        type: "post",
        data: {
            unit: $('#unit').val(),
            periode: $('#periode').val(),
            tampilan: $('#tampilan').val(),
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
            $("#ajax_saldopersedianmaterialbar").html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}

function getDataRencanaPemakaian() {
    $.ajax({
        url: "<?= base_url() ?>C_ITO/ajax_rencanapemakaian",
        type: "post",
        data: {
            unit: $('#unit').val(),
            periode: $('#periode').val(),
            tampilan: $('#tampilan').val(),
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
            $("#ajax_rencanapemakaian").html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}
</script>