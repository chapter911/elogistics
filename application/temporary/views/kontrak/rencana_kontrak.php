<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Daftar Rencana Kontrak</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Kontrak/ExportRencanaKontrak" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Awal Bulan / Tahun</label>
                        <input type="month" name="start_month" id="start_month" class="form-control mb-3 mb-lg-0" onfocus="this.showPicker()" value="<?= date('Y-01'); ?>" onchange="filterData()" required />
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Akhir Bulan / Tahun</label>
                        <input type="month" name="end_month" id="end_month" class="form-control mb-3 mb-lg-0" onfocus="this.showPicker()" value="<?= date('Y-m'); ?>" onchange="filterData()" required />
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Basket</label>
                        <select name="basket_filter" id="basket_filter" class="form-select select2"
                            data-placeholder="Pilih Basket" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($basket as $b) { ?>
                                <option value='<?= html_escape($b->id); ?>'><?= html_escape($b->basket); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-3"></div>
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
                            <th style="text-align: center; color: white;"> BASKET </th>
                            <th style="text-align: center; color: white;"> BULAN / TAHUN </th>
                            <th style="text-align: center; color: white;"> MINGGU </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> VOLUME </th>
                            <th style="text-align: center; color: white;"> HARGA </th>
                            <th style="text-align: center; color: white;"> TOTAL </th>
                            <th style="text-align: center; color: white;"> STATUS </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr class="fw-semibold fs-6 text-gray-800" style="background-color: #008B8B;">
                            <th style="text-align: center; color: white;" colspan="7"> TOTAL </th>
                            <th style="text-align: right; color: white;" id="total"> 0 </th>
                            <th style="text-align: center; color: white;"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
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
            "columnDefs": [
                { targets: [5, 6, 7], className: 'dt-body-right' },
                { targets: [2, 3, 8], className: 'dt-body-center' }
            ],
            "pageLength": 10,
            "language": {
                "decimal": ",",
            },
            "ajax": {
                "url": "<?= base_url() ?>C_Kontrak/AjaxRencanaKontrak",
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
                    data.start_month = $("#start_month").val(),
                    data.end_month = $("#end_month").val(),
                    data.basket_filter = $("#basket_filter").val(),
                    data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
                },
                "complete": function(response) {
                    var total = response.responseJSON.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    $("#total").text(total);
                    Swal.close();
                },
                "error": function(jqXHR, textStatus, errorThrown) {
                    Swal.close();
                }
            }
        });
    });

    function filterData(){
        table.ajax.reload();
    }

    function deleteRow(loc) {
        $(loc).parent().parent().remove();
    }

    function updateRencanaKontrak(id){
        Swal.fire({
            title: "Selesaikan Rencana ini?",
            showDenyButton: true,
            confirmButtonText: "Selesaikan",
            denyButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url() ?>C_Kontrak/updateSelesai",
                    type: "post",
                    data: {
                        id: id,
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
                        filterData();
                        Swal.close();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        filterData();
                        Swal.close();
                    }
                });
            } else if (result.isDenied) {
                Swal.fire("Batal", "", "info");
            }
        });
    }
</script>