<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Dashboard PRK</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_MonitoringAnggaran/exportDashboardPRK" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select select2" data-placeholder="Pilih Tahun"
                            onchange="filterData()">
                            <?php for($i = date('Y'); $i >= 2022; $i--) { ?>
                            <option value="<?= $i; ?>"><?= $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-8"></div>
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
                            <th style="text-align: center; color: white;">TAHUN</th>
                            <th style="text-align: center; color: white;">STATUS</th>
                            <th style="text-align: center; color: white;">BASKET</th>
                            <th style="text-align: center; color: white;">NO PRK</th>
                            <th style="text-align: center; color: white;">URAIAN PRK</th>
                            <th style="text-align: center; color: white;">MATERIAL</th>
                            <th style="text-align: center; color: white;">JASA</th>
                            <th style="text-align: center; color: white;">TOTAL</th>
                            <th style="text-align: center; color: white;">NILAI KONTRAK</th>
                            <th style="text-align: center; color: white;">PROSENTASE</th>
                        </tr>
                    </thead>
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
        "columnDefs": [{
            targets: [5, 6, 7, 8, 9],
            className: 'dt-body-right'
        }],
        "pageLength": -1,
        "ajax": {
            "url": "<?= base_url() ?>C_MonitoringAnggaran/ajaxDashboardPrk",
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
</script>