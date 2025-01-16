<div class="row">
    <form action="<?= base_url() ?>C_MonitoringAnggaran/SaveDocument" method="POST" class="form" enctype="multipart/form-data">
        <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
        <div class="table-responsive">
            <table id="table" class="table" style="white-space: nowrap;">
                <thead>
                    <tr style="background-color: #008B8B">
                        <th style="text-align: center; color: white;"> REVISI </th>
                        <th style="text-align: center; color: white;"> BASKET </th>
                        <th style="text-align: center; color: white;"> NO SKKI </th>
                        <th style="text-align: center; color: white;"> JENIS </th>
                        <th style="text-align: center; color: white;"> DOKUMEN </th>
                        <th style="text-align: center; color: white;"> ACTION </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $d) { ?>
                        <tr>
                            <td style="text-align: center;"><?= html_escape($d->revisi); ?></td>
                            <td style="text-align: center;"><?= html_escape($d->revisi); ?></td>
                            <td style="text-align: center;"><?= html_escape($d->revisi); ?></td>
                            <td style="text-align: center;"><?= html_escape($d->revisi); ?></td>
                            <td style="text-align: center;">
                                <a href="<?= site_url() . html_escape($d->folder_path) . html_escape($d->file_name) ?>" target="_blank" class="btn btn-text-danger btn-hover-light-danger btn-sm"/>
                                    <i class="fa fa-file-pdf"></i> PDF
                                </a>
                            </td>
                            <td></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td style="text-align: center;">
                            <?= count($data) + 1; ?>
                            <input type="hidden" name="revisi" value="<?= count($data) + 1; ?>"/>
                            <input type="hidden" name="id_skki" value="<?= $id_skki; ?>"/>
                        </td>
                        <td colspan="4">
                            <input type="file" name="file_dokumen" id="file_dokumen" accept=".pdf" class="form-control" placeholder="File Dokumen"/>
                        </td>
                        <td style="text-align: center;">
                            <button type="submit" class="btn btn-success btn-sm">SIMPAN</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>