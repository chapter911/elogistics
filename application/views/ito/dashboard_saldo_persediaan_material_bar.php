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
                <div id="grafik_bar" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="justify-content: center">
            <div class="card-body pt-3">
                <div id="grafik_saldo_uid" style="height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
<?php $total_uid =  0; ?>
var non_uid = {
    series: [
        {
            name: "Saldo Persediaan",
            type: "column",
            data: [
                <?php
                        foreach ($unit as $u) {
                            foreach ($non_uid as $r) {
                                $total_uid += $r->saldo_akhir;
                                if($u->kode_unit == $r->unit) {
                                    echo "'" . html_escape(number_format($r->saldo_akhir, 2, '.', ',')) . "', ";
                                }
                            }
                        }
                    ?>
            ]
        },
        {
            name: "Target",
            type: "line",
            data: [
                <?php
                    foreach ($unit as $u) {
                        foreach ($target as $r) {
                            if($u->kode_unit == $r->kode_unit) {
                                echo "'" . html_escape(number_format($r->target, 2, '.', ',')) . "', ";
                            }
                        }
                    }
                ?>
            ],
        },
    ],
    colors: ['#008b8b', '#ff9f43'],
    chart: {
        type: 'line',
        height: 500
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '75%',
            endingShape: 'rounded'
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
            <?php foreach ($unit as $d) {
                    echo "'" . html_escape($d->singkatan) . "', ";
                }
            ?>
        ],
    },
    yaxis: {
        title: {
            text: 'RP. (Milyard)'
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

var chart_non_uid = new ApexCharts(document.querySelector("#grafik_bar"), non_uid);
chart_non_uid.render();


var uid = {
    series: [{
        name: "Saldo Persediaan",
        type: "column",
        data: [<?= number_format($uid[0]->saldo_akhir, 2, '.', ','); ?>]
    }],
    colors: ['#008b8b'],
    chart: {
        type: 'bar',
        height: 500
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '75%',
            endingShape: 'rounded'
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
        categories: ["KD UID"],
    },
    yaxis: {
        title: {
            text: 'RP. (Milyard)'
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

var chart_uid = new ApexCharts(document.querySelector("#grafik_saldo_uid"), uid);
chart_uid.render();
</script>