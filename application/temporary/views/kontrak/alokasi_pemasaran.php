<div class="card card-action mb-12">
    <div class="card-header">
        <h3 class="card-action-title mb-0">Data Penyerapan KHS</h3>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url() ?>C_Kontrak/ExportPabrikan" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">No KHS</label>
                        <input type="text" name="nomor_khs" id="nomor_khs" class="form-control" placeholder="No KHS">
                    </div>
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Kategori</label>
                        <select name="kategori" id="kategori" class="form-selectb select2"
                            data-placeholder="Pilih kategori" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($kategori as $k) { ?>
                            <option value="<?= html_escape($k->kategori); ?>"><?= html_escape($k->kategori); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
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
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Vendor</label>
                        <select name="id_vendor" id="id_vendor" class="form-control select2" onchange="filterData()">
                            <option value="*">- SEMUA -</option>
                            <?php foreach ($vendor as $v) { ?>
                            <option value="<?= html_escape($v->id); ?>"><?= html_escape($v->vendor); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-1">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">&nbsp;</label>
                        <button type="button" class="btn btn-primary btn-success form-control waves-effect waves-light"
                            onclick="filterData()">
                            <i class="fa-solid fa-search"></i> &nbsp; Filter
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
                            <th style="text-align: center; color: white;"> NO KHS </th>
                            <th style="text-align: center; color: white;"> VENDOR </th>
                            <th style="text-align: center; color: white;"> KATEGORI </th>
                            <th style="text-align: center; color: white;"> MATERIAL </th>
                            <th style="text-align: center; color: white;"> SATUAN </th>
                            <th style="text-align: center; color: white;"> ALOKASI </th>
                            <th style="text-align: center; color: white;"> KONTRAK RINCI </th>
                            <th style="text-align: center; color: white;"> PROSENTASE </th>
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
$(document).ready(function() {
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "columnDefs": [{
            "className": "dt-body-center",
            "targets": [1, 6]
        },{
            "className": "dt-body-right",
            "targets": [7,8,9]
        }],
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_Kontrak/getAlokasiPemasaran",
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
                data.nomor_khs   = $("#nomor_khs").val(),
                data.kategori    = $("#kategori").val(),
                data.material_id = $("#material_id").val(),
                data.id_vendor   = $("#id_vendor").val(),
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