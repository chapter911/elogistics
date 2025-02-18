<form class="form" action="<?= base_url(); ?>C_Kebutuhan/exportDashboard" method="POST">
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
    <div class="row">
        <div class="col-2">
            <div class="fv-row mb-7">
                <label class="fw-semibold fs-6 mb-2">Basket</label>
                <select name="detail_kebutuhan" id="detail_kebutuhan" class="form-select select2" data-placeholder="Pilih Kebutuhan">
                    <option value="*">- SEMUA -</option>
                    <option value="keandalan">KEANDALAN</option>
                    <option value="efisiensi">EFISIENSI</option>
                    <option value="pemasaran">PEMASARAN</option>
                </select>
            </div>
        </div>
        <div class="col-2">
            <div class="fv-row mb-7">
                <label class="fw-semibold fs-6 mb-2">Filter</label>
                <select name="detail_filter" id="detail_filter" class="form-select select2" data-placeholder="Pilih Filter" onchange="filterData()">
                    <option value="*">TOTAL</option>
                    <option value="approved_volume">SUDAH DIPENUHI</option>
                    <option value="sisa">BELUM DIPENUHI</option>
                </select>
            </div>
        </div>
    </div>
</form>

<table class="table align-middle table-bordered table-hover table-striped table-row-bordered" style="white-space: nowrap;">
    <thead>
        <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B">
            <th style="text-align: center; color: white;"> NO </th>
            <th style="text-align: center; color: white;"> NORMALISASI </th>
            <th style="text-align: center; color: white;"> MATERIAL </th>
            <th style="text-align: center; color: white;"> KATEGORI </th>
            <th style="text-align: center; color: white;"> SATUAN </th>
            <th style="text-align: center; color: white;"> RASIO </th>
            <th style="text-align: center; color: white;"> TOTAL </th>
            <th style="text-align: center; color: white; min-width:50px"> UID </th>
            <th style="text-align: center; color: white; min-width:50px"> BDG </th>
            <th style="text-align: center; color: white; min-width:50px"> BLG </th>
            <th style="text-align: center; color: white; min-width:50px"> BTR </th>
            <th style="text-align: center; color: white; min-width:50px"> CKG </th>
            <th style="text-align: center; color: white; min-width:50px"> CPP </th>
            <th style="text-align: center; color: white; min-width:50px"> CPT </th>
            <th style="text-align: center; color: white; min-width:50px"> CRC </th>
            <th style="text-align: center; color: white; min-width:50px"> JTN </th>
            <th style="text-align: center; color: white; min-width:50px"> KBJ </th>
            <th style="text-align: center; color: white; min-width:50px"> KJT </th>
            <th style="text-align: center; color: white; min-width:50px"> LTA </th>
            <th style="text-align: center; color: white; min-width:50px"> MRD </th>
            <th style="text-align: center; color: white; min-width:50px"> MTG </th>
            <th style="text-align: center; color: white; min-width:50px"> PDG </th>
            <th style="text-align: center; color: white; min-width:50px"> PDK </th>
            <th style="text-align: center; color: white; min-width:50px"> TJP </th>
            <th style="text-align: center; color: white; min-width:50px"> UP2D </th>
        </tr>
    </thead>
    <tbody>
        <?php
            $i =1;
            foreach ($data as $d) { ?>
                <tr>
                    <td style="text-align: right;"><?= $i++; ?></td>
                    <td><?= html_escape($d->material_id); ?></td>
                    <td><?= html_escape($d->material); ?></td>
                    <td><?= html_escape($d->kategori); ?></td>
                    <td><?= html_escape($d->satuan); ?></td>
                    <td><?= html_escape($d->rasio); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->total); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->UID) == 0 ? "" : html_escape($d->UID); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->BDG) == 0 ? "" : html_escape($d->BDG); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->BLG) == 0 ? "" : html_escape($d->BLG); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->BTR) == 0 ? "" : html_escape($d->BTR); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->CKG) == 0 ? "" : html_escape($d->CKG); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->CPP) == 0 ? "" : html_escape($d->CPP); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->CPT) == 0 ? "" : html_escape($d->CPT); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->CRC) == 0 ? "" : html_escape($d->CRC); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->JTN) == 0 ? "" : html_escape($d->JTN); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->KBJ) == 0 ? "" : html_escape($d->KBJ); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->KJT) == 0 ? "" : html_escape($d->KJT); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->LTA) == 0 ? "" : html_escape($d->LTA); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->MRD) == 0 ? "" : html_escape($d->MRD); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->MTG) == 0 ? "" : html_escape($d->MTG); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->PDG) == 0 ? "" : html_escape($d->PDG); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->PDK) == 0 ? "" : html_escape($d->PDK); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->TJP) == 0 ? "" : html_escape($d->TJP); ?></td>
                    <td style="text-align: right;"><?= html_escape($d->UP2D) == 0 ? "" : html_escape($d->UP2D); ?></td>
                </tr>
        <?php } ?>
    </tbody>
</table>