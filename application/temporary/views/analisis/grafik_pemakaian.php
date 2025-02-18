<div id="grafik_pemakaian" style="height: 300px;"></div>

<script>
    var options = {
        series: [
            {
                name: "High - 2013",
                data: [
                    <?php foreach ($hasil as $h) {
                        echo html_escape($h->tahunan) . ", ";
                    } ?>
                ]
            },
        ],
        chart: {
            height: 350,
            type: 'bar',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '20%',
                endingShape: 'rounded'
            }
        },
        colors: ['#008B8B', '#545454'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth'
        },
        title: {
            text: 'Trend Pemakaian Material Tahunan',
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: [
                <?php foreach ($hasil as $h) {
                    echo html_escape($h->tahun) . ", ";
                } ?>
            ],
            title: {
                text: 'Tahun'
            }
        },
        yaxis: {
            title: {
                text: 'Pemakaian Tahunan'
            },
            min: 0,
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
        }
    };

    var chart = new ApexCharts(document.querySelector("#grafik_pemakaian"), options);
    chart.render();
</script>