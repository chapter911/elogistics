<div class="card">
    <div class="card-body p-lg-15">
        <div class="mb-13">
            <div class="mb-15">
                <h4 class="fs-2x text-gray-800 w-bolder mb-6">KRITERIA PENILAIAN MATURITY LEVEL IMPLEMENTASI MATERIAL
                    RETURN & WAREHOUSE INVENTORY (MRWI) </h4>
            </div>
            <?php foreach($header as $h) { ?>
                <div class="row mb-12">
                    <div class="col-md-12 pe-md-10 mb-10 mb-md-0">
                        <h2 class="text-gray-800 fw-bold mb-4"><?= html_escape($h->kriteria); ?></h2>
                        <?php foreach($detail as $d) {
                            if($d->hdr_id == $h->id) { ?>
                            <div class="m-0">
                                <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0"
                                    data-bs-toggle="collapse" data-bs-target="#<?= html_escape($d->nomor); ?>">
                                    <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                                        <i class="ki-outline ki-minus-square toggle-on text-primary fs-1"></i>
                                        <i class="ki-outline ki-plus-square toggle-off fs-1"></i>
                                    </div>
                                    <h4 class="text-gray-700 fw-bold cursor-pointer mb-0"><?= html_escape($d->kriteria); ?></h4>
                                </div>
                                <div id="<?= html_escape($d->nomor); ?>" class="collapse fs-6 ms-1">
                                    <div class="mb-4 text-gray-600 fw-semibold fs-6 ps-10"><?= html_escape($d->indikasi); ?></div>
                                    <div class="mb-4 text-gray-600 fw-semibold fs-6 ps-10">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" name="<?= html_escape($d->nomor); ?>" id="<?= html_escape($d->nomor); ?>lv1" />
                                            <label class="form-check-label" for="<?= html_escape($d->nomor); ?>lv1">
                                                <?= html_escape($d->lv1); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-4 text-gray-600 fw-semibold fs-6 ps-10">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" name="<?= html_escape($d->nomor); ?>" id="<?= html_escape($d->nomor); ?>lv2" />
                                            <label class="form-check-label" for="<?= html_escape($d->nomor); ?>lv2">
                                                <?= html_escape($d->lv2); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-4 text-gray-600 fw-semibold fs-6 ps-10">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" name="<?= html_escape($d->nomor); ?>" id="<?= html_escape($d->nomor); ?>lv3" />
                                            <label class="form-check-label" for="<?= html_escape($d->nomor); ?>lv3">
                                                <?= html_escape($d->lv3); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-4 text-gray-600 fw-semibold fs-6 ps-10">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" name="<?= html_escape($d->nomor); ?>" id="<?= html_escape($d->nomor); ?>lv4" />
                                            <label class="form-check-label" for="<?= html_escape($d->nomor); ?>lv4">
                                                <?= html_escape($d->lv4); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-4 text-gray-600 fw-semibold fs-6 ps-10">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" name="<?= html_escape($d->nomor); ?>" id="<?= html_escape($d->nomor); ?>lv5" />
                                            <label class="form-check-label" for="<?= html_escape($d->nomor); ?>lv5">
                                                <?= html_escape($d->lv5); ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed"></div>
                            </div>
                        <?php }
                        } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>