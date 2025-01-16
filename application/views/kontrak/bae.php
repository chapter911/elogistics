<div class="card card-action mb-12">
    <div class="card-header">
        <h3 class="card-action-title mb-0">Daftar BAE Kontrak</h3>
        <div class="card-header-elements ms-auto">
            <a href="<?= base_url('C_Kontrak/ExportBAE') ?>" class="btn btn-primary">
                <span class="fa-solid fa-file-excel"></span> &nbsp; Export
            </a>
        </div>
    </div>
    <div class="collapse p-5 show">

        <div class="row">
            <div class="card-datatable text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th class="text-center" style="color: white"> NO </th>
                            <th class="text-center" style="color: white"> NO KONTRAK </th>
                            <th class="text-center" style="color: white"> VENDOR </th>
                            <th class="text-center" style="color: white"> KATEGORI MATERIAL </th>
                            <th class="text-center" style="color: white"> MATERIAL </th>
                            <th class="text-center" style="color: white"> AWAL KONTRAK </th>
                            <th class="text-center" style="color: white"> AKHIR KONTRAK </th>
                            <th class="text-center" style="color: white"> NILAI JAMINAN </th>
                            <th class="text-center" style="color: white"> NOMOR BAE </th>
                            <th class="text-center" style="color: white"> AWAL BAE </th>
                            <th class="text-center" style="color: white"> AKHIR BAE </th>
                            <th class="text-center" style="color: white"> FILE BAE </th>
                            <th class="text-center" style="color: white"> FILE JAMINAN </th>
                            <th class="text-center" style="color: white"> ACTION </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($data as $d) { ?>
                            <tr>
                                <td> <?= $i++ ?></td>
                                <td> <?= html_escape($d->no_kontrak) ?></td>
                                <td> <?= html_escape($d->vendor) ?></td>
                                <td style="text-align: center"> <?= html_escape($d->kategori) ?></td>
                                <td style="text-align: center">
                                    <button onclick="getMaterialKontrak('<?= html_escape($d->no_kontrak); ?>')" class="btn btn-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#kt_modal_material">
                                        <?= html_escape($d->material) ?>
                                    </button>
                                </td>
                                <td style="text-align: center"> <?= html_escape($d->awal_kontrak) ?></td>
                                <td style="text-align: center"> <?= html_escape($d->akhir_kontrak) ?></td>
                                <td style="text-align: right"> <?= number_format(html_escape($d->nilai_kontrak) * 0.05, 0, ".", ".") ?></td>
                                <?php if (html_escape($d->is_using_jm) == "0") { ?>
                                    <td>TANPA BANK GARANSI</td>
                                <?php } else { ?>
                                    <td <?= empty(html_escape($d->nomor_bae)) ? "style='background: #ffcccc'" : ""; ?>><?= html_escape($d->nomor_bae) ?></td>
                                <?php } ?>
                                <td <?= empty(html_escape($d->bae_awal)) ? "style='background: #ffcccc'" : ""; ?>><?= html_escape($d->bae_awal) ?></td>
                                <td <?= empty(html_escape($d->bae_akhir)) ? "style='background: #ffcccc'" : ""; ?>><?= html_escape($d->bae_akhir) ?></td>
                                <td <?= empty(html_escape($d->file_bae)) ? "style='background: #ffcccc'" : ""; ?>>
                                    <?php if (!empty(html_escape($d->file_bae))) { ?>
                                        <a href="<?= base_url() . 'data_uploads/kontrak/bae/' . html_escape($d->file_bae)  ?>" class="btn btn-text-danger btn-hover-light-danger btn-sm" target="_blank"> <i class="fa fa-file-pdf"></i> PDF
                                        <?php } ?>
                                </td>
                                <td <?= empty(html_escape($d->file_jm)) ? "style='background: #ffcccc'" : ""; ?>>
                                    <?php if (!empty(html_escape($d->file_jm))) { ?>
                                        <a href="<?= base_url() . html_escape($d->file_jm_location) . '/' . html_escape($d->file_jm)  ?>" class="btn btn-text-danger btn-hover-light-danger btn-sm" target="_blank"> <i class="fa fa-file-pdf"></i> PDF
                                        <?php } ?>
                                </td>
                                <td class="text-center">
                                    <?php if (html_escape($d->is_using_jm) != "0") { ?>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="get_data(<?= html_escape($d->id); ?>)" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">
                                            <i class="fa fa-pencil"></i>
                                        </button>
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

<div class="modal fade" id="kt_modal_material" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen p-9" role="document">
        <div class="modal-content">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h2>List Detail Material</h2>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <div class="table-responsive" id="materialKontrak"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="kt_modal_create_campaign" tabindex="-1">
    <div class="modal-dialog modal-xl p-9">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h4>UPDATE DATA BAE <span id="sno_kontrak"></span></h4>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body scroll-y m-5">
                <form action="<?= base_url() ?>C_Kontrak/SaveBAE" id="kt_modal_add_user_form" method="POST" name="form-wizard" class="form-control-with-bg  w-lg-900px mx-auto" enctype="multipart/form-data">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    <div class="mb-5">
                        <div class="fv-row mb-10">
                            <label class="form-label">Nomor BAE</label>
                            <input type="hidden" class="form-control" name="id" id="id" placeholder="" value="" autocomplete="off" />
                            <input type="text" class="form-control" name="nomor_bae" id="nomor_bae" placeholder="" autocomplete="off" />
                        </div>
                        <div class="fv-row mb-10">
                            <label class="form-label">Tanggal Awal BAE</label>
                            <input type="date" name="bae_awal" id="bae_awal" class="form-control datepicker" data-date-format="Y-m-d" placeholder="Tanggal Awal" autocomplete="off" required>
                        </div>
                        <div class="fv-row mb-10">
                            <label class="form-label">Tanggal Akhir BAE</label>
                            <input type="date" id="bae_akhir" name="bae_akhir" class="form-control datepicker" data-date-format="Y-m-d" placeholder="Tanggal Akhir" autocomplete="off" required>
                        </div>
                        <div class="fv-row mb-10">
                            <label class="form-label">
                                <span class="required">File Jaminan Pelaksanaan (PDF)</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Pastikan Yang anda upload file PDF"></i>
                            </label>
                            <input type="file" class="form-control m-b-5" name="filejm" accept=".pdf"> </span>
                        </div>
                        <div class="fv-row mb-10">
                            <label class="form-label">
                                <span class="required">File BAE (PDF)</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Pastikan Yang anda upload file PDF"></i>
                            </label>
                            <input type="file" class="form-control m-b-5" name="filebae" id="filebae" accept=".pdf"> </span>
                        </div>
                        <div class="fv-row mb-10">
                            <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit" id="kt_button_1">
                                <span class="indicator-label">SIMPAN</span>
                                <div id="simpan-loading" hidden>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>

                                </div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
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

    function getMaterialKontrak($id) {
        $url = "<?= base_url() ?>C_Kontrak/getMaterialKontrak?id=" + $id;
        $.ajax({
            url: $url,
            type: "GET",
            data: "text",
            success: function(d) {
                $("#materialKontrak").html(d);
            }
        });
    }

    function get_data(id) {
        $url = "<?= base_url() ?>C_Kontrak/GetDataBAE?id=" + id;
        $.ajax({
            url: $url,
            type: "GET",
            data: "JSON",
            success: function(data) {
                var datas = JSON.parse(data);
                datas = datas[0];
                console.log(datas);
                $("#id").val(datas.id);
                $("#nomor_bae").val(datas.nomor_bae);
                $("#bae_awal").val(datas.bae_awal);
                $("#bae_akhir").val(datas.bae_akhir);
                $("#sno_kontrak").html(" - NO KONTRAK : " + datas.no_kontrak);
                // setBae();
            }
        });
    }

    function downloadFile() {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '<?= base_url('C_Kontrak/ExportBAE') ?>';
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
</script>