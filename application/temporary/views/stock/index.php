<div class="card card-action mb-12">
    <div class="card-header">
        <h5 class="card-action-title mb-0">Daftar Stock Material
            <i><?= date("d-m-Y", strtotime(html_escape($tanggal_stock[0]->tanggal_stock))); ?></i>
        </h5>
    </div>
    <div class="collapse p-5 show">
        <form class="form" action="<?= base_url(); ?>C_Stock/Export" method="POST">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
            <div class="row">
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="form-select select2"
                            data-placeholder="Pilih Kategori" onchange="filterData()">
                            <option value="*">- SEMUA KATEGORI -</option>
                            <?php foreach($kategori as $d) { ?>
                            <option value="<?= html_escape($d->id); ?>"><?= html_escape($d->kategori); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Tampilkan</label>
                        <select name="is_highlight" id="is_highlight" class="form-select select2"
                            data-placeholder="Pilih Highlight" onchange="filterData()">
                            <option value="1">HIGHLIGHT MATERIAL</option>
                            <option value="0">SEMUA MATERIAL</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                </div>
                <div class="col-2">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Upload</label>
                        <button type="button" class="btn btn-warning btn-block form-control" data-bs-toggle="modal"
                            data-bs-target="#modal_upload_stock">
                            <i class="fa-solid fa-upload"></i> &nbsp;  Upload Stock
                        </button>
                    </div>
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
                            <th style="text-align: center; color: white;"> STOCK UID </th>
                            <th style="text-align: center; color: white;"> STOCK UNIT </th>
                            <th style="text-align: center; color: white;"> TOTAL STOCK </th>
                            <th style="text-align: center; color: white;"> DETAIL </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_upload_stock" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Upload Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>C_Stock/import" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label class="form-label">Tanggal Stock</label>
                            <input type="date" name="tanggal_stock" id="tanggal_stock" class="form-control"
                                placeholder="Tanggal Stock" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label class="required fw-semibold fs-6 mb-2">File Dokumen Stock (Excel)</label>
                            <input type="file" name="upload_file" id="upload_file" class="form-control"
                                accept=".xls,.xlsx" placeholder="xls,.xlsx" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <a href="<?= base_url(); ?>data_uploads/stockmaterial/format.xlsx" download target="_blank"
                                class="btn btn-success form-control">Download Format</a>
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

<div class="modal fade" id="modal_detail_stock" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Detail Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form" action="<?= base_url(); ?>/C_WebUser/Save" method="POST">
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <div class="modal-body">
                    <div class="card-datatable text-nowrap">
                        <table id="tabledetail" class="table">
                            <thead>
                                <tr style="background-color: #008B8B">
                                    <th style="text-align: center; color: white;"> NO </th>
                                    <th style="text-align: center; color: white;"> NORMALISASI </th>
                                    <th style="text-align: center; color: white;"> MATERIAL </th>
                                    <th style="text-align: center; color: white;"> UNIT </th>
                                    <th style="text-align: center; color: white;"> SATUAN </th>
                                    <th style="text-align: center; color: white;"> JUMLAH </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #008B8B">
                                    <th style="text-align: center; color: white;" colspan="5"> TOTAL </th>
                                    <th style="text-align: center; color: white;" id="total_detail_stock"> 0 </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var table;
var tabledetail;

$(document).ready(function() {
    table = $("#table").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "columnDefs": [{
            targets: [0, 1, 2, 4, 5, 6, 7, 8],
            className: 'dt-body-center'
        }, ],
        "pageLength": 10,
        "ajax": {
            "url": "<?= base_url() ?>C_Stock/getData",
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
                data.kategori = $("#kategori_id").val(),
                data.is_highlight = $("#is_highlight").val(),
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

function detailStock(id, material) {
    tabledetail = $("#tabledetail").DataTable({
        "destroy": true,
        "bPaginate": false,
        "columnDefs": [{
            targets: [0, 1, 4, 5],
            className: 'dt-body-center'
        }, ],
        "ajax": {
            "url": "<?= base_url() ?>C_Stock/detailStock",
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
                data.id = id,
                data.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash();?>"
            },
            "complete": function(response) {
                Swal.close();
                $('#total_detail_stock').html(response.responseJSON.total[0]);
                $("#material_stock").html(material);
                $("#modal_detail_stock").modal('show');
            },
            "error": function(jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        }
    });
}
</script>