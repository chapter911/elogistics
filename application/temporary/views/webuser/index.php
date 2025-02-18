<div class="card mb-6">
    <div class="card-header header-elements">
        <h3 class="mb-0 me-2">Daftar User</h3>
        <div class="card-header-elements ms-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_user">
                <span class="tf-icon ti ti-plus ti-xs me-1"></span>Add User
            </button>
        </div>
    </div>
    <div class="collapse show">
        <div class="row">
            <div class="card-datatable p-10 text-nowrap">
                <table id="table" class="table dt-fixedheader">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color:white;"> USERNAME </th>
                            <th style="text-align: center; color:white;"> NIK </th>
                            <th style="text-align: center; color:white;"> NAMA </th>
                            <th style="text-align: center; color:white;"> EMAIL </th>
                            <th style="text-align: center; color:white;"> JABATAN </th>
                            <th style="text-align: center; color:white;"> GROUP </th>
                            <th style="text-align: center; color:white;"> UNIT </th>
                            <th style="text-align: center; color:white;"> ACTIVE </th>
                            <th style="text-align: center; color:white;"> WEB ACCESS </th>
                            <th style="text-align: center; color:white;"> ANDROID ACCESS </th>
                            <th style="text-align: center; color:white;"> ACTION </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $d) { ?>
                        <tr>
                            <td> <?= html_escape(html_escape($d->username)); ?> </td>
                            <td> <?= html_escape($d->nik); ?> </td>
                            <td> <?= html_escape($d->name); ?> </td>
                            <td> <?= html_escape($d->email); ?> </td>
                            <td> <?= html_escape($d->jabatan_name); ?> </td>
                            <td> <?= html_escape($d->group_name); ?> </td>
                            <td> <?= html_escape($d->unit_name); ?> </td>
                            <td style="text-align: center;">
                                <a href="<?= base_url(); ?>C_WebUser/Activation/<?= html_escape($d->username); ?>/<?= html_escape($d->is_active) ?>"
                                    class="btn btn-sm <?= html_escape($d->is_active) == 1 ? "btn-primary" : "btn-danger" ?>">
                                    <?= html_escape($d->is_active) == 1 ? "Active" : "Not Active" ?>
                                </a>
                            </td>
                            <td style="text-align: center;"
                                onclick="setAkses('<?= html_escape($d->username); ?>', '<?= html_escape($d->is_web); ?>', 'Web')">
                                <?= html_escape($d->is_web) == 1
                                        ? "<button class='btn btn-sm btn-primary'>Active</button>"
                                        : "<button class='btn btn-sm btn-danger'>Not Active</button>"; ?>
                            </td>
                            <td style="text-align: center;"
                                onclick="setAkses('<?= html_escape($d->username); ?>', '<?= html_escape($d->is_android); ?>', 'Android')">
                                <?= html_escape($d->is_android) == 1
                                        ? "<button class='btn btn-sm btn-primary'>Active</button>"
                                        : "<button class='btn btn-sm btn-danger'>Not Active</button>"; ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <?php if($this->session->userdata("edit") == 1) { ?>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="edituser('<?= html_escape($d->username); ?>')"><i class="ti ti-pencil"></i></button>
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

<div class="modal fade" id="add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kt_modal_add_user_form" class="form" action="<?= base_url(); ?>/C_WebUser/Save" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label for="nameWithTitle" class="form-label">Site</label>
                            <select name="unit_id" id="unit_id" onchange="generateUsername()"
                                class="form-select select2" data-placeholder="Pilih Site" required>
                                <option></option>
                                <?php foreach($unit as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->name); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Username</label>
                            <input type="text" name="input_username" id="input_username" class="form-control"
                                onkeyup="removeSpace()" placeholder="Username" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Username yang digunakan</label>
                            <input type="text" name="username" id="username" class="form-control"
                                onkeyup="removeSpace()" placeholder="Username" required readonly />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Nik</label>
                            <input type="number" name="nik" id="nik" class="form-control" onkeyup="removeSpace()"
                                placeholder="Nik" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Nama</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nama" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email"
                                required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Jabatan</label>
                            <select name="jabatan_id" id="jabatan_id" onchange="generateUsername()"
                                class="form-select select2" data-placeholder="Pilih Jabatan" required>
                                <option></option>
                                <?php foreach($jabatan as $j) { ?>
                                <option value="<?= html_escape($j->id); ?>"><?= html_escape($j->jabatan_name); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Group User</label>
                            <select name="group_id" id="group_id" onchange="generateUsername()"
                                class="form-select select2" data-placeholder="Pilih Group User" required>
                                <option></option>
                                <?php foreach($group as $g) { ?>
                                <option value="<?= html_escape($g->group_id); ?>"><?= html_escape($g->group_name); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div id="is_new">
                        <div class="row">
                            <div class="col mb-4">
                                <label class="required fw-semibold fs-6 mb-2">Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Password" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-4">
                                <label class="required fw-semibold fs-6 mb-2">Konfirmasi Password</label>
                                <input type="password" name="konfirmasi" id="konfirmasi" class="form-control"
                                    placeholder="Konfirmasi Password" required />
                            </div>
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

function removeSpace() {
    $('#input_username').val($.trim($('#input_username').val().toLowerCase()));
    generateUsername();
}

function generateUsername() {
    var generated = $('#unit_id').select2('data')[0].id + "." + $('#input_username').val();
    $("#username").val(generated);
}

function edituser(username) {
    $.ajax({
        url: "<?= base_url() ?>C_WebUser/getUser",
        type: "post",
        data: {
            username: username,
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
            console.log(response);
            var response = JSON.parse(response);
            Swal.close();
            $('#unit_id').val(response[0]['unit_id']).trigger('change');
            $('#input_username').val(response[0]['username'].split('.')[1]).attr('readonly', true);
            $('#username').val(response[0]['username']).attr('readonly', true);
            $('#nik').val(response[0]['nik']);
            $('#name').val(response[0]['name']);
            $('#email').val(response[0]['email']);
            $('#jabatan_id').val(response[0]['jabatan_id']).trigger('change');
            $('#group_id').val(response[0]['group_id']).trigger('change');
            $('#is_new').remove();
            $("#add_user").modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}

function setAkses(username, akses, platform) {
    Swal.fire({
        title: akses == 0 ? "Berikan Akses " + platform + "?" : "Cabut Akses " + platform + "?",
        text: "Anda akan mengupdate akses " + platform + " untuk user " + username,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya",
        customClass: {
            cancelButton: 'btn btn-label-danger',
            confirmButton: 'btn btn-primary'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.close();
            $.ajax({
                url: "<?= base_url() ?>C_WebUser/updateAkses",
                type: "post",
                data: {
                    username: username,
                    akses: akses == 0 ? 1 : 0,
                    platform: platform,
                    <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Mohon Tunggu',
                        html: 'Mengupdate Akses',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                    Swal.showLoading();
                },
                success: function(response) {
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                    console.log(textStatus, errorThrown);
                }
            });
        }
    });
}
</script>