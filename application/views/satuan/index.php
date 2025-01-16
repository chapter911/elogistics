<div class="card mb-6">
    <div class="card-header header-elements">
        <h3 class="mb-0 me-2">Daftar Satuan</h3>
        <div class="card-header-elements ms-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_data">
                <span class="tf-icon ti ti-plus ti-xs me-1"></span>Add Kategori
            </button>
        </div>
    </div>
    <div class="collapse show">
        <div class="row">
            <div class="card-datatable p-10 text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white;">SINGKATAN</th>
                            <th style="text-align: center; color: white;">SATUAN</th>
                            <th style="text-align: center; color: white;">DIBUAT OLEH</th>
                            <th style="text-align: center; color: white;">DIBUAT TANGGAL</th>
                            <th style="text-align: center; color: white;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $d) { ?>
                        <tr>
                            <td style="text-align: center;"> <?= html_escape($d->id) ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->satuan) ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->createdby) ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->createddate) ?> </td>
                            <td style="text-align: center;">
                                <?php if($this->session->userdata('edit')) { ?>
                                <a href="<?= base_url(); ?>C_Satuan/Add/<?= html_escape($d->id); ?>"
                                    class="btn btn-warning btn-sm">EDIT</a>
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

<div class="modal fade" id="add_data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Add Satuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kt_modal_add_user_form" class="form" action="<?= base_url(); ?>/C_Satuan/Save" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">ID Singkatan</label>
                            <input type="text" name="id" maxlength="3" class="form-control" placeholder="ID Singkatan"
                                required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Satuan</label>
                            <input type="text" name="satuan" class="form-control" placeholder="Satuan" required />
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
</script>