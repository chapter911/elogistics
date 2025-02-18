<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Daftar BASTB</h5>
    </div>
    <div class="collapse p-5 show">
        <div class="row">
            <div class="col-2">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">Nomor Kontrak</label>
                    <input type="text" name="no_kontrak" id="no_kontrak" class="form-control mb-3 mb-lg-0"/>
                </div>
            </div>
            <div class="col-2">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">Nomor KHS</label>
                    <input type="text" name="nomor_khs" id="nomor_khs" class="form-control mb-3 mb-lg-0"/>
                </div>
            </div>
            <div class="col-2">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">Vendor</label>
                    <select name="id_vendor" id="id_vendor" class="form-control select2" onchange="filterData()">
                        <option value="*">- SEMUA -</option>
                        <?php foreach ($vendor as $v) { ?>
                        <option value="<?= html_escape($v->id); ?>"><?= html_escape($v->vendor); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-1">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">&nbsp;</label>
                    <button type="button" class="btn btn-primary btn-success form-control waves-effect waves-light"
                        onclick="filterData()">
                        <i class="fa-solid fa-search"></i> &nbsp; Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card-datatable text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color:white"> # </th>
                            <th style="text-align: center; color:white"> NOMOR KONTRAK </th>
                            <th style="text-align: center; color:white"> NOMOR KHS </th>
                            <th style="text-align: center; color:white"> VENDOR </th>
                            <th style="text-align: center; color:white"> NO BASTB </th>
                            <th style="text-align: center; color:white"> TANGGAL </th>
                            <th style="text-align: center; color:white"> MANAGER PLN </th>
                            <th style="text-align: center; color:white"> DIREKTUR VENDOR </th>
                            <th style="text-align: center; color:white"> ACTION </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_bastb" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Update BASTB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>/C_Kontrak/UpdateBastb" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">NO BASTB</label>
                            <input type="hidden" name="no_kontrak_update" id="no_kontrak_update" class="form-control" value="" />
                            <input type="text" name="no_bastb" id="no_bastb" onkeyup="this.value = this.value.toUpperCase()"
                                class="form-control" placeholder="NO BASTB" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" placeholder="Tanggal" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">TTD Oleh (PLN)</label>
                            <select name="nama_manager" id="nama_manager" class="form-control select2" required>
                                <option value="">- PILIH -</option>
                                <?php foreach ($pejabat_pln as $p) { ?>
                                <option value="<?= html_escape($p->name) . ' - ' . html_escape($p->jabatan); ?>">
                                    <?= html_escape($p->name) . ' - ' . html_escape($p->jabatan); ?>
                                </option>
                                <?php } ?>
                            </select>
                            * update master petugas pln tidak ditemukan
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">Direktur Vendor</label>
                            <select name="vendor_direktur" id="vendor_direktur" class="form-control select2" required>
                                <option value="">- PILIH -</option>
                                <?php foreach ($vendor as $v) { ?>
                                <option value="<?= html_escape($v->direktur); ?>"><?= html_escape($v->direktur); ?></option>
                                <?php } ?>
                            </select>
                            * update master vendor jika direktur tidak ditemukan
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">Jabatan Vendor</label>
                            <input type="text" name="vendor_jabatan" id="vendor_jabatan" class="form-control" placeholder="Jabatan Vendor" value="Direktur Utama" required/>
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

<script>
var table;

$(document).ready(function() {
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 10,
        "columnDefs": [
            {
                targets: [8],
                className: 'text-center'
            },
        ],
        "ajax": {
            "url": "<?= base_url() ?>C_Kontrak/bastbData",
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
                data.no_kontrak = $("#no_kontrak").val(),
                data.nomor_khs  = $("#nomor_khs").val(),
                data.id_vendor  = $("#id_vendor").val(),
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

function cekKontrak(no_kontrak){
    $.ajax({
        url: "<?= base_url() . 'C_Kontrak/cekKontrakBASTB'; ?>",
        type: "POST",
        data: {
            no_kontrak: no_kontrak,
            <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
        },
        beforeSend: function() {
            Swal.fire({
                title: 'Mohon Tunggu',
                html: 'Mengambil Data...',
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
            Swal.showLoading();
        },
        success: function(response) {
            var data = JSON.parse(response);
            $('#no_kontrak_update').val(no_kontrak);
            if(data.length > 0){
                $('#no_bastb').val(data[0]['no_bastb']);
                $('#tanggal').val(data[0]['tanggal']);
                $('#nama_manager').val(data[0]['nama_manager'] + " - " + data[0]['jabatan_manager']).trigger('change');
                $('#vendor_direktur').val(data[0]['vendor_direktur']).trigger('change');
                $('#vendor_jabatan').val(data[0]['vendor_jabatan']);
            }
            Swal.close();
            $('#update_bastb').modal('show')
        },
        error: function() {
            Swal.close();
            Swal.fire({
                text: "Maaf Terdapat Error",
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });

        }
    });
}

function printKontrak(no_kontrak) {
    var my_form = document.createElement('FORM');
    my_form.name='myForm';
    my_form.method='POST';
    my_form.action = "<?= base_url() . 'C_Kontrak/printBASTB'; ?>";

    var my_tb = document.createElement('INPUT');
    my_tb.type='HIDDEN';
    my_tb.name='no_kontrak';
    my_tb.value=no_kontrak;
    my_form.appendChild(my_tb);

    my_tb=document.createElement('INPUT');
    my_tb.type='HIDDEN';
    my_tb.name='<?=$this->security->get_csrf_token_name();?>';
    my_tb.value='<?= $this->security->get_csrf_hash(); ?>';
    my_form.appendChild(my_tb);
    document.body.appendChild(my_form);
    my_form.submit();
}
</script>