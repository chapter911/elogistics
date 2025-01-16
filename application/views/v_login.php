<!doctype html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="<?= site_url(); ?>assets/" data-template="vertical-menu-template-no-customizer"
    data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>E-Logistics PLN</title>

    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="<?= site_url(); ?>assets/app_logo.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="<?= site_url(); ?>assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?= site_url(); ?>assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="<?= site_url(); ?>assets/vendor/fonts/flag-icons.css" />
    <link rel="stylesheet" href="<?= site_url(); ?>assets/vendor/css/rtl/core.css" />
    <link rel="stylesheet" href="<?= site_url(); ?>assets/vendor/css/rtl/theme-default.css" />
    <link rel="stylesheet" href="<?= site_url(); ?>assets/css/demo.css" />
    <link rel="stylesheet" href="<?= site_url(); ?>assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="<?= site_url(); ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="<?= site_url(); ?>assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="<?= site_url(); ?>assets/vendor/libs/@form-validation/form-validation.css" />
    <link rel="stylesheet" href="<?= site_url(); ?>assets/vendor/css/pages/page-auth.css" />

    <script src="<?= site_url(); ?>assets/vendor/js/helpers.js"></script>
    <script src="<?= site_url(); ?>assets/js/config.js"></script>

    <script src="<?= base_url(); ?>assets/vendor/libs/jquery/jquery.js"></script>

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="<?= base_url(); ?>assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="<?= base_url(); ?>assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet"
        href="<?= base_url(); ?>assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />

    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/libs/sweetalert2/sweetalert2.css" />

    <script src="https://www.google.com/recaptcha/api.js?render=6LdbpGAqAAAAADUsJ59-iwY0-p_Vlw9sICxJtHrz"></script>
    <script>
    grecaptcha.ready(function() {
        grecaptcha.execute('6LdbpGAqAAAAADUsJ59-iwY0-p_Vlw9sICxJtHrz', {
            action: 'submit'
        }).then(function(token) {
            document.getElementById("token").value = token;
        });
    });
    </script>
</head>

<body oncopy="return false" oncut="return false">
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            <div class="d-none d-lg-flex col-lg-8 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center"
                    style="background-color: #008B8B;">
                    <img src="<?= site_url(); ?>assets/e-logistics.png" alt="auth-login-cover"
                        class="my-5 auth-illustration" width="30%" />
                </div>
            </div>
            <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
                <div class="w-px-400 mx-auto mt-12 pt-5">
                    <img src="<?= site_url(); ?>assets/tes2.png" height="80" alt="Logo"
                        style="display: block; margin: 0 auto;" />
                    <form id="formAuthentication" class="mb-6 mt-10" action="<?= site_url()?>C_Login/auth" method="POST"
                        autocomplete="off" role="presentation" autocomplete="new-text">
                        <div class="mb-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="hidden" class="form-control" placeholder="Token" name="token" id="token"
                                required="required">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>"
                                value="<?=$this->security->get_csrf_hash();?>">
                            <input type="text" class="form-control" id="username" name="username" autocomplete="off"
                                role="presentation" autocomplete="new-text" placeholder="Masukkan username Anda"
                                autofocus required />
                        </div>
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" autocomplete="new-password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary d-grid w-100">Masuk</button>
                        <hr />
                        <div style="text-align: center; margin-top: 30px;">© 2023 <b>PLN UID Jakarta Raya</b></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= site_url(); ?>assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?= site_url(); ?>assets/vendor/libs/popper/popper.js"></script>
    <script src="<?= site_url(); ?>assets/vendor/js/bootstrap.js"></script>
    <script src="<?= site_url(); ?>assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="<?= site_url(); ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?= site_url(); ?>assets/vendor/libs/hammer/hammer.js"></script>
    <script src="<?= site_url(); ?>assets/vendor/libs/i18n/i18n.js"></script>
    <script src="<?= site_url(); ?>assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="<?= site_url(); ?>assets/vendor/js/menu.js"></script>
    <script src="<?= site_url(); ?>assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="<?= site_url(); ?>assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="<?= site_url(); ?>assets/vendor/libs/@form-validation/auto-focus.js"></script>
    <script src="<?= site_url(); ?>assets/js/main.js"></script>

    <script src="<?= base_url(); ?>assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
</body>

</html>