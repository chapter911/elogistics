<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Daftar Kontrak Material</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Kontrak/ExportDashboard" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Jenis Anggaran</label>
                        <select name="is_murni" id="is_murni" class="form-select select2"
                            data-placeholder="Pilih Kategori" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <option value="1">MURNI</option>
                            <option value="0">LUNCURAN</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Basket</label>
                        <select name="basket" id="basket" class="form-select select2"
                            data-placeholder="Pilih Highlight" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($basket as $d) { ?>
                                <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->basket); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Tahun</label>
                        <select name="tahun_anggaran" id="tahun_anggaran" class="form-select select2"
                            data-placeholder="Pilih Highlight" onchange="filterData()">
                            <?php for ($i = (date("Y")); $i >= 2020; $i--) { ?>
                                <option value="<?= $i; ?>" <?= date("Y") == $i ? "selected" : "" ?>><?= $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-4"></div>
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
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="card-datatable text-nowrap">
                <table id="table_month" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white;"> NO </th>
                            <th style="text-align: center; color: white;"> NORMALISASI </th>
                            <th style="text-align: center; color: white;"> KATEGORI </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> SATUAN </th>
                            <th style="text-align: center; color: white;"> TOTAL </th>
                            <th style="text-align: center; color: white; min-width:50px"> JAN </th>
                            <th style="text-align: center; color: white; min-width:50px"> FEB </th>
                            <th style="text-align: center; color: white; min-width:50px"> MAR </th>
                            <th style="text-align: center; color: white; min-width:50px"> APR </th>
                            <th style="text-align: center; color: white; min-width:50px"> MEI </th>
                            <th style="text-align: center; color: white; min-width:50px"> JUNI </th>
                            <th style="text-align: center; color: white; min-width:50px"> JULI </th>
                            <th style="text-align: center; color: white; min-width:50px"> AGTS </th>
                            <th style="text-align: center; color: white; min-width:50px"> SEPT </th>
                            <th style="text-align: center; color: white; min-width:50px"> OKT </th>
                            <th style="text-align: center; color: white; min-width:50px"> NOV </th>
                            <th style="text-align: center; color: white; min-width:50px"> DES </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    var table;
    var table_month;

    $(document).ready(function() {
        table = $("#table").DataTable({
            "scrollX": true,
            "fixedHeader": {
                "header": true,
                "headerOffset": $('.layout-navbar').height() + 15
            },
            "columnDefs": [{
                targets: [0, 1, 2, 4, 5],
                className: 'dt-body-center'
            },{
                targets: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
                className: 'dt-body-center'
            }, ],
            "pageLength": 10,
            "ajax": {
                "url": "<?= base_url() ?>C_Kontrak/ajaxDashboard",
                "type": "post",
                "beforeSend": function() {
                    Swal.fire({
                        title: 'Mohon Tunggu',
                        html: 'Memuat Data',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                        showCloseButton: false,
                    });
                    Swal.showLoading();
                },
                "data": function(data) {
                    data.is_murni       = $("#is_murni").val(),
                    data.basket         = $("#basket").val(),
                    data.tahun_anggaran = $("#tahun_anggaran").val(),
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

        table_month = $("#table_month").DataTable({
            "scrollX": true,
            "fixedHeader": {
                "header": true,
                "headerOffset": $('.layout-navbar').height() + 15
            },
            "columnDefs": [{
                targets: [0, 1, 2, 4, 5],
                className: 'dt-body-center'
            },{
                targets: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
                className: 'dt-body-right'
            },],
            "pageLength": 10,
            "ajax": {
                "url": "<?= base_url() ?>C_Kontrak/ajaxDashboardMonth",
                "type": "post",
                "beforeSend": function() {
                    Swal.fire({
                        title: 'Mohon Tunggu',
                        html: 'Memuat Data',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                        showCloseButton: false,
                    });
                    Swal.showLoading();
                },
                "data": function(data) {
                    data.is_murni       = $("#is_murni").val(),
                    data.basket         = $("#basket").val(),
                    data.tahun_anggaran = $("#tahun_anggaran").val(),
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
        table_month.ajax.reload();
    }
</script>