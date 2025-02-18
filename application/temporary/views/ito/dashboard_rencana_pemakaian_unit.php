<div class="row mb-5">
    <div class="col-md-12">
        <div class="card" style="justify-content: center">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 ">Grafik Pemakaian Material</span>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card " style="justify-content: center">
            <div class="card-body pt-3">
                <div id="grafik_rencana_pemakaian" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
var data_unit = {
    series: [
        {
            name: "Rencana Pemakaian Material (Milyar)",
            type: "column",
            data: [
                <?php
                    foreach ($data as $d) {
                        echo "'" . html_escape(number_format($d->rencana, 2, '.', ',')) . "', ";
                    }
                ?>
            ]
        },
    ],
    colors: ['#008b8b'],
    chart: {
        type: 'bar',
        height: 500
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '75%',
            endingShape: 'rounded',
            dataLabels: {
                position: 'top'
            }
        }
    },
    dataLabels: {
        enabled: true
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
            <?php foreach ($data as $d) {
                    echo "'" . html_escape($d->singkatan) . "', ";
                }
            ?>
        ],
    },
    yaxis: {
        title: {
            text: 'Rencana Pemakaian Material (Milyar)'
        },
        labels: {
            formatter: function(value) {
                return value;
            }
        },
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function(val) {
                return val;
            }
        }
    }
};

var chart_data_unit = new ApexCharts(document.querySelector("#grafik_rencana_pemakaian"), data_unit);
chart_data_unit.render();
</script>