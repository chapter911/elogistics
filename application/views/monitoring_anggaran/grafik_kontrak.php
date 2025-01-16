<div id="grafik_kontrak" style="height: 300px;"></div>

<script>
    var options = {
        series: [
            {
                name: 'Rencana Bayar',
                data: [
                    <?php foreach ($grafik as $d) {
                        echo html_escape($d->rencana_bayar) . ", ";
                    } ?>
                ]
            },
            {
                name: 'Realisasi Bayar',
                data: [
                    <?php foreach ($grafik as $d) {
                        echo html_escape($d->realisasi_bayar) . ", ";
                    } ?>
                ]
            }
        ],
        colors:['#008B8B', '#ff9f43'],
        chart: {
            type: 'bar',
            height: 500
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '90%',
                endingShape: 'rounded'
            }
        },
        dataLabels: {
            enabled: false,
            style: {
                colors: ['#000000'],
                rotation: -90
            }
        },
        stroke: {
            show: true,
            curve: 'smooth',
            lineCap: 'butt',
            colors: undefined,
            width: 5,
            dashArray: 0,
        },
        xaxis: {
            categories: [
                <?php foreach ($grafik as $d) {
                    echo "'" . html_escape($d->singkatan) . "', ";
                }
                ?>
            ],
        },
        yaxis: {
            title: {
                text: 'Jumlah Kontrak'
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y:  {
                    formatter: function (val) {
                        return val;
                    }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#grafik_kontrak"), options);
    chart.render();
</script>