<div class="card mb-6">
    <div class="card-header header-elements">
        <h3 class="mb-0 me-2">Daftar KHS</h3>
        <div class="card-header-elements ms-auto">
            <button type="button" class="btn btn-primary" onclick="addMaterial()" data-bs-toggle="modal" data-bs-target="#add_data">
                <span class="tf-icon ti ti-plus ti-xs me-1"></span>Add KHS
            </button>
        </div>
    </div>
    <div class="collapse show">
        <div class="row">
            <div class="card-datatable p-10 text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white;"> NO </th>
                            <th style="text-align: center; color: white;"> JUDUL </th>
                            <th style="text-align: center; color: white;"> PENYEDIA </th>
                            <th style="text-align: center; color: white;"> NO KONTRAK KHS </th>
                            <th style="text-align: center; color: white;"> NO AMANDEMEN </th>
                            <th style="text-align: center; color: white;"> TAHUN KONTRAK </th>
                            <th style="text-align: center; color: white;"> HARGA KONTRAK </th>
                            <th style="text-align: center; color: white;"> TANGGAL AWAL KONTRAK </th>
                            <th style="text-align: center; color: white;"> TANGGAL AKHIR KONTRAK </th>
                            <th style="text-align: center; color: white;"> FILE </th>
                            <th style="text-align: center; color: white;"> ACTION </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $d) { ?>
                        <tr>
                            <td> <?= html_escape($d->id); ?> </td>
                            <td> <?= html_escape($d->judul); ?> </td>
                            <td> <?= html_escape($d->vendor); ?> </td>
                            <td> <?= html_escape($d->nomor_khs); ?> </td>
                            <td> <?= html_escape($d->nomor_amandemen); ?> </td>
                            <td style="text-align: center"> <?= html_escape($d->tahun_kontrak); ?> </td>
                            <td style="text-align: right"> <?= number_format(html_escape($d->harga_kontrak), 0, ",", "."); ?> </td>
                            <td style="text-align: center"> <?= html_escape($d->tanggal_awal_kontrak); ?> </td>
                            <td style="text-align: center"> <?= html_escape($d->tanggal_akhir_kontrak); ?> </td>
                            <td style="text-align: center">
                                <a href="<?= base_url() . html_escape($d->file_location) . '/' . html_escape($d->file_name)  ?>" target="_blank"
                                    class="btn btn-text-danger btn-hover-light-danger btn-sm waves-effect waves-light">
                                    <i class="fa fa-file-pdf"></i> PDF
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <?php if($this->session->userdata("edit") == 1) { ?>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="editKHS('<?= html_escape($d->id); ?>', '<?= html_escape($d->nomor_khs); ?>')"><i class="ti ti-pencil"></i></button>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Add KHS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kt_modal_add_user_form" class="form" action="<?= base_url(); ?>/C_KHS/Save" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">Judul</label>
                            <input type="hidden" name="is_edit" id="is_edit" value="0" />
                            <input type="hidden" name="id" id="id" value="0" />
                            <input type="text" name="judul" id="judul" onkeyup="this.value = this.value.toUpperCase()"
                                class="form-control" placeholder="Judul"  />
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">Penyedia / Vendor</label>
                            <select name="id_vendor" id="id_vendor" class="form-select select2" data-dropdown-parent='#add_data' data-placeholder="Penyedia / Vendor" >
                                <option></option>
                                <?php foreach($vendor as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->vendor); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">Nomor KHS</label>
                            <input type="text" name="nomor_khs" id="nomor_khs" onkeyup="removeSpace('nomor_khs')"
                                class="form-control" placeholder="Nomor KHS"  />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="fw-semibold fs-6 mb-2">Nomor Amanademen</label>
                            <input type="text" name="nomor_amandemen" id="nomor_amandemen"
                                onkeyup="removeSpace('nomor_amandemen')"
                                class="form-control" placeholder="Nomor Amandemen" />
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">Tahun Kontrak</label>
                            <input type="number" name="tahun_kontrak" id="tahun_kontrak" class="form-control" placeholder="Tahun Kontrak"/>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">Harga Kontrak</label>
                            <input type="number" name="harga_kontrak" id="harga_kontrak" class="form-control" placeholder="Harga Kontrak"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">Tanggal Awal Kontrak</label>
                            <input type="date" name="tanggal_awal_kontrak" id="tanggal_awal_kontrak"
                                class="form-control" placeholder="Tanggal Awal Kontrak"
                                />
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class=" fw-semibold fs-6 mb-2">Tanggal Akhir Kontrak</label>
                            <input type="date" name="tanggal_akhir_kontrak" id="tanggal_akhir_kontrak"
                                class="form-control" placeholder="Tanggal Akhir Kontrak"
                                />
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="fw-semibold fs-6 mb-2">File KHS</label>
                            <input type="file" name="filepdf" id="filepdf" accept=".pdf"
                                class="form-control" placeholder="File KHS" />
                        </div>
                    </div>
                    <div class="row">
                        <table id="table-insert-material" class="table table-striped table-bordered">
                            <thead>
                                <tr style="background-color: #008B8B;">
                                    <th
                                        style="text-align: center; vertical-align: middle; color: white; width: 5%;">
                                        # </th>
                                    <th
                                        style="text-align: center; vertical-align: middle; color: white; width: 50%;">
                                        MATERIAL </th>
                                    <th
                                        style="text-align: center; vertical-align: middle; color: white; width: 40%;">
                                        ALOKASI </th>
                                    <th
                                        style="text-align: center; vertical-align: middle; color: white; width: 5%;">
                                        ACTIONS </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
var tabledetail;

$(document).ready(function() {
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 10,
    });
});

function removeSpace(input) {
    if (input == 'nomor_khs') {
        $('#nomor_khs').val($.trim($('#nomor_khs').val().toUpperCase()));
    } else {
        $('#nomor_amandemen').val($.trim($('#nomor_amandemen').val().toUpperCase()));
    }
}

function editKHS(id, nomor_khs) {
    $.ajax({
        url: "<?= base_url() ?>C_KHS/getKHS",
        type: "post",
        data: {
            id: id,
            nomor_khs: nomor_khs,
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
            $('#is_edit').val("1");
            $('#id').val(data[0]['id']);
            $('#id').prop('readonly', true);
            $('#judul').val(data[0]['judul']);
            $("#id_vendor").select2().val(data[0]['id_vendor']).trigger("change");
            $('#nomor_khs').val(data[0]['nomor_khs']);
            $('#nomor_amandemen').val(data[0]['nomor_amandemen']);
            $('#tahun_kontrak').val(data[0]['tahun_kontrak']);
            $('#harga_kontrak').val(data[0]['harga_kontrak']);
            $('#tanggal_awal_kontrak').val(data[0]['tanggal_awal_kontrak']);
            $('#tanggal_akhir_kontrak').val(data[0]['tanggal_akhir_kontrak']);
            $('#table-insert-material > tbody').html('');
            if(data['detail'].length > 0){
                for(var i =0; i < data['detail'].length; i++){
                    addMaterial();
                    $('#table-insert-material > tbody:last-child > tr:last-child').find('select[name="material[]"]').select2().val(data['detail'][i]['material_id']).trigger("change");
                    $('#table-insert-material > tbody:last-child > tr:last-child').find('input[name="alokasi[]"]').val(data['detail'][i]['alokasi']);
                }
            } else {
                addMaterial();
            }
            $("#add_data").modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}

function addMaterial(){
    $('#table-insert-material > tbody:last-child').append(
        `<tr>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-primary" onclick="addMaterial()">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </td>
            <td style="text-align: center;">
                <select name='material[]' id='material[]' class='form-control select2' required>
                    <option></option>
                    <?php foreach ($material as $d) { ?>
                        <option value='<?= html_escape($d->id); ?>'><?= html_escape($d->id); ?> - <?= html_escape($d->material); ?></option>
                    <?php } ?>
                </select>
            </td>
            <td style="text-align: center;">
                <input type='number' name='alokasi[]' id='alokasi[]' class='form-control' required/>
            </td>
            <td style="text-align: center;">
                <button type='button' class='btn btn-danger btn-sm' onclick='deleteRow(this)'><i class="fa-solid fa-trash"></i></button>
            </td>
        </tr>`
    );

    $('#table-insert-material select[name="material[]"]').last().select2({
        placeholder: "Pilih Material",
        dropdownParent: $('#add_data')
    });
}

function deleteRow(loc) {
    $(loc).parent().parent().remove();
}
</script>