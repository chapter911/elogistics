<div id="chart" style="height: 300px;"></div>

<script>
    var options = {
        series: [
            {
                name: 'Rencana Bayar',
                data: [
                    <?php foreach ($grafik as $d) {
                        echo number_format(html_escape($d->rencana_bayar), 0, "", "") . ", ";
                    } ?>
                ]
            },
            {
                name: 'Terbayar',
                data: [
                    <?php foreach ($grafik as $d) {
                        echo number_format(html_escape($d->terbayar), 0, "", "") . ", ";
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
                text: 'Nilai Kontrak'
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y:  {
                    formatter: function (val) {
                        return "Rp " + (val).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                    }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>