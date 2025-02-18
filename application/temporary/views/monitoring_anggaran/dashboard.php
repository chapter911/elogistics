<div class="row">
    <div class="col-2">
        <div class="fv-row mb-7">
            <label class="fw-semibold fs-6 mb-2">Tahun</label>
            <select name="tahunskki" id="tahunskki" class="select2" data-control="" data-placeholder="Pilih Tahun" onchange="getDataSKKI()">
                <?php for($i = (date("Y") + 1); $i >= 2020; $i--) { ?>
                    <option value="<?= $i; ?>" <?= date("Y") == $i ? "selected" : "" ?>><?= $i; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>

<div id="ajaxskki"></div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-5 mb-xl-8" style="justify-content: center">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Grafik Monitoring Pembayaran</span>
                </h3>
            </div>
            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-2">
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select" data-control="select2" data-placeholder="Pilih Tahun" onchange="getGrafik()">
                                <?php for($i = (date("Y") + 1); $i >= 2020; $i--) { ?>
                                    <option value="<?= $i; ?>" <?= date("Y") == $i ? "selected" : "" ?>><?= $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="ajaxGrafik"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-5 mb-xl-8" style="justify-content: center">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Grafik Kontrak Pembayaran</span>
                </h3>
            </div>
            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-2">
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Tahun</label>
                            <select name="tahun_kontrak" id="tahun_kontrak" class="form-select" data-control="select2" data-placeholder="Pilih Tahun" onchange="getGrafikKontrak()">
                                <?php for($i = (date("Y") + 1); $i >= 2020; $i--) { ?>
                                    <option value="<?= $i; ?>" <?= date("Y") == $i ? "selected" : "" ?>><?= $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="ajaxKontrak"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-5 mb-xl-8" style="justify-content: center">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Grafik Durasi Pembayaran</span>
                </h3>
            </div>
            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-2">
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Tahun</label>
                            <select name="tahun_durasi" id="tahun_durasi" class="form-select" data-control="select2" data-placeholder="Pilih Tahun" onchange="getGrafikDurasi()">
                                <?php for($i = (date("Y") + 1); $i >= 2020; $i--) { ?>
                                    <option value="<?= $i; ?>" <?= date("Y") == $i ? "selected" : "" ?>><?= $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="ajaxDurasi"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        getDataSKKI();
        getGrafik();
        getGrafikKontrak();
        getGrafikDurasi();
    });

    function getDataSKKI(){
        $.ajax({
            url: "<?= base_url() ?>C_MonitoringAnggaran/getDataSKKI",
            type: "post",
            data: {
                tahun: $('#tahunskki').val(),
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
                $("#ajaxskki").html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                console.log(textStatus, errorThrown);
            }
        });
    }

    function getGrafik(){
        $.ajax({
            url: "<?= base_url() ?>C_MonitoringAnggaran/ajaxGrafik",
            type: "post",
            data: {
                tahun: $('#tahun').val(),
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
                $("#ajaxGrafik").html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                console.log(textStatus, errorThrown);
            }
        });
    }

    function getGrafikKontrak(){
        $.ajax({
            url: "<?= base_url() ?>C_MonitoringAnggaran/ajaxKontrak",
            type: "post",
            data: {
                tahun: $('#tahun_kontrak').val(),
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
                $("#ajaxKontrak").html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                console.log(textStatus, errorThrown);
            }
        });
    }

    function getGrafikDurasi(){
        $.ajax({
            url: "<?= base_url() ?>C_MonitoringAnggaran/ajaxDurasi",
            type: "post",
            data: {
                tahun: $('#tahun_durasi').val(),
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
                $("#ajaxDurasi").html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }
</script>