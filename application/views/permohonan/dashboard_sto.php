<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Dashboard STO</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Permohonan/exportDashboardSTO" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Basket</label>
                        <select name="basket" id="basket" class="form-select select2" data-placeholder="Pilih Basket"
                            onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach($basket as $d) { ?>
                            <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->basket); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Jenis</label>
                        <select name="jenis_anggaran" id="jenis_anggaran" class="form-select select2"
                            data-placeholder="Jenis Anggaran" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <option value="MURNI">MURNI</option>
                            <option value="LANJUTAN">LUNCURAN</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select select2" data-placeholder="Pilih Highlight"
                            onchange="filterData()">
                            <?php for($i = (date("Y")); $i >= 2020; $i--) { ?>
                            <option value="<?= $i; ?>" <?= date("Y") == $i ? "selected" : "" ?>><?= $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                </div>
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
        <div class="row">
            <div class="card-datatable text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white;"> NO </th>
                            <th style="text-align: center; color: white;"> NORMALISASI </th>
                            <th style="text-align: center; color: white;"> KATEGORI </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> SATUAN </th>
                            <th style="text-align: center; color: white;"> TOTAL </th>
                            <th style="text-align: center; color: white; min-width:50px"> UID </th>
                            <th style="text-align: center; color: white; min-width:50px"> BDG </th>
                            <th style="text-align: center; color: white; min-width:50px"> BLG </th>
                            <th style="text-align: center; color: white; min-width:50px"> BTR </th>
                            <th style="text-align: center; color: white; min-width:50px"> CKG </th>
                            <th style="text-align: center; color: white; min-width:50px"> CPP </th>
                            <th style="text-align: center; color: white; min-width:50px"> CPT </th>
                            <th style="text-align: center; color: white; min-width:50px"> CRC </th>
                            <th style="text-align: center; color: white; min-width:50px"> JTN </th>
                            <th style="text-align: center; color: white; min-width:50px"> KBJ </th>
                            <th style="text-align: center; color: white; min-width:50px"> KJT </th>
                            <th style="text-align: center; color: white; min-width:50px"> LTA </th>
                            <th style="text-align: center; color: white; min-width:50px"> MRD </th>
                            <th style="text-align: center; color: white; min-width:50px"> MTG </th>
                            <th style="text-align: center; color: white; min-width:50px"> PDG </th>
                            <th style="text-align: center; color: white; min-width:50px"> PDK </th>
                            <th style="text-align: center; color: white; min-width:50px"> TJP </th>
                            <th style="text-align: center; color: white; min-width:50px"> UP2D </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div id="ajaxContainer"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
var table;
$(document).ready(function() {
    $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn';
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "columnDefs": [{
                targets: [0, 1, 2, 4],
                className: 'dt-body-center'
            },
            {
                targets: [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
                className: 'dt-body-right'
            },
        ],
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_Permohonan/ajaxDashboardSTO",
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
                data.basket = $("#basket").val(),
                data.jenis_anggaran = $("#jenis_anggaran").val(),
                data.tahun = $("#tahun").val(),
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

function showDetail(unit, normalisasi) {
    $.ajax({
        url: "<?= base_url() ?>C_Permohonan/getDetailDashboardSTO",
        type: "post",
        data: {
            unit: unit,
            normalisasi: normalisasi,
            basket: $("#basket").val(),
            jenis_anggaran: $("#jenis_anggaran").val(),
            tahun: $("#tahun").val(),
            <?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash();?>"
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
            $("#ajaxContainer").html(response);
            $('#modal_detail').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.close();
            console.log(textStatus, errorThrown);
        }
    });
}
</script>