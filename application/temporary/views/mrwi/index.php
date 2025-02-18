<div class="card">
    <div class="card-header border-0 pt-5">
        <h4 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold mb-1">DASHBOARD KRITERIA PENILAIAN MATURITY LEVEL IMPLEMENTASI MATERIAL RETURN & WAREHOUSE INVENTORY (MRWI)</span>
        </h4>
    </div>
    <div class="card-body pt-3">
        <div class="row">
            <div class="col-md-2">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">Triwulan</label>
                    <input type="text" id="id_hdr" value="0" hidden>
                    <select name="triwulan" id="triwulan" class="form-select" data-control="select2" data-placeholder="Pilih Triwulan" required>
                        <option></option>
                        <option value="Triwulan 1">Triwulan 1</option>
                        <option value="Triwulan 2">Triwulan 2</option>
                        <option value="Triwulan 3">Triwulan 3</option>
                        <option value="Triwulan 4">Triwulan 4</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select" data-control="select2" data-placeholder="Pilih Tahun" required>
                        <option></option>
                        <?php
                        for ($i = date('Y'); $i >= date('Y') - 5; $i--) {
                            if ($i == date('Y')) {
                                echo "<option value='$i' selected>$i</option>";
                            } else {
                                echo "<option value='$i'>$i</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="fv-row mb-7">
                    <label class="fw-semibold fs-6 mb-2">&nbsp;</label>
                    <button type="button" class="form-control btn btn-primary" onclick="get_header()"> <i class="fa  fa-refresh "></i> GET DASHBOARD </button>
                </div>
            </div>


        </div>

        <br>
        <div class="row">
            <div class="col-md-12">
                <div id="dt_hdr"></div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div id="chart"></div>
            </div>
        </div>
        <br>
        <div class="table-responsive" id="tampil_sini">

        </div>

    </div>
</div>


<script>
    $(document).ready(function() {
        // Mendapatkan bulan saat ini (1-12)
        var currentMonth = new Date().getMonth() + 1;

        // Mendapatkan triwulan berdasarkan bulan
        var currentQuarter = Math.ceil(currentMonth / 3);

        // Mengatur opsi terpilih berdasarkan triwulan saat ini
        $('#triwulan').val('Triwulan ' + currentQuarter).trigger("change");
        $data = [{
            "kriteria": "Komitmen Manajemen",
            "nilai": "0"
        }, {
            "kriteria": "Ada KPI implementasi MRWI",
            "nilai": "0"
        }, {
            "kriteria": "Keberlanjutan & Budaya MRWI",
            "nilai": "0"
        }, {
            "kriteria": "Dampak dan Hasil",
            "nilai": "0"
        }];
        drawChart2($data);
    });
</script>

<script>
    var chart;

    function get_header() {
        $unit = $("#unit").val();
        $triwulan = $("#triwulan").val();
        $tahun = $("#tahun").val();

        $url = "<?= base_url() ?>C_MRWI/get_data_hdr?th=" + $tahun + "&tw=" + $triwulan;
        // console.log($url);
        $.ajax({
            url: $url,
            type: "GET",
            data: "text",
            success: function(data) {
                $("#dt_hdr").html(data);
            }
        });
    }

    function get_detail($unit, $triwulan, $tahun) {
        $url = "<?= base_url() ?>C_MRWI/get_data?th=" + $tahun + "&tw=" + $triwulan + "&unit=" + $unit;
        $.ajax({
            url: $url,
            type: "GET",
            data: "JSON",
            success: function(data) {
                var datas = JSON.parse(data);
                console.log(datas);
                drawChart(datas);
                tampil_detail($unit, $triwulan, $tahun);
            }
        });
    }

    function tampil_detail($unit, $triwulan, $tahun) {
        $url = "<?= base_url() ?>C_MRWI/get_data_dtl?th=" + $tahun + "&tw=" + $triwulan + "&unit=" + $unit;
        $.ajax({
            url: $url,
            type: "GET",
            data: "JSON",
            success: function(data) {
                $("#tampil_sini").html(data);
            }
        });

    }

    function drawChart(data) {

        // Mengonversi data JSON ke format yang diterima oleh ApexCharts
        var categories = data.map(item => item.kriteria);
        var values = data.map(item => parseFloat(item.nilai));

        // Konfigurasi untuk chart spider
        var options = {
            chart: {
                height: 450,
                type: 'radar'
            },
            series: [{
                name: 'Series 1',
                data: values
            }],
            plotOptions: {
                radar: {
                    size: 180,
                    polygons: {
                        strokeColor: '#43231a',
                        fill: {
                            colors: ['#f8f8f8', '#fff']
                        }
                    }
                }
            },
            xaxis: {
                categories: categories,
                labels: {
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold' // adjust the font size as needed
                    }
                }
            }
        };

        chart.updateOptions(options);
    }

    function drawChart2(data) {
        // Mengonversi data JSON ke format yang diterima oleh ApexCharts
        var categories = data.map(item => item.kriteria);
        var values = data.map(item => parseFloat(item.nilai));

        // Konfigurasi untuk chart spider
        var options = {
            chart: {
                height: 400,
                type: 'radar'
            },
            series: [{
                name: 'Series 1',
                data: values
            }],
            plotOptions: {
                radar: {
                    size: 130,
                    polygons: {
                        strokeColor: '#43231a',
                        fill: {
                            colors: ['#f8f8f8', '#fff']
                        }
                    }
                }
            },
            xaxis: {
                categories: categories
            }
        };

        chart = new ApexCharts(document.querySelector("#chart"), options);

        chart.render();


    }
</script>