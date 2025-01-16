<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Mutasi Material</h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url() ?>C_Mutasi/exportIndex" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Unit</label>
                        <select name="unit" id="unit" class="form-selectb select2" data-placeholder="Pilih Unit"
                            onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($unit as $u) { ?>
                            <option value="<?= html_escape($u->id); ?>"><?= html_escape($u->name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Material</label>
                        <select name="material_id" id="material_id" class="form-control select2"
                            onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($material as $m) { ?>
                            <option value="<?= html_escape($m->id); ?>"><?= html_escape($m->material); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-5"></div>
                <div class="col-1">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">&nbsp;&nbsp;</label>
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
                            <th style="text-align: center; vertical-align: middle; color: white;"> NO </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> UNIT </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> NORMALISASI </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> MATERIAL </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> SATUAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> KARANTINA </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> PERSEDIAAN AWAL </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> MUTASI MASUK </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> MUTASI KELUAR </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> PERSEDIAAN AKHIR </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TANGGAL PERGERAKAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> DURASI </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TIPE PERGERAKAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> SLIP </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> MATA UANG </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> TOTAL HARGA </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> HARGA SATUAN </th>
                            <th style="text-align: center; vertical-align: middle; color: white;"> KODE 7 </th>
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
        "searching": false,
        "processing": true,
        "serverSide": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_Mutasi/getData",
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
                data.unit = $("#unit").val(),
                data.material_id = $("#material_id").val(),
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