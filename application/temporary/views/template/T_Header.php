<div id="kt_app_header" class="app-header">
    <div class="app-container container-fluid d-flex align-items-stretch flex-stack ribbon ribbon-top ribbon-vertical" id="kt_app_header_container">
        <div class="d-flex align-items-center d-block d-lg-none ms-n3" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px me-2" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-outline ki-abstract-14 fs-2"></i>
            </div>
            <a href="../../demo38/dist/index.html">
                <img alt="Logo" src="assets/media/logos/demo38-small.svg" class="h-30px" />
            </a>
        </div>
        <div class="app-navbar flex-lg-grow-1" id="kt_app_header_navbar">
            <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1">
            </div>
            <?php if(str_contains(base_url(), 'trial')){ ?>
                <div class="ribbon ribbon-top ribbon-vertical">
                    <div class="ribbon-label bg-danger">
                        DEVELOPER TRIAL MODE
                    </div>
                </div>
            <?php } ?>
            <div class="app-navbar-item ms-1 ms-md-3">
                <div class="btn btn-icon btn-custom btn-color-gray-600 btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px position-relative" id="kt_drawer_chat_toggle">
                    <i class="ki-outline ki-notification-on fs-1"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-danger w-15px h-15px ms-n4 mt-3">5</span>
                </div>
            </div>
            <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
                <div class="cursor-pointer symbol symbol-circle symbol-35px symbol-md-45px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <img src="assets/media/avatars/300-1.jpg" alt="user" />
                </div>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="assets/media/avatars/300-1.jpg" />
                            </div>
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5"><?= $this->session->userdata('username') ?></div>
                                <div class="fw-semibold text-muted text-hover-primary fs-7"><?= $this->session->userdata('group_name') ?></div>
                                <div class="fw-semibold text-muted text-hover-primary fs-7"><?= $this->session->userdata('jabatan_name') ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="separator my-2"></div>
                    <?php if($this->session->userdata('group_id') == 1){
                        if(str_contains(base_url(), "trial")){ ?>
                            <div class="menu-item px-5 my-1">
                                <a href="https://e-logisticspln.com" class="menu-link px-5">PROD MODE</a>
                            </div>
                    <?php } else { ?>
                        <div class="menu-item px-5 my-1">
                            <a href="https://trial.e-logisticspln.com" class="menu-link px-5">TRIAL MODE</a>
                        </div>
                    <?php }
                    } ?>
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title position-relative">Mode
                            <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                            </span></span>
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-night-day fs-2"></i>
                                    </span>
                                    <span class="menu-title">Light</span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-moon fs-2"></i>
                                    </span>
                                    <span class="menu-title">Dark</span>
                                </a>
                            </div>
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                    <span class="menu-icon" data-kt-element="icon">
                                        <i class="ki-outline ki-screen fs-2"></i>
                                    </span>
                                    <span class="menu-title">System</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item px-5 my-1">
                        <a href="#" class="menu-link px-5" data-bs-toggle="modal" data-bs-target="#kt_modal_password">Password</a>
                    </div>
                    <div class="menu-item px-5">
                        <a href="<?= base_url(); ?>C_Login/logout" class="menu-link px-5">Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-navbar-separator separator d-none d-lg-flex"></div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="kt_modal_password">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Ganti Password</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body">
                <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
                <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Password Lama</label>
                        <input type="password" name="oldpassword" id="oldpassword" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Password Lama" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Password Baru</label>
                        <input type="password" name="newpassword" id="newpassword" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Password Baru" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Konfirmasi Password</label>
                        <input type="password" name="confirmation" id="confirmation" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Konfirmasi Password" required />
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">BATAL</button>
                <button type="button" class="btn btn-primary" onclick="changePassword()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
    <div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
        <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
            <div class="card-title">
                <div class="d-flex justify-content-center flex-column me-3">
                    <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">Notifikasi</a>
                </div>
            </div>
            <div class="card-toolbar">
                <div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_chat_close">
                    <i class="ki-outline ki-cross-square fs-2"></i>
                </div>
            </div>
        </div>
        <div class="card-body" id="kt_drawer_chat_messenger_body">
            <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer" data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
                <div class="d-flex justify-content-start mb-10">
                    <div class="d-flex flex-column align-items-start">
                        <div class="d-flex align-items-center mb-2">
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="assets/media/avatars/300-25.jpg" />
                            </div>
                            <div class="ms-3">
                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Brian Cox</a>
                                <span class="text-muted fs-7 mb-1">2 mins</span>
                            </div>
                        </div>
                        <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">How likely are you to recommend our company to your friends and family ?</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function changePassword(){
        $.ajax({
            url: "<?= base_url(); ?>C_WebUser/ChangePassword",
            type: "post",
            data: {
                oldpassword : $('#oldpassword').val(),
                newpassword : $('#newpassword').val(),
                confirmation: $('#confirmation').val(),
                <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Mohon Tunggu',
                    html: 'Mengganti Password',
                    allowOutsideClick: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                });
                Swal.showLoading();
            },
            success: function(response) {
                Swal.close();
                $('#oldpassword').val("");
                $('#newpassword').val("");
                $('#confirmation').val("");
                var json = $.parseJSON(response);
                if(json['status'] != 'failed'){
                    $("#kt_modal_password").modal('hide');
                }
                Swal.fire({
                    icon: json['status'] == 'failed' ? 'error' : 'success' ,
                    title: json['status'] == 'failed' ? 'Gagal' : 'Sukses',
                    text: json['message']
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                console.log(textStatus, errorThrown);
            }
        });
    }

</script>