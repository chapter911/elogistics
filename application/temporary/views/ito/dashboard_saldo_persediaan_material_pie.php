<div class="row mb-5">
    <div class="col-md-12">
        <div class="card" style="justify-content: center">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3">Grafik Saldo Persediaan Material</span>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card" style="justify-content: center">
            <div class="card-body pt-3">
                <table id="table" class="table align-middle table-bordered" style="white-space: nowrap;">
                    <thead style="background-color: #008B8B">
                        <tr>
                            <td style="text-align: center; vertical-align: middle; color: white;"> NO </td>
                            <td style="text-align: center; vertical-align: middle; color: white;"> URAIAN </td>
                            <td style="text-align: center; vertical-align: middle; color: white;"> SALDO </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                            $total = 0;
                            foreach ($data as $d) {
                                $total += $d->saldo_akhir;?>
                        <tr>
                            <td style="text-align: center; vertical-align: middle;"><?= $i++; ?></td>
                            <td style="text-align: center; vertical-align: middle;"><?= $d->uraian; ?></td>
                            <td style="text-align: right; vertical-align: middle;">
                                <?= number_format($d->saldo_akhir, 0, ',', '.'); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot style="background-color: #008B8B">
                        <tr>
                            <td colspan="2" style="text-align: center; vertical-align: middle; color: white;">TOTAL</td>
                            <td style="text-align: right; vertical-align: middle; color: white;">
                                <?= number_format($total, 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="justify-content: center">
            <div class="card-body pt-3">
                <div id="grafik_pie" style="height: 420px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var options = {
        series: [
            <?php foreach ($data as $d) {
                echo $d->saldo_akhir . ',';
            } ?>
        ],
        chart: {
            width: 500,
            type: 'pie',
        },
        labels: [
            <?php foreach ($data as $d) {
                echo "'" . html_escape($d->uraian) . "', ";
        } ?>
        ],
        legend: {
            position: 'bottom',
            horizontalAlign: 'center'
        },
        responsive: [{
            breakpoint: 300,
            options: {
                chart: {
                    width: 300
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center'
                }
            }
        }],
    };

    var chart_total = new ApexCharts(document.querySelector("#grafik_pie"), options);

    chart_total.render();
});
</script>