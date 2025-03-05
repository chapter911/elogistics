<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="<?= base_url(); ?>assets/" data-template="vertical-menu-template"
    data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>E-Logistics PLN</title>

    <meta name="description" content="" />

    <link rel="icon" type="image/x-icon" href="<?= base_url(); ?>assets/app_logo.png" />

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fonts/flag-icons.css" />

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/css/rtl/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/css/rtl/theme-default.css"
        class="template-customizer-theme-css" />

    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/demo.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/node-waves/node-waves.css" />

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <script src="<?= base_url(); ?>assets/vendor/js/helpers.js"></script>
    <script src="<?= base_url(); ?>assets/js/config.js"></script>

    <script src="<?= base_url(); ?>assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/libs/popper/popper.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/js/bootstrap.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/libs/hammer/hammer.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/libs/i18n/i18n.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/js/menu.js"></script>

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="<?= base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="<?= base_url(); ?>assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet"
        href="<?= base_url(); ?>assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <script src="<?= base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/datatables-fixedcolumns-bs5/fixedcolumns.bootstrap5.css" />

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/sweetalert2/sweetalert2.css" />
    <script src="<?= base_url(); ?>assets/vendor/libs/sweetalert2/sweetalert2.js"></script>

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/select2/select2.css" />
    <script src="<?= base_url(); ?>assets/vendor/libs/select2/select2.js"></script>

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/bs-stepper/bs-stepper.css" />
    <script src="<?= base_url(); ?>assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/libs/bs-stepper/bs-stepper.js"></script>

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/apex-charts/apex-charts.css" />
    <script src="<?= base_url(); ?>assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <script src="<?= base_url(); ?>assets/vendor/libs/chartjs/chartjs.js"></script>

    <link rel="stylesheet" href="<?= base_url(); ?>application/libraries/leaflet/leaflet.css" />
    <script src="<?= base_url(); ?>application/libraries/leaflet/leaflet.js"></script>

    <script>
    var timeoutInMilliseconds = 1800000;

    function redirectToLogin() {
        location.reload(true);
    }

    var timeoutId = setTimeout(redirectToLogin, timeoutInMilliseconds);

    document.addEventListener("mousemove", function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(redirectToLogin, timeoutInMilliseconds);
    });

    document.addEventListener("keydown", function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(redirectToLogin, timeoutInMilliseconds);
    });
    </script>

</head>

<body <?= $this->session->userdata('group_id') == 1 || $this->session->userdata('group_id') == 5 ? "" : "oncopy='return false' oncut='return false'"?>>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?= $_sidebar;?>
            <div class="layout-page">
                <?= $_navbar; ?>
                <div class="content-wrapper">
                    <div class="flex-grow-1 container-p-y container-fluid">
                        <?php if($this->session->flashdata('flash_success') != null) { ?>
                        <div class="alert alert-solid-success d-flex align-items-center" role="alert">
                            <span class="alert-icon rounded">
                                <i class="ti ti-check"></i>
                            </span>
                            <?= $this->session->flashdata('flash_success'); ?>
                        </div>
                        <?php } else if($this->session->flashdata('flash_failed') != null) { ?>
                        <div class="alert alert-solid-danger d-flex align-items-center" role="alert">
                            <span class="alert-icon rounded">
                                <i class="ti ti-ban"></i>
                            </span>
                            <?= $this->session->flashdata('flash_failed'); ?>
                        </div>
                        <?php } ?>
                        <?= $_content; ?>
                    </div>
                    <?= $_footer; ?>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
    <script src="<?= base_url(); ?>assets/js/main.js"></script>
    <script>
    $(function() {
        const select2 = $('.select2');
        if (select2.length) {
            select2.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Select value',
                    dropdownParent: $this.parent()
                });
            });
        }
    });

    $(document).on('focus', 'input[type="date"]', function() {
        this.showPicker();
    });
    </script>
</body>
</html>