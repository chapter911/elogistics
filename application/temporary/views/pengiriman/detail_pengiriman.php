<table id="table" class="table">
    <thead>
        <tr style="background-color: #008B8B">
            <th style="text-align: center; color: white;"> NO </th>
            <th style="text-align: center; color: white;"> UNIT </th>
            <th style="text-align: center; color: white;"> MATERIAL </th>
            <th style="text-align: center; color: white;"> VOLUME KIRIM </th>
            <th style="text-align: center; color: white;"> RENCANA KIRIM </th>
            <th style="text-align: center; color: white;"> TANGGAL TERIMA </th>
            <th style="text-align: center; color: white;"> SLIP PENERIMAAN </th>
            <th style="text-align: center; color: white;"> TANGGAL PERSEDIAAN </th>
            <th style="text-align: center; color: white;"> SLIP PERSEDIAAN </th>
            <th style="text-align: center; color: white;"> STATUS </th>
            <th style="text-align: center; color: white;"> SURAT JALAN </th>
            <th style="text-align: center; color: white;"> FOTO </th>
            <th style="text-align: center; color: white;"> ACTION </th>
        </tr>
    </thead>
    <tbody>
        <?php
            $no = 1;
            foreach ($data as $d) { ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= html_escape($d->name); ?></td>
            <td><?= html_escape($d->material); ?></td>
            <td><?= html_escape($d->volume); ?></td>
            <td><?= html_escape($d->rencana_kirim); ?></td>
            <td><?= html_escape($d->tanggal_penerimaan); ?></td>
            <td style="text-align: center;">
                <button class="btn btn-primary btn-sm"
                    onclick="updateSlip('<?= html_escape($d->id_pengiriman_material); ?>', '<?= html_escape($d->slip_penerimaan); ?>')">
                    <?= html_escape($d->slip_penerimaan); ?>
                </button>
            </td>
            <td><?= html_escape($d->tanggal_persediaan); ?></td>
            <td><?= html_escape($d->no_persediaan); ?></td>
            <td><?= html_escape($d->status); ?></td>
            <td style="text-align: center;">
                <a href="<?= base_url() . html_escape($d->pdf) . '.pdf'?>" target="_blank"
                    class="btn btn-text-danger btn-hover-light-danger btn-sm"><i class="fa fa-file-pdf"></i> PDF</a>
            </td>
            <td style="text-align: center;">
                <div style="display: flex; flex-direction: row;">
                    <?php if($d->foto1 == null) { ?>
                    <button class="btn btn-sm btn-secondary" style="margin-right: 5px;"><i
                            class="fa fa-image"></i></button>
                    <button class="btn btn-sm btn-secondary"><i class="fa fa-image"></i></button>

                    <?php } else {?>
                    <button class="btn btn-sm btn-danger" style="margin-right: 5px;"
                        onclick="foto('<?= html_escape($d->foto1); ?>')"><i class="fa fa-image"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="foto('<?= html_escape($d->foto2); ?>')"><i
                            class="fa fa-image"></i></button>
                    <?php } ?>
                </div>
            </td>
            <td style="text-align: center;"><button class="btn btn-outline-secondary btn-sm waves-effect waves-light"
                    onclick="update('<?= html_escape($d->id_material); ?>', '<?= html_escape($d->slip_penerimaan); ?>')"><i
                        class='fa fa-pencil'></i></button>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<div class="modal fade" id="modal_foto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">FOTO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <img id="foto" src="#" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    TUTUP
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_slip_penerimaan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">UPDATE SLIP PENERIMAAN?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_Pengiriman/UpdateSlip" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
                    value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="form-label">Slip Penerimaan</label>
                            <input type="text" name="slip_penerimaan_edit" id="slip_penerimaan_edit"
                                class="form-control" placeholder="Slip Penerimaan" required />
                            <input type="hidden" name="id_pengiriman_material" id="id_pengiriman_material"
                                class="form-control" required readonly />
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

<div class="modal fade" id="modal_update_status_karantina" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">UPDATE STATUS PENGIRIMAN INI?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_Pengiriman/UpdateStatus" method="POST"
                enctype="multipart/form-data">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
                    value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Nomor Persediaan</label>
                            <input type="text" name="no_persediaan" id="no_persediaan" class="form-control"
                                placeholder="Nomor Persediaan" required />
                            <input type="hidden" name="id_material" id="id_material" class="form-control" required
                                readonly />
                            <input type="hidden" name="slip_penerimaan" id="slip_penerimaan" class="form-control"
                                required readonly />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Tanggal Persediaan</label>
                            <input type="date" name="tanggal_persediaan" id="tanggal_persediaan" class="form-control"
                                placeholder="Tanggal Persediaan" required />
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
function updateSlip(id_pengiriman_material, slip_penerimaan) {
    $('#slip_penerimaan_edit').val(slip_penerimaan);
    $('#id_pengiriman_material').val(id_pengiriman_material);
    $("#modal_slip_penerimaan").modal('show');
}

function update(id_material, slip_penerimaan) {
    $('#id_material').val(id_material);
    $('#slip_penerimaan').val(slip_penerimaan);
    $("#modal_update_status_karantina").modal('show');
}

function foto(img) {
    $('#foto').attr('src', "<?= base_url(); ?>" + img);
    $("#modal_foto").modal('show');
}
</script>