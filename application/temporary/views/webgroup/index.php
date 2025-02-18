<div class="card mb-6">
    <div class="card-header header-elements">
        <h3 class="mb-0 me-2">Daftar Group Web</h3>
        <div class="card-header-elements ms-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_group">
                <span class="tf-icon ti ti-plus ti-xs me-1"></span>Add Group
            </button>
        </div>
    </div>
    <div class="collapse show">
        <div class="row">
            <div class="card-datatable p-10 text-nowrap">
                <table id="table" class="dt-fixedheader table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white;">GROUP NAME</th>
                            <th style="text-align: center; color: white;">ACTIVE</th>
                            <th style="text-align: center; color: white;">CREATED BY</th>
                            <th style="text-align: center; color: white;">CREATED DATE</th>
                            <th style="text-align: center; color: white;">AKSES</th>
                            <th style="text-align: center; color: white;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $d) { ?>
                        <tr>
                            <td> <?= html_escape($d->group_name); ?> </td>
                            <td style="text-align: center;">
                                <?= html_escape($d->is_active) == 1
                                        ? '<span class="badge bg-success"> active </span>'
                                        : '<span class="badge bg-danger"> not active </span>'; ?>
                            </td>
                            <td> <?= html_escape($d->createdby); ?> </td>
                            <td style="text-align: center;"> <?= html_escape($d->createddate); ?> </td>
                            <td style="text-align: center;">
                                <button onclick="getData(<?= html_escape($d->group_id); ?>)"
                                    class="btn btn-warning btn-sm">Edit</button>
                            </td>
                            <td style="text-align: center;">
                                <?php if(html_escape($d->group_name) == "administrator" || html_escape($d->group_name) == "super administrator") { ?>
                                <a href="<?= base_url(); ?>C_WebGroup/Activation/#" class="btn btn-default btn-sm">Non
                                    Aktifkan</a>
                                <?php } else { ?>
                                <a href="<?= base_url(); ?>C_WebGroup/Activation/<?= html_escape($d->group_id); ?>/<?= html_escape($d->is_active) ?>"
                                    class="btn <?php echo html_escape($d->is_active) == 1 ? 'btn-danger' : 'btn-success'?> btn-sm"><?php echo html_escape($d->is_active) == 1 ? "Non Aktifkan" : "Aktifkan" ?></a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_group" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Add Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kt_modal_add_user_form" class="form" action="<?= base_url(); ?>/C_WebGroup/Save" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Group Name</label>
                            <input type="text" name="group_name" id="group_name" class="form-control" placeholder="Group Name" required />
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

<div class="modal fade" id="edit_group" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Edit Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="C_WebGroup/SaveAkses" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div id="content"></div>
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

function getData(group_id){
    $.ajax({
        url: "<?= site_url() ?>C_WebGroup/Akses",
        type: "POST",
        data: {
            group_id: group_id,
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
            $("#edit_group").modal('show');
            $("#content").html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}
</script>