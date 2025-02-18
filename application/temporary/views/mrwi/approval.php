<div class="card">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-5 mb-1">APPROVAL KRITERIA PENILAIAN MATURITY LEVEL IMPLEMENTASI MATERIAL RETURN & WAREHOUSE INVENTORY (MRWI)</span>
        </h3>
    </div>
    <div class="card-body pt-3">
        <div class="row">
            <div class="col">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">Triwulan</label>
                    <input type="text" id="id_hdr" value="0" hidden>
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
                    <button type="button" class="form-control btn btn-primary" onclick="caridata()"> <i class="fa fa-history me-2"></i> SET </button>
                </div>
            </div>


        </div>
        <hr>
        <div class="table-responsive" id="tampil_sini">

        </div>
    </div>
</div>


<!-- ================ MODAL ================= -->
<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h2 class="fw-bold" id="Judul">LIST DATA MRWI UNIT</h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_add_user_form" class="form" action="<?= base_url(); ?>C_MRWI/approval_save" method="POST">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="10px">
                        <input hidden type="text" name="id_hdrs" id="id_hdrs" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="" />
                        <input hidden type="text" name="iscomplete" id="iscomplete" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="" />


                        <div class="table-responsive" id="tampilkan_isi">

                        </div>
                        <!-- <div class="row">
                            <div class="col-md-3">
                                <select name="pilihan" id="pilihan" class="form-select fs-1">
                                    <option value="Approved">Approve</option>
                                    <option value="Ditolak">Reject</option>
                                </select>
                            </div>
                            <br>
                            <div class="col-md-9">
                                <textarea name="alasan" id="alasan" class="form-control" rows="2"></textarea>
                            </div>
                        </div> -->


                        <div class="text-center pt-10" id="area_submit">
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
    $(document).ready(function() {
        // Mendapatkan bulan saat ini (1-12)
        var currentMonth = new Date().getMonth() + 1;

        // Mendapatkan triwulan berdasarkan bulan
        var currentQuarter = Math.ceil(currentMonth / 3);

        // Mengatur opsi terpilih berdasarkan triwulan saat ini
        $('#triwulan').val('Triwulan ' + currentQuarter).trigger("change");
    });
</script>

<script>
    function caridata() {
        $triwulan = $("#triwulan").val();
        $tahun = $("#tahun").val();
        $url = "<?= base_url() ?>C_MRWI/CariDataMRWI?tahun=" + $tahun + "&triwulan=" + $triwulan;
        $.ajax({
            url: $url,
            type: "GET",
            data: "JSON",
            success: function(data) {
                $("#tampil_sini").html(data);
            }
        });
    }

    function view(id, name, isc) {
        $url = "<?= base_url() ?>C_MRWI/Tampil_detailmrwi?id=" + id;
        $.ajax({
            url: $url,
            type: "GET",
            data: "JSON",
            success: function(data) {
                $("#tampilkan_isi").html(data);
                $("#Judul").text("LIST DATA MRWI UNIT " + name);
                $("#id_hdrs").val(id)
                $("#iscomplete").val(isc)
            }
        });
    }
</script>

<script>
    $(function() {
        var button = document.querySelector("#kt_button_1");


        $('#kt_modal_add_user_form').unbind('submit').bind('submit', function(e) { //<-- e defined here

            e.preventDefault();
            console.log('Submit event prevented');

            if ($("#iscomplete").val() == 0) {
                Swal.fire({
                    text: "MRWI BELUM BISA DI APPROVE, KARENA UNIT MASIH BELUM SUBMIT MRWI INI !",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                })
                return;
            }

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
                        caridata();
                        $('#kt_modal_add_user').modal('toggle');
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