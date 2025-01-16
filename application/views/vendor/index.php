<div class="card mb-6">
    <div class="card-header header-elements">
        <h3 class="mb-0 me-2">Daftar Vendor</h3>
        <div class="card-header-elements ms-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_vendor" onclick="newData();">
                <span class="tf-icon ti ti-plus ti-xs me-1"></span>Add Vendor
            </button>
        </div>
    </div>
    <div class="collapse show">
        <div class="row">
            <div class="card-datatable p-10 text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color:white;"> NOMOR VENDOR </th>
                            <th style="text-align: center; color:white;"> VENDOR </th>
                            <th style="text-align: center; color:white;"> MERK </th>
                            <th style="text-align: center; color:white;"> ALAMAT </th>
                            <th style="text-align: center; color:white;"> DIREKTUR </th>
                            <th style="text-align: center; color:white;"> PIC </th>
                            <th style="text-align: center; color:white;"> JABATAN PIC </th>
                            <th style="text-align: center; color:white;"> AKTE </th>
                            <th style="text-align: center; color:white;"> BANK </th>
                            <th style="text-align: center; color:white;"> NO REKENING </th>
                            <th style="text-align: center; color:white;"> NO TELP </th>
                            <th style="text-align: center; color:white;"> DIHUBUNGI </th>
                            <th style="text-align: center; color:white;"> ACTION </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $d) { ?>
                        <tr>
                            <td> <?= html_escape($d->id); ?> </td>
                            <td> <?= html_escape($d->vendor); ?> </td>
                            <td> <?= html_escape($d->merk); ?> </td>
                            <td> <?= html_escape($d->alamat); ?> </td>
                            <td> <?= html_escape($d->direktur); ?> </td>
                            <td> <?= html_escape($d->pic); ?> </td>
                            <td> <?= html_escape($d->jabatan_pic); ?> </td>
                            <td> <?= html_escape($d->akte_pendirian); ?> </td>
                            <td> <?= html_escape($d->bank); ?> </td>
                            <td> <?= html_escape($d->nomor_rekening); ?> </td>
                            <td> <?= html_escape($d->phone); ?> </td>
                            <td> <?= html_escape($d->dihubungi); ?> </td>
                            <td style="text-align: center;">
                                <?php if($this->session->userdata('edit')) { ?>
                                <button class="btn btn-warning btn-sm" onclick="getData(<?= html_escape($d->id); ?>)">Edit</button>
                                <a href="<?= base_url(); ?>C_Vendor/Activation/<?= html_escape($d->id); ?>/<?= html_escape($d->is_active) ?>"
                                    class="btn <?php echo html_escape($d->is_active) == 1 ? 'btn-danger' : 'btn-success'?> btn-sm"><?php echo html_escape($d->is_active) == 1 ? "Non Aktifkan" : "Aktifkan" ?></a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_vendor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Add Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="add_vendor_form" class="form" action="<?= base_url(); ?>C_Vendor/Save" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Nomor Vendor</label>
                            <input type="number" name="id" id="id" class="form-control" placeholder="Nomor Vendor" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Vendor</label>
                            <input type="text" name="vendor" id="vendor" class="form-control" placeholder="Vendor" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Merk</label>
                            <input type="text" name="merk" id="merk" class="form-control" placeholder="Merk" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Direktur</label>
                            <input type="text" name="direktur" id="direktur" class="form-control" placeholder="Direktur" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">PIC</label>
                            <input type="text" name="pic" id="pic" class="form-control" placeholder="PIC" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Jabatan PIC</label>
                            <input type="text" name="jabatan_pic" id="jabatan_pic" class="form-control" placeholder="Jabatan PIC" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Akte Pendirian</label>
                            <input type="text" name="akte_pendirian" id="akte_pendirian" class="form-control" placeholder="Akte Pendirian" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Bank</label>
                            <input type="text" name="id_bank" id="id_bank" class="form-control" placeholder="Bank" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" id="nomor_rekening" class="form-control" placeholder="Nomor Rekening" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Phone</label>
                            <input type="number" name="phone" id="phone" class="form-control" placeholder="Nomor Telefon" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="fw-semibold fs-6 mb-2">Dihubungi</label>
                            <input type="date" name="dihubungi" id="dihubungi" class="form-control" placeholder="DiHubungi" />
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

function newData(){
    $("#id").prop("readonly", false);
    $('#add_vendor_form').trigger("reset");
}

function getData(id){
    $.ajax({
        url: "<?= base_url() ?>C_Vendor/getVendor/",
        type: "POST",
        data: {
            id : id,
            <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
        },
        success: function(data) {
            var datas = JSON.parse(data)[0];
            $("#id").val(datas.id).prop("readonly", true);
            $("#vendor").val(datas.vendor);
            $("#merk").val(datas.merk);
            $("#alamat").val(datas.alamat);
            $("#direktur").val(datas.direktur);
            $("#pic").val(datas.pic);
            $("#jabatan_pic").val(datas.jabatan_pic);
            $("#akte_pendirian").val(datas.akte_pendirian);
            $("#id_bank").val(datas.id_bank);
            $("#nomor_rekening").val(datas.nomor_rekening);
            $("#phone").val(datas.phone);
            $("#dihubungi").val(datas.dihubungi);
            $("#add_vendor").modal("show");
        }
    });
}
</script>