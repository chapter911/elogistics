<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="<?= site_url(); ?>assets/app_logo.png" width="60%" height="100%" alt="Logo" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold">E-Logistics</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1 mt-4">
        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Menu" style="color: black;"><b>Menu</b></span>
        </li>
        <li class="menu-item">
            <a href="<?= base_url(); ?>C_Dashboard" class="menu-link">
                <i class="menu-icon ti ti-device-desktop"></i>
                <div data-i18n="&nbsp;Dashboard">Dashboard</div>
            </a>
            <?php foreach ($_menulv1 as $row1) { ?>
                <li class="menu-item">
                    <a href="<?= html_escape($row1->link) == '#' ? html_escape($row1->link) : base_url().html_escape($row1->link); ?>" class="<?= html_escape($row1->link) == '#' ? 'menu-link menu-toggle' : 'menu-link'; ?>">
                        <i class="menu-icon <?= html_escape($row1->icon); ?>"></i>
                        <div data-i18n="&nbsp;<?= html_escape($row1->label); ?>"><?= html_escape($row1->label); ?></div>
                    </a>
                    <?php if(html_escape($row1->link) == '#') { ?>
                        <ul class="menu-sub">
                            <?php foreach ($_menulv2 as $row2) {
                                if(html_escape($row2->header) === html_escape($row1->id)) { ?>
                                    <li class="menu-item">
                                        <a href="<?= base_url().html_escape($row2->link); ?>" class="<?= html_escape($row2->link) == '#' ? 'menu-link menu-toggle' : 'menu-link'; ?>">
                                            <div data-i18n="<?= html_escape($row2->label); ?>"><?= html_escape($row2->label); ?></div>
                                        </a>
                                        <?php if(html_escape($row1->link) == '#') { ?>
                                            <ul class="menu-sub">
                                                <?php foreach ($_menulv3 as $row3) {
                                                    if(html_escape($row3->header) === html_escape($row2->id)) { ?>
                                                        <li class="menu-item">
                                                            <a href="<?= base_url().html_escape($row3->link); ?>" class="menu-link">
                                                                <div data-i18n="<?= html_escape($row3->label); ?>"><?= html_escape($row3->label); ?></div>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                } ?>
                                            </ul>
                                        <?php } ?>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    <?php } ?>
                </li>
            <?php } ?>
        </li>
    </ul>
</aside>