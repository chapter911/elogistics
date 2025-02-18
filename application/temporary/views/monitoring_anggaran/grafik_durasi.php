<div id="grafik_durasi" style="height: 300px;"></div>

<script>
    var options = {
        series: [
            {
                name: 'Durasi',
                data: [
                    <?php foreach ($grafik as $d) {
                        echo html_escape(number_format($d->durasi, 2, '.', ',')) . ", ";
                    } ?>
                ]
            }
        ],
        colors:['#008B8B'],
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
            enabled: true,
            style: {
                colors: ['#FFFFFF'],
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
                text: 'Durasi'
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

    var chart = new ApexCharts(document.querySelector("#grafik_durasi"), options);
    chart.render();
</script>