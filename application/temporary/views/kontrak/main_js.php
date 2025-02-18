<script>
    $(document).ready(function() {
        table = $("#table").DataTable({});

        $('input[name="is_using_jm"]').change(function() {
            if ($(this).is(':checked')) {
                var selectedValue = $(this).val();
                if(selectedValue == "1"){
                    $('#nilai_jaminan').val($('#nilai_kontrak').val() * 0.05);
                } else {
                    $('#nilai_jaminan').val(0);
                }
                console.log('Selected value:', selectedValue);
            }
        });
    });

    function getMaterialKontrak($id) {
        $url = "<?= base_url() ?>C_Kontrak/getMaterialKontrak?id=" + $id;
        $.ajax({
            url: $url,
            type: "GET",
            data: "text",
            success: function(d) {
                $("#materialKontrak").html(d);
            }
        });
    }

    function getDetailNilai($id) {
        $url = "<?= base_url() ?>C_Kontrak/getDetailNilai?id=" + $id;
        $.ajax({
            url: $url,
            type: "GET",
            data: "text",
            success: function(d) {
                $("#nilaiKontrak").html(d);
            }
        });
    }

    function delete_data($id, $produk) {
        $url = "<?= base_url() ?>C_Kontrak/delete_data?id=" + $id;
        Swal.fire({
            title: 'Apakah Anda yakin menghapus Data Kontrak ' + $produk + ' ?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger',
            },
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $url,
                    type: "GET",
                    data: "JSON",
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Mohon Tunggu',
                            html: 'Menghapus Data...',
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                        Swal.showLoading();
                    },
                    success: function(data) {
                        var datas = JSON.parse(data);
                        Swal.fire({
                            text: datas.msg,
                            icon: datas.status,
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        location.reload();
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

                    }
                });
            }
        });
    }

    function hitungDurasi() {
        var awal_kontrak = new Date($('#awal_kontrak').val());
        var durasi = $('#durasi').val() - 1;

        if ($('#awal_kontrak').val() != '' && !isNaN(durasi)) {
            var akhir_kontrak = new Date(awal_kontrak.getTime() + durasi * 24 * 60 * 60 * 1000);

            var tahun = akhir_kontrak.getFullYear();
            var bulan = ("0" + (akhir_kontrak.getMonth() + 1)).slice(-2);
            var hari = ("0" + akhir_kontrak.getDate()).slice(-2);
            var tanggalHasil = tahun + "-" + bulan + "-" + hari;

            $('#akhir_kontrak').val(tanggalHasil);
        }
    }

    function kalkulasi(input) {
        var row = $(input).closest('tr');
        var volume = parseFloat(row.find('input[name="volume[]"]').val()) || 0;
        var harga = parseFloat(row.find('input[name="harga[]"]').val()) || 0;
        var ongkos = parseFloat(row.find('input[name="ongkos[]"]').val()) || 0;
        var total = (volume * (harga + ongkos)) * 1.11;
        row.find('input[name="total[]"]').val(Math.floor(total));

        hitungNilaiKontrak();
    }

    function hitungNilaiKontrak() {
        var totalInputs = document.querySelectorAll('input[name="total[]"]');
        var totalSum = 0;

        for (var i = 0; i < totalInputs.length; i++) {
            var totalValue = parseFloat(totalInputs[i].value.replace(/,/g, '')) || 0;
            totalSum += totalValue;
        }

        $('#nilai_kontrak').val(Math.floor(totalSum));
    }

    function checkDuplicate(selectElement) {
        var selectedValue = selectElement.value;
        var allSelectElements = document.querySelectorAll('.material');
        var duplicateFound = false;

        allSelectElements.forEach(function(element) {
            if (element !== selectElement && element.value === selectedValue) {
                duplicateFound = true;
            }
        });

        if (duplicateFound) {
            alert('Material ini sudah dipilih di baris lain.');
            selectElement.selectedIndex = -1;
        }
    }

    function duplicateRow() {
        var row = $('#clone').clone();
        row.find('.material').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').removeAttr('aria-hidden').removeAttr('tabindex');
        row.find('.select2-selection').remove();
        $('.tblMaterial tbody:last-child').append(row);
        $('.material').select2({ placeholder: "Pilih Material" });
        hitungNilaiKontrak();
    }

    function deleteRow(loc) {
        $(loc).parent().parent().remove();
        hitungNilaiKontrak();
    }

    function editdata($id) {
        $url = "<?= base_url() ?>C_Kontrak/get_data_edit";
        $.ajax({
            url: $url,
            type: "POST",
            data: {
                id : $id,
                <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Mohon Tunggu',
                    html: 'Mengambil Data...',
                    allowOutsideClick: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                });
                Swal.showLoading();
            },
            success: function(data) {
                var datas = JSON.parse(data);
                $("#id_edit").val(datas[0].id);
                $("#tahun_anggaran_edit").val(datas[0].tahun_anggaran);
                $("#nomor_kontrak_edit").val(datas[0].no_kontrak);
                $("#nomor_po_edit").val(datas[0].no_po);

                $("#id_basket_edit").val(datas[0].id_basket);
                $("#id_basket_edit").trigger("change");
                $("#no_prk_edit").val(datas[0].id_prk);
                $("#no_prk_edit").trigger("change");
                $("#no_khs_edit").val(datas[0].no_khs);
                $("#no_khs_edit").trigger("change");
                $("#is_murni_edit").val(datas[0].is_murni);
                $("#is_murni_edit").trigger("change");
                $("#id_skki_edit").val(datas[0].id_skki);
                $("#id_skki_edit").trigger("change");

                if(datas[0].is_using_jm == "1"){
                    $('input[name="is_using_jm_edit"][value="1"]').prop('checked', true);
                } else {
                    $('input[name="is_using_jm_edit"][value="0"]').prop('checked', true);
                }
                Swal.close();
            },
            error: function(err) {
                Swal.fire({
                    text: err,
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });

            }
        });
    }
</script>