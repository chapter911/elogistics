<div class="card mb-6">
    <div class="card-header header-elements">
        <h3 class="mb-0 me-2">Daftar Pejabat</h3>
        <div class="card-header-elements ms-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_data">
                <span class="tf-icon ti ti-plus ti-xs me-1"></span>Add
            </button>
        </div>
    </div>
    <div class="collapse show">
        <div class="row">
            <div class="card-datatable p-10 text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color:white"> NO </th>
                            <th style="text-align: center; color:white"> NAMA </th>
                            <th style="text-align: center; color:white"> JABATAN </th>
                            <th style="text-align: center; color:white"> ACTION </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($data as $d) { ?>
                        <tr>
                            <td style="text-align: center;"> <?= $i++; ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->name) ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->jabatan) ?> </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <?php if($this->session->userdata("edit") == 1) { ?>
                                    <button class="btn btn-sm btn-outline-secondary"
                                        onclick="editData('<?= html_escape($d->id); ?>', '<?= html_escape($d->name); ?>', '<?= html_escape($d->jabatan); ?>')"><i
                                            class="ti ti-pencil"></i></button>
                                    <?php }?>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Add Pejabat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kt_modal_add_user_form" class="form" action="<?= base_url(); ?>/C_Master/PejabatSave"
                method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
                    value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Nama</label>
                            <input type="hidden" name="id" id="id" class="form-control" value="0" required />
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nama" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Jabatan</label>
                            <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Jabatan"
                                required />
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
    });
});

function add_data() {
    $('#id').val('0');
    $('#name').val('');
    $('#jabatan').val('');
    $("#add_data").modal('show');
}

function editData(id, name, jabatan) {
    $('#id').val(id);
    $('#name').val(name);
    $('#jabatan').val(jabatan);
    $("#add_data").modal('show');
}
</script>