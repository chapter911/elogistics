<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-wide" dir="ltr" data-theme="theme-default"
    data-assets-path="<?= base_url(); ?>assets/" data-template="front-pages" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>E-Logistics PLN</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url(); ?>assets/app_logo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fonts/tabler-icons.css" />

    <!-- Core CSS -->

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/css/rtl/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/css/rtl/theme-default.css"
        class="template-customizer-theme-css" />

    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/demo.css" />

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/css/pages/front-page.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/node-waves/node-waves.css" />

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/css/pages/front-page-pricing.css" />

    <script src="<?= base_url(); ?>assets/vendor/libs/jquery/jquery.js"></script>

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="<?= base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="<?= base_url(); ?>assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet"
        href="<?= base_url(); ?>assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <script src="<?= base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/sweetalert2/sweetalert2.css" />
    <script src="<?= base_url(); ?>assets/vendor/libs/sweetalert2/sweetalert2.js"></script>

    <style>
      table.dataTable th {
        font-size: 0.7em;
      }
      table.dataTable td {
        font-size: 0.8em;
      }
    </style>

</head>

<body>
    <script src="<?= base_url(); ?>assets/vendor/js/dropdown-hover.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/js/mega-dropdown.js"></script>

    <!-- Navbar: Start -->
    <nav class="layout-navbar shadow-none py-0">
        <div class="container" style="background-image: url(assets/media/svg/illustrations/landing.svg)">
            <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
                <!-- Menu logo wrapper: Start -->
                <div class="navbar-brand app-brand demo d-flex py-0 py-lg-2 me-4 me-xl-8">
                    <!-- Mobile menu toggle: Start-->
                    <button class="navbar-toggler border-0 px-0 me-4" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="ti ti-menu-2 ti-lg align-middle text-heading fw-medium"></i>
                    </button>
                    <!-- Mobile menu toggle: End-->
                    <a href="<?= base_url(); ?>" class="app-brand-link">
                        <img width="18" height="22" alt="Logo" src="<?= base_url(); ?>assets/app_logo.png" />
                        <span class="app-brand-text demo menu-text fw-bold ms-2 ps-1">E-Logistics PLN</span>
                    </a>
                </div>
                <!-- Menu logo wrapper: End -->
                <!-- Menu wrapper: Start -->
                <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                    <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl"
                        type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="ti ti-x ti-lg"></i>
                    </button>
                </div>
                <div class="landing-menu-overlay d-lg-none"></div>
                <!-- Menu wrapper: End -->
                <!-- Toolbar: Start -->
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <li>
                    <label class="switch switch-primary">
                        <input type="checkbox" class="switch-input" onchange="checkScroll();">
                            <span class="switch-toggle-slider">
                                <span class="switch-on">
                                <i class="ti ti-check"></i>
                                </span>
                                <span class="switch-off">
                                <i class="ti ti-x"></i>
                                </span>
                            </span>
                            <span class="switch-label">Auto Scroll</span>
                        </label>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>" class="btn btn-primary">
                            <span class="tf-icons ti ti-home scaleX-n1-rtl me-md-1">
                            </span><span class="d-none d-md-block">Home</span>
                        </a>
                    </li>
                    <!-- navbar button: End -->
                </ul>
                <!-- Toolbar: End -->
            </div>
        </div>
    </nav>
    <!-- Navbar: End -->

    <!-- Sections:Start -->

    <!-- Pricing Plans -->
    <section class="section-py">
        <div class="container-fluid">
            <h3 class="text-center">SELAMAT DATANG DIGUDANG
                <span
                    style="background: linear-gradient(to right, #12CE5D 0%, #FFD80C 100%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;">
                    <span id="kt_landing_hero_text"><?= strtoupper(html_escape($unit[0]->name)) ?></span>
                </span>
            </h3>

            <div class="row mb-xl-6">
                <div class="col-6">
                    <div class="card" style="background: rgba(191, 221, 227, 0.2);">
                        <div class="card-header header-elements">
                            <h5 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3">Stock Material</span>
                            </h5>
                            <div class="card-header-elements ms-auto">
                                <button type="button" id="is_highlight" onclick="isHighlight()" class="btn btn-xs btn-primary waves-effect waves-light">
                                    All Material
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="table_stock_material"
                                        class="table align-middle table-bordered table-hover table-striped table-row-bordered"
                                        style="white-space: nowrap; background: white">
                                        <thead>
                                            <tr style="background-color: #008B8B; ">
                                                <th style="text-align: center; color: white;"> NO </th>
                                                <th style="text-align: center; color: white;"> Material </th>
                                                <th style="text-align: center; color: white;"> Satuan </th>
                                                <th style="text-align: center; color: white;"> UID </th>
                                                <th style="text-align: center; color: white;"> UP3 </th>
                                                <th style="text-align: center; color: white;"> Total </th>
                                                <th style="text-align: center; color: white;"> Rupiah </th>
                                                <th style="text-align: center; color: white;"> Status </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #008B8B; ">
                                                <th colspan="5" style="text-align: center; color: white;"> Total </th>
                                                <th id="total_rupiah_stock_material" style="text-align: right; color: white;"> Rupiah </th>
                                                <th> </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card" style="background: rgba(191, 221, 227, 0.2);">
                        <div class="card-header border-0 pt-5">
                            <h5 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3">Kedatangan Material</span>
                            </h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="table_kedatangan_material"
                                        class="table align-middle table-bordered table-hover table-striped table-row-bordered"
                                        style="white-space: nowrap; background: white;">
                                        <thead>
                                            <tr style="background-color: #008B8B">
                                                <th style="text-align: center; color: white;"> NO </th>
                                                <th style="text-align: center; color: white;"> Material </th>
                                                <th style="text-align: center; color: white;"> Satuan </th>
                                                <th style="text-align: center; color: white;"> Volume </th>
                                                <th style="text-align: center; color: white;"> Rupiah </th>
                                                <th style="text-align: center; color: white;"> Rencana Kirim </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #008B8B; ">
                                                <th colspan="4" style="text-align: center; color: white;"> Total </th>
                                                <th id="total_rupiah_kedatangan_material" style="text-align: right; color: white;"> Rupiah </th>
                                                <th> </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card mb-5 mb-xl-6" style="background: rgba(191, 221, 227, 0.2);">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">Data ATTB</span>
                            </h3>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="table_attb"
                                        class="table align-middle table-bordered table-hover table-striped table-row-bordered"
                                        style="white-space: nowrap; background: white;">
                                        <thead>
                                            <tr style="background-color: #008B8B">
                                                <th style="text-align: center; color: white;"> NO </th>
                                                <th style="text-align: center; color: white;"> Material </th>
                                                <th style="text-align: center; color: white;"> Volume </th>
                                                <th style="text-align: center; color: white;"> Belum di Usulkan </th>
                                                <th style="text-align: center; color: white;"> AE1 </th>
                                                <th style="text-align: center; color: white;"> AE2 </th>
                                                <th style="text-align: center; color: white;"> AE3 </th>
                                                <th style="text-align: center; color: white;"> AE4 </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #008B8B">
                                                <th style="text-align: center; color: white;"> NO </th>
                                                <th style="text-align: center; color: white;"> Material </th>
                                                <th style="text-align: center; color: white;"> Volume </th>
                                                <th style="text-align: center; color: white;"> Belum di Usulkan </th>
                                                <th style="text-align: center; color: white;"> AE1 </th>
                                                <th style="text-align: center; color: white;"> AE2 </th>
                                                <th style="text-align: center; color: white;"> AE3 </th>
                                                <th style="text-align: center; color: white;"> AE4 </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card mb-5 mb-xl-6" style="background: rgba(191, 221, 227, 0.2);">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">Pengeluaran Material</span>
                            </h3>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="table_pengeluaran_material"
                                        class="table align-middle table-bordered table-hover table-striped table-row-bordered"
                                        style="white-space: nowrap; background: white;">
                                        <thead>
                                            <tr style="background-color: #008B8B">
                                                <th style="text-align: center; color: white;"> NO </th>
                                                <th style="text-align: center; color: white;"> Material </th>
                                                <th style="text-align: center; color: white;"> Satuan </th>
                                                <th style="text-align: center; color: white;"> Volume </th>
                                                <th style="text-align: center; color: white;"> Rupiah </th>
                                                <th style="text-align: center; color: white;"> Bulan </th>
                                                <th style="text-align: center; color: white;"> Minggu </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #008B8B; ">
                                                <th colspan="4" style="text-align: center; color: white;"> Total </th>
                                                <th id="total_rupiah_pengeluaran_material" style="text-align: right; color: white;"> Rupiah </th>
                                                <th colspan="2"> </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?= base_url(); ?>assets/vendor/libs/popper/popper.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/js/bootstrap.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/libs/node-waves/node-waves.js"></script>



    <script>
    var tableStock;
    var tableKedatangan;
    var tableAttb;
    var tablePengeluaran;
    var is_top = true;
    var is_highlight = true;
    var is_scroll = false;
    var scrollInterval;

    function isHighlight() {
        if (is_highlight) {
            is_highlight = false;
            $("#is_highlight").html("Highlight Material");
        } else {
            is_highlight = true;
            $("#is_highlight").html("All Material");
        }
        StockMaterial();
    }

    $(document).ready(function() {
        StockMaterial();
    });

    function StockMaterial() {
        tableStock = $("#table_stock_material").DataTable({
            "columnDefs": [{
                targets: [3, 4, 5, 6],
                className: 'dt-body-right'
            }, {
                targets: [0],
                className: 'dt-body-center'
            }],
            "destroy": true,
            "paging": false,
            "info": false,
            "scrollCollapse": true,
            "scrollX": true,
            "scrollY": '300px',
            "ordering": false,
            "searching": false,
            "ajax": {
                <?php if ($this->session->userdata('unit_id') != 5400){ ?> "url": "<?= base_url() ?>C_Dashboard/ajaxStockMaterial",
                <?php } else { ?> "url": "<?= base_url() ?>C_Dashboard/ajaxStockMaterialUID",
                <?php } ?> "type": "post",
                "beforeSend": function() {
                    Swal.fire({
                        title: 'Mohon Tunggu',
                        html: 'Mengambil Data Stock Material',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                    Swal.showLoading();
                },
                "data": function(data) {
                    data.is_highlight = is_highlight,
                    data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
                },
                "complete": function(response) {
                    Swal.close();
                    $('#total_rupiah_stock_material').html(response.responseJSON['total']);
                    KedatanganMaterial();
                },
                "error": function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                    KedatanganMaterial();
                }
            }
        });
    }

    function KedatanganMaterial() {
        tableKedatangan = $("#table_kedatangan_material").DataTable({
            "columnDefs": [{
                targets: [3, 4],
                className: 'dt-body-right'
            }, {
                targets: [0, 5],
                className: 'dt-body-center'
            }],
            "destroy": true,
            "paging": false,
            "scrollCollapse": true,
            "info": false,
            "scrollY": '300px',
            "scrollX": true,
            "ordering": false,
            "searching": false,
            "ajax": {
                "url": "<?= base_url() ?>C_Dashboard/ajaxKedatanganMaterial",
                "type": "post",
                "beforeSend": function() {
                    Swal.fire({
                        title: 'Mohon Tunggu',
                        html: 'Mengambil Data Kedatangan Material',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                    Swal.showLoading();
                },
                "data": function(data) {
                    data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
                },
                "complete": function(response) {
                    Swal.close();
                    $('#total_rupiah_kedatangan_material').html(response.responseJSON['total']);
                    Attb();
                },
                "error": function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                    Attb();
                }
            }
        });
    }

    function Attb() {
        tableAttb = $("#table_attb").DataTable({
            "columnDefs": [{
                targets: [2, 3, 4, 5, 6, 7],
                className: 'dt-body-right'
            }, {
                targets: [0],
                className: 'dt-body-center'
            }],
            "destroy": true,
            "paging": false,
            "scrollCollapse": true,
            "searching": false,
            "scrollY": '300px',
            "info": false,
            "scrollX": true,
            "ordering": false,
            "ajax": {
                "url": "<?= base_url() ?>C_Dashboard/ajaxAttb",
                "type": "post",
                "beforeSend": function() {
                    Swal.fire({
                        title: 'Mohon Tunggu',
                        html: 'Mengambil Data ATTB',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                    Swal.showLoading();
                },
                "data": function(data) {
                    data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
                },
                "complete": function(response) {
                    Swal.close();
                    PengeluaranMaterial();
                },
                "error": function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                    PengeluaranMaterial();
                }
            }
        });
    }

    function PengeluaranMaterial() {
        tablePengeluaran = $("#table_pengeluaran_material").DataTable({
            "columnDefs": [{
                targets: [3, 4],
                className: 'dt-body-right'
            }, {
                targets: [0, 5, 6],
                className: 'dt-body-center'
            }],
            "destroy": true,
            "paging": false,
            "scrollCollapse": true,
            "scrollY": '300px',
            "info": false,
            "scrollX": true,
            "ordering": false,
            "searching": false,
            "ajax": {
                "url": "<?= base_url() ?>C_Dashboard/ajaxPengeluaranMaterial",
                "type": "post",
                "beforeSend": function() {
                    Swal.fire({
                        title: 'Mohon Tunggu',
                        html: 'Mengambil Data Pengeluaran Material',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                    Swal.showLoading();
                },
                "data": function(data) {
                    data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
                },
                "complete": function(response) {
                    $('#total_rupiah_pengeluaran_material').html(response.responseJSON['total']);
                    Swal.close();
                },
                "error": function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                }
            }
        });
    }

    function checkScroll(){
        is_scroll = !is_scroll;
        if(is_scroll){
            scrollPage();
        } else {
            clearInterval(scrollInterval);
        }
    }

    function scrollPage() {
        scrollInterval = setInterval(function() {
            if (is_top) {
                $("html, body").animate({
                    scrollTop: $(document).height()
                }, 15000);
                is_top = false;
            } else {
                $("html, body").animate({
                    scrollTop: 0
                }, 1000);
                is_top = true;
            }
        }, 15000);
    }
    </script>
    <script src="<?= base_url(); ?>assets/js/main.js"></script>
</body>

</html>