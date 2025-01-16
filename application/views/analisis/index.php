<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Status Material</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Analisis/exportIndex" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Basket</label>
                        <select name="basket" id="basket" class="select2" data-placeholder="Pilih Basket" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <option value="keandalan">KEANDALAN</option>
                            <option value="efesiensi">EFESIENSI</option>
                            <option value="pemasaran">PEMASARAN</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Kategori</label>
                        <select name="kategori" id="kategori" class="select2" data-placeholder="Pilih Kategori"
                            onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($kategori as $d) { ?>
                            <option value="<?= html_escape($d->kategori); ?>"><?= html_escape($d->kategori); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Status</label>
                        <select name="status" id="status" class="select2" data-placeholder="Pilih Status"
                            onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <option value="AMAN">AMAN</option>
                            <option value="SIAGA">SIAGA</option>
                            <option value="KRITIS">KRITIS</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Tampilkan</label>
                        <select name="highlight" id="highlight" class="form-select select2"
                            data-placeholder="Pilih Status" onchange="filterData()">
                            <option value="1">HIGHLIGHT MATERIAL</option>
                            <option value="0">SEMUA MATERIAL</option>
                        </select>
                    </div>
                </div>
                <div class="col-2"></div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Export</label>
                        <button type="submit" class="btn btn-primary btn-block form-control">
                            <i class="fa-solid fa-file-excel"></i> &nbsp; Export
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <br />
        <b>ROP = Reorder Poin</b>
        <br />
        <div class="row">
            <div class="card-datatable text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> NO </th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> NORMALISASI </th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> KATEGORI </th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> MATERIAL </th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> SATUAN </th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> STATUS </th>
                            <th colspan="4" style="text-align: center; vertical-align: middle; color: white;"> STOCK </th>
                            <th rowspan="2"> </th> <!-- dikosongkan untuk memberi spasi -->
                            <th colspan="2" style="text-align: center; vertical-align: middle; color: white;"> HISTORI PEMAKAIAN </th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> KEBUTUHAN <br/> MATERIAL </th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> LEADTIME <br /> (MINGGU) </th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> SAFETY <br/> STOCK </th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> ROP </th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> RASIO <br/> PEMAKAIAN </th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle; color: white;"> ACTIONS </th>
                        </tr>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; vertical-align: middle; color: white;"> UID</th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> UNIT</th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> RENCANA <br /> TIBA</th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TOTAL</th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> PER MINGGU </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> PER BULAN </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_add" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modal-rounded">
            <div class="modal-header py-7 d-flex justify-content-between">
                <h5>Buat Rencana Kontrak Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_Analisis/RencanaSave" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="required fw-semibold fs-6 mb-2">Material</label>
                            <input type="hidden" id="materialid" name="materialid" class="form-control mb-3 mb-lg-0" required readonly />
                            <input type="text" id="material" name="material" class="form-control mb-3 mb-lg-0" required readonly />
                        </div>
                        <div class="col-6">
                            <label class="required fw-semibold fs-6 mb-2">Satuan</label>
                            <input type="text" id="satuan" class="form-control mb-3 mb-lg-0" readonly/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Bulan / Tahun</label>
                            <input type="month" id="bulantahun" name="bulantahun" class="form-control mb-3 mb-lg-0" onfocus="this.showPicker()" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Material</label>
                            <div class="table-responsive">
                                <table id="table" class="table">
                                    <thead>
                                        <tr style="background-color: #008B8B;">
                                            <th width="20px" style="text-align: center; color: white;"> # </th>
                                            <th width="120px" style="text-align: center; color: white;"> BASKET </th>
                                            <th width="120px" style="text-align: center; color: white;"> MINGGU </th>
                                            <th width="120px" style="text-align: center; color: white;"> SISA ANGGARAN </th>
                                            <th width="120px" style="text-align: center; color: white;"> VOLUME KONTRAK </th>
                                            <th width="120px" style="text-align: center; color: white;"> HARGA SATUAN</th>
                                            <th width="120px" style="text-align: center; color: white;"> TOTAL + PPN 11%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="text-align: center;"><input type="checkbox" onchange="setVisible(this, 'b1')"></td>
                                            <td>SKKI B1</td>
                                            <td>
                                                <select name="minggub1" id="minggub1" class="form-select" data-placeholder="Pilih Minggu">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </td>
                                            <td><input type="number" id="sisab1" name="sisab1" class="form-control mb-3 mb-lg-0" readonly /></td>
                                            <td><input type="number" id="volumeb1" name="volumeb1" class="form-control mb-3 mb-lg-0" onkeyup="hitung('b1')" readonly /></td>
                                            <td><input type="number" id="hargab1" name="hargab1" class="form-control mb-3 mb-lg-0" onkeyup="hitung('b1')" readonly /></td>
                                            <td><input type="number" id="totalb1" name="totalb1" class="form-control mb-3 mb-lg-0" readonly /></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;"><input type="checkbox" onchange="setVisible(this, 'b2')"></td>
                                            <td>SKKI B2</td>
                                            <td>
                                                <select name="minggub2" id="minggub2" class="form-select" data-placeholder="Pilih Minggu">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </td>
                                            <td><input type="number" id="sisab2" name="sisab2" class="form-control mb-3 mb-lg-0" readonly /></td>
                                            <td><input type="number" id="volumeb2" name="volumeb2" class="form-control mb-3 mb-lg-0" onkeyup="hitung('b2')" readonly /></td>
                                            <td><input type="number" id="hargab2" name="hargab2" class="form-control mb-3 mb-lg-0" onkeyup="hitung('b2')" readonly /></td>
                                            <td><input type="number" id="totalb2" name="totalb2" class="form-control mb-3 mb-lg-0" readonly /></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;"><input type="checkbox" onchange="setVisible(this, 'b3')"></td>
                                            <td>SKKI B3</td>
                                            <td>
                                                <select name="minggub3" id="minggub3" class="form-select" data-placeholder="Pilih Minggu">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </td>
                                            <td><input type="number" id="sisab3" name="sisab3" class="form-control mb-3 mb-lg-0" readonly /></td>
                                            <td><input type="number" id="volumeb3" name="volumeb3" class="form-control mb-3 mb-lg-0" onkeyup="hitung('b3')" readonly /></td>
                                            <td><input type="number" id="hargab3" name="hargab3" class="form-control mb-3 mb-lg-0" onkeyup="hitung('b3')" readonly /></td>
                                            <td><input type="number" id="totalb3" name="totalb3" class="form-control mb-3 mb-lg-0" readonly /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var table;
$(document).ready(function() {
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "columnDefs": [{
            targets: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
            className: 'dt-body-right'
        }],
        "pageLength": 25,
        "ajax": {
            "url": "<?= base_url() ?>C_Analisis/ajaxIndex",
            "type": "post",
            "beforeSend": function() {
                Swal.fire({
                    title: 'Mohon Tunggu',
                    html: 'Mengambil Data',
                    allowOutsideClick: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                });
                Swal.showLoading();
            },
            "data": function(data) {
                data.basket    = $("#basket").val(),
                data.kategori  = $("#kategori").val(),
                data.status    = $("#status").val(),
                data.highlight = $("#highlight").val(),
                data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
            },
            "complete": function(response) {
                Swal.close();
            },
            "error": function(jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        }
    });
});

function filterData() {
    table.ajax.reload();
}

function showAddRencana(material_id) {
    $.ajax({
        url: "<?= base_url() ?>C_Analisis/dataRencana",
        type: "post",
        data: {
            material_id: material_id,
            <?=$this->security->get_csrf_token_name();?>: "<?=$this->security->get_csrf_hash();?>"
        },
        beforeSend: function() {
            Swal.fire({
                title: 'Mohon Tunggu',
                html: 'Mengambil Data',
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
            Swal.showLoading();
        },
        success: function(response) {
            Swal.close();
            var data = JSON.parse(response);

            $("#volumeb1").val("0");
            $("#sisab1").val("0");
            $("#hargab1").val("0");
            $("#totalb1").val("0");
            $("#volumeb2").val("0");
            $("#sisab2").val("0");
            $("#hargab2").val("0");
            $("#totalb2").val("0");
            $("#volumeb3").val("0");
            $("#sisab3").val("0");
            $("#hargab3").val("0");
            $("#totalb3").val("0");

            $('#materialid').val(material_id);
            $('#material').val(data[0]['material']);
            $('#satuan').val(data[0]['satuan']);
            for (let i = 0; i < data.length; i++) {
                if (data[i]['basket_id'] == 1) {
                    $("#sisab1").val(data[i]['selisih_skki']);
                    $("#hargab1").val(data[i]['harga_skki']);
                } else if (data[i]['basket_id'] == 2) {
                    $("#sisab2").val(data[i]['selisih_skki']);
                    $("#hargab2").val(data[i]['harga_skki']);
                } else if (data[i]['basket_id'] == 3) {
                    $("#sisab3").val(data[i]['selisih_skki']);
                    $("#hargab3").val(data[i]['harga_skki']);
                }
            }
            $('#modal_add').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}

function setVisible(checkbox, loc) {
    if (checkbox.checked) {
        $("#volume" + loc).val("0").prop("readonly", false);
        $("#harga" + loc).prop("readonly", false);
        $("#total" + loc).val("0");
    } else {
        $("#volume" + loc).val("0").prop("readonly", true);
        $("#harga" + loc).prop("readonly", true);
        $("#total" + loc).val("0");
    }
}

function hitung(loc) {
    if ($("#volume" + loc).val() != "" && $("#harga" + loc).val() != "") {
        var hasil = Math.round($("#volume" + loc).val() * $("#harga" + loc).val());
        $("#total" + loc).val(hasil + (hasil * 0.11));
    }
}
</script>