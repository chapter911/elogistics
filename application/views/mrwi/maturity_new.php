<div class="card">
    <!-- <div class="card-body p-lg-15">
        <div class="mb-13">
            <div class="mb-15">
                <h4 class="text-gray-800 w-bolder mb-6">KRITERIA PENILAIAN MATURITY LEVEL IMPLEMENTASI MATERIAL
                    RETURN & WAREHOUSE INVENTORY (MRWI) </h4>
            </div>
            
        </div>
    </div> -->
    <div class="card-header border-0 pt-5">
        <h4 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold mb-1">KRITERIA PENILAIAN MATURITY LEVEL IMPLEMENTASI MATERIAL RETURN & WAREHOUSE INVENTORY (MRWI)</span>
        </h4>
    </div>
    <div class="card-body pt-3">
        <div class="row">
            <div class="col">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">Triwulan</label>
                    <input type="text" id="id_hdr" value="0" hidden>
                    <input type="text" id="status_hdr" value="" hidden>
                    <select name="triwulan" id="triwulan" class="form-select" data-control="select2" data-placeholder="Pilih Triwulan" required>
                        <option></option>
                        <option value="Triwulan 1">Triwulan 1</option>
                        <option value="Triwulan 2">Triwulan 2</option>
                        <option value="Triwulan 3">Triwulan 3</option>
                        <option value="Triwulan 4">Triwulan 4</option>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select" data-control="select2" data-placeholder="Pilih Tahun" required>
                        <option></option>
                        <?php
                        for ($i = date('Y'); $i >= date('Y') - 5; $i--) {
                            if ($i == date('Y')) {
                                echo "<option value='$i' selected>$i</option>";
                            } else {
                                echo "<option value='$i'>$i</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">&nbsp;</label>
                    <button type="button" class="form-control btn btn-primary" onclick="set_header()"> <i class="fa fa-history me-2"></i> SET </button>
                </div>
            </div>

            <div class="col">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">&nbsp;</label>
                    <button type="button" class="form-control btn btn-success" onclick="simpan()"><i class="fa fa-check me-2"></i> Complete</button>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <h4 style="background-color: orange;">Status MRWI : <span id="status_hdr2"></span></h4>
            </div>
        </div>
        <hr>
        <div class="row">
            <!-- <ul class="pagination pagination-outline"> -->
            <?php foreach ($header as $h) { ?>
                <div class="col-sm-3">
                    <a class="hdr btn  btn-light waves-effect waves-light form-control " id="hdr-<?= html_escape($h->id) ?>" onclick="get_dtl('<?= html_escape($h->id) ?>')"><span class="fa-solid fa-circle fs-4 me-2" id="btn-hdr-<?= html_escape($h->id) ?> ?>"></span><?= html_escape($h->kriteria) ?></a>
                </div>
                <!-- <li class="page-item hdr" id="hdr-<?= $h->id ?>"><a class="page-link  fs-2" style="cursor: pointer;" onclick="get_dtl('<?= $h->id ?>')"><?= $h->kriteria ?></a></li> -->
            <?php } ?>
            <!-- </ul> -->
        </div>
        <hr>
        <div class="row" id="dtl_isi">

        </div>
        <div class="row" id="dtl_soal">

        </div>
        <div class="row" id="dtl_evidence">

        </div>
    </div>
</div>


<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h2 class="fw-bold" id="Judul"></h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_add_user_form" class="form" action="<?= base_url(); ?>C_MRWI/MaturitySaveDoc" method="POST">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
                        <input hidden type="text" name="id_hdrs" id="id_hdrs" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="" />
                        <input hidden type="text" name="dtl_id" id="dtl_id" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="" />
                        <input hidden type="text" name="evidence_id" id="evidence_id" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="" />

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Document allowed pdf|jpeg|jpg|xls|xlsx|png (Maks. 1MB)</label>
                            <input type="file" name="file" id="file" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama Client" />
                        </div>

                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
                            <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit" id="kt_button_1">
                                <span class="indicator-label">Submit</span>
                               </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var nomor_aktif = "0";
    var kriteria_aktif = "0";

    function get_complete_hdr() {
        $id = $("#id_hdr").val();
        $url = "<?= base_url() ?>C_MRWI/check_mrwi_hdr?id=" + $id;

        $.ajax({
            url: $url,
            type: "GET",
            data: "JSON",
            success: function(data) {
                var datas = JSON.parse(data);
                for (let i = 0; i < datas.length; i++) {
                    $h = "#btn-hdr-" + datas[i].id;
                    let btn_hdr_id = $($h);
                    btn_hdr_id.removeClass("text-primary");
                    btn_hdr_id.removeClass("text-danger");
                    // Update button text color based on the background property
                    if (datas[i].selesai) {
                        btn_hdr_id.addClass("text-primary");
                    } else {
                        btn_hdr_id.addClass("text-danger");
                    }
                }
            }
        });
    }

    function get_complete_hdr_for_approval() {
        $id = $("#id_hdr").val();
        $url = "<?= base_url() ?>C_MRWI/check_mrwi_hdr?id=" + $id;

        console.log("Masuk Sini!")

        $.ajax({
            url: $url,
            type: "GET",
            data: "JSON",
            success: function(data) {
                var datas = JSON.parse(data);
                var selesai = true;
                for (let i = 0; i < datas.length; i++) {

                    if (datas[i].selesai) {
                        selesai = true;
                        
                    } else {
                        selesai = false;
                        break;
                    }
                }
                console.log(selesai)
                if (!selesai) {
                    Swal.fire({
                        title: 'PERINGATAN !',
                        text: 'MASIH ADA DOKUMEN YANG BELUM SELESAI ! SELESAIKAN TERLEBIH DAHULU !',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });

                    return;
                }
            }
        });
    }

    function get_complete_dtl() {
        $id = $("#id_hdr").val();
        $kr = kriteria_aktif;
        $url = "<?= base_url() ?>C_MRWI/check_mrwi_dtl?id=" + $id + "&kr=" + $kr;

        $.ajax({
            url: $url,
            type: "GET",
            data: "JSON",
            success: function(data) {
                var datas = JSON.parse(data);
                for (let i = 0; i < datas.length; i++) {
                    $h = "#btn_nomor_" + datas[i].id;
                    console.log($h)
                    let btn_hdr_id = $($h);
                    btn_hdr_id.removeClass("text-primary");
                    btn_hdr_id.removeClass("text-danger");
                    // Update button text color based on the background property
                    if (datas[i].selesai) {
                        btn_hdr_id.addClass("text-primary");
                    } else {
                        btn_hdr_id.addClass("text-danger");
                    }
                }
            }
        });
    }




    function set_header() {
        $triwulan = $("#triwulan").val();
        $tahun = $("#tahun").val();
        $url = "<?= base_url() ?>C_MRWI/MaturitySetHdr?tahun=" + $tahun + "&triwulan=" + $triwulan;
        // console.log($url);
        $.ajax({
            url: $url,
            type: "GET",
            data: "JSON",
            success: function(data) {
                var datas = JSON.parse(data);
                console.log(datas);
                $("#id_hdr").val(datas.id);
                $("#status_hdr").val(datas.status_hdr);

                $("#status_hdr2").text(datas.status_hdr + " ( " + datas.remark + " )");

                $("#dtl_isi").html('');
                $("#dtl_soal").html('');
                $("#dtl_evidence").html('');
                $('.hdr').removeClass('active');
                get_complete_hdr();
            }
        });

    }

    function get_dtl($id) {
        console.log($("#id_hdr").val())

        if ($("#id_hdr").val() == 0) {
            // alert("Silahkan SET PERIODE TERLEBIH DAHULU !!! ");
            Swal.fire({
                text: "Tekan Tombol Set Terlebih Dahulu",
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "OK",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            })
            return;
        }
        kriteria_aktif = $id

        $('.hdr').removeClass('btn-success');
        $('.hdr').removeClass('btn-light');
        $('.hdr').addClass('btn-light');
        $ids = "#hdr-" + $id;
        $($ids).removeClass('btn-light').addClass('btn-success');

        $url = "<?= base_url() ?>C_MRWI/MaturityGetDtl?id=" + $id;
        $.ajax({
            url: $url,
            type: "GET",
            data: "html",
            success: function(data) {
                $("#dtl_isi").html(data);
                $("#dtl_soal").html('');
                $("#dtl_evidence").html('');
                get_complete_dtl();
            }
        });
    }


    function get_dtl_isi($id, $i) {
        $('.sub').removeClass('btn-success');
        $('.sub').removeClass('btn-light');
        $('.sub').addClass('btn-light');
        nomor_aktif = $id;
        $ids = "#dtl-" + $i;
        console.log($ids)
        $($ids).removeClass('btn-light').addClass('btn-success');

        // $('.sub').removeClass('active');
        // $ids = "#dtl-" + $i;
        // $($ids).addClass('active');

        $hdr = $("#id_hdr").val();
        $url = "<?= base_url() ?>C_MRWI/MaturityGetDtlIsi?id=" + $id + "&hdr=" + $hdr;
        // console.log($url);
        $.ajax({
            url: $url,
            type: "GET",
            data: "text",
            success: function(data) {
                $("#dtl_soal").html(data);
                $("#dtl_evidence").html("");
                get_evidence();
            }
        });
    }

    function refresh_isi() {
        $hdr = $("#id_hdr").val();
        $url = "<?= base_url() ?>C_MRWI/MaturityGetDtlIsi?id=" + nomor_aktif + "&hdr=" + $hdr;
        // console.log($url);
        $.ajax({
            url: $url,
            type: "GET",
            data: "text",
            success: function(data) {
                $("#dtl_soal").html(data);
                $("#dtl_evidence").html("");
                get_evidence();
                get_complete_hdr();
                get_complete_dtl();
            }
        });

    }

    function tetapkan() {
        $st = $("#status_hdr").val();
        $std = $("#status_dtl").val();
        if (($st == "Completed" && $std != "Rejected")) {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Status MRWI Sudah Diajukan tidak dapat melakukan perubahan Data !',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
            return;
        }

        if (($std == "Approved")) {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Status MRWI Sudah Disetujui tidak dapat melakukan perubahan Data !',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
            return;
        }

        $select = $("input[name='pilihan']:checked").val();
        $hdr = $("#id_hdr").val();
        $dtl_id = $("#dtls_id").val();
        $id = $("#id_case").val();

        console.log($dtl_id);
        var dataToSend = {
            id: $id,
            kriteria_unit: $select,
            hdr_id: $hdr,
            kriteria_manajemen: $dtl_id,
            <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
        };

        // Melakukan AJAX POST request
        $url = "<?= base_url() ?>C_MRWI/MaturitySaveDtl";
        $.ajax({
            url: $url,
            type: 'POST',
            data: dataToSend,
            success: function(response) {
                $("#dtl_evidence").html(response);
                refresh_isi();
                // Tanggapan dari server jika permintaan berhasil
                // console.log(response);


            },
            error: function(xhr, status, error) {
                // Menangani kesalahan jika permintaan gagal
                console.error(xhr.responseText);
            }
        });
    }

    function get_evidence() {
        $select = $("input[name='pilihan']:checked").val();
        $hdr = $("#id_hdr").val();
        $dtl_id = $("#dtls_id").val();
        $id = $("#id_case").val();

        if ($id == 0)
            return;

        console.log($dtl_id);
        var dataToSend = {
            id: $id,
            kriteria_unit: $select,
            hdr_id: $hdr,
            kriteria_manajemen: $dtl_id,
            <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
        };

        // Melakukan AJAX POST request
        $url = "<?= base_url() ?>C_MRWI/MaturityGetEvidence";
        $.ajax({
            url: $url,
            type: 'POST',
            data: dataToSend,
            success: function(response) {
                $("#dtl_evidence").html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

        // cek_status();''
    }



    function simpan() {
        $id = $("#id_hdr").val();

        if ($id == 0) {
            Swal.fire({
                text: "Tekan Tombol Set Terlebih Dahulu",
                icon: "warning",
                buttonsStyling: !1,
                confirmButtonText: "OK",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            })

            return
        }

        get_complete_hdr_for_approval();

        Swal.fire({
            title: 'Apakah Anda yakin untuk melakukan pengajuan , data tidak bisa di ubah jika data sudah di ajukan ?',
            text: 'Perubahan akan disimpan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ajukan Dokumen!',
            customClass: {
                cancelButton: 'btn btn-secondary',
                confirmButton: 'btn btn-danger'
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $urls = "<?= base_url() ?>C_MRWI/complete?id=" + $id;
                $.ajax({
                    url: $urls, // Ganti dengan URL skrip PHP Anda
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            // Jika update berhasil, tampilkan pesan sukses
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Perubahan telah disimpan.',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                            set_header();
                            get_complete_hdr();
                            get_complete_dtl();
                        } else {
                            // Jika ada kesalahan, tampilkan pesan error
                            Swal.fire({
                                title: 'Error!',
                                text: response.msg,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }
                    },
                    error: function() {
                        // Jika terjadi kesalahan saat melakukan permintaan AJAX
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat memproses permintaan.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                });
            }
        });
    }

    function isi_form($id, $judul, $hdr) {
        if (cek_status()) {
            document.getElementById('kt_modal_add_user_scroll').style.display = 'none';


        } else {
            $("#Judul").text($judul);
            $("#evidence_id").val($id);
            $idcase = $("#id_case").val();
            $("#dtl_id").val($idcase);
            $("#id_hdrs").val($hdr);
            $("#file").val(null);
        }

    }



    function cek_status() {
        $st = $("#status_hdr").val();
        $std = $("#status_dtl").val();
        if ($st == "Completed" && $std != "Rejected") {
            Swal.fire({
                title: 'Peringatan',
                text: 'Tidak dapat melakukan perubahan dokumen karena status MRWI sudah Diajukan !',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonText: 'OK !',
                customClass: {
                    cancelButton: 'btn btn-secondary',
                    confirmButton: 'btn btn-danger'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#kt_modal_add_user").modal('toggle');
                }
            });
        }

        if (($std == "Approved")) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Tidak dapat melakukan perubahan dokumen karena status MRWI sudah Disetujui !',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonText: 'OK !',
                customClass: {
                    cancelButton: 'btn btn-secondary',
                    confirmButton: 'btn btn-danger'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#kt_modal_add_user").modal('toggle');
                }
            });
        }


    }
</script>


<script>
    $(function() {
        var button = document.querySelector("#kt_button_1");

        $('#kt_modal_add_user_form').unbind('submit').bind('submit', function(e) { //<-- e defined here
            e.preventDefault();
            console.log('Submit event prevented');

            button.setAttribute("data-kt-indicator", "on");

            var form = $(this);
            var data = new FormData(this);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: data,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (data.status == 'success') {
                        Swal.fire({
                            text: data.msg,
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        })
                        //table.ajax.reload();
                        $('#kt_modal_add_user').modal('toggle');
                        get_evidence();
                        get_complete_hdr();
                        get_complete_dtl();
                    } else {
                        Swal.fire({
                            text: data.msg,
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        })
                    }

                    setTimeout(function() {
                        button.removeAttribute("data-kt-indicator");
                    }, 3000);
                },
                error: function(err) {
                    console.log(err);
                    Swal.fire({
                        text: err,
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    setTimeout(function() {
                        button.removeAttribute("data-kt-indicator");
                    }, 3000);
                }
            });
            return false;
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Mendapatkan bulan saat ini (1-12)
        var currentMonth = new Date().getMonth() + 1;

        // Mendapatkan triwulan berdasarkan bulan
        var currentQuarter = Math.ceil(currentMonth / 3);

        // Mengatur opsi terpilih berdasarkan triwulan saat ini
        $('#triwulan').val('Triwulan ' + currentQuarter).trigger("change");
    });
</script>