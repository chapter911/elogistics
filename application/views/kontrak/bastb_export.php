<?php
    $filename = "BASTB " . $data[0]->vendor . " " . $data[0]->tanggal;
    header("Content-Type: application/vnd.ms-word");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("content-disposition: attachment;filename=" . $filename . ".doc");
?>
<head>
    <style>
        h3, h4, p, table {
            font-family: Arial, sans-serif;
            font-size: 12.5px;
        }
        body {
            margin-left: -1.5cm;
            margin-right: -1.5cm;
            margin-top: -2.5cm;
            margin-bottom: -0.5cm;
        }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <h3>BERITA ACARA SERAH TERIMA BARANG</h3>
        <h4>Antara<br/>
            PT. PLN (Persero) UNIT INDUK DISTRIBUSI JAKARTA RAYA<br/>
            Dengan<br/>
            <?= $data[0]->vendor; ?><br/>
            Nomor : <?= $data[0]->no_bastb; ?><br/>
        </h4>
    </div>
    <p style="width: 120%; text-align: justify;">Pada hari ini, <?= $tanggal; ?> (<?= date("d/m/Y", strtotime($data[0]->tanggal)); ?>), yang bertanda tangan dibawah ini :</p>
    <table border="0" cellpadding="5" cellspacing="0" style="width: 120%">
        <tbody>
            <tr>
                <td width="5%">I</td>
                <td width="15%"><?= $data[0]->nama_manager; ?></td>
                <td width="5%">:</td>
                <td width="75%" style="text-align: justify;"><?= $data[0]->jabatan_manager; ?> <b>PT. PLN ( Persero ) Unit Induk Distribusi Jakarta Raya</b>
                    yang berkedudukan di Jl. M.I.R Rais No. 1 - Jakarta Pusat, dalam Berita Acara Serah Terima Barang ini,
                    bertindak untuk dan atas nama
                    <b>PIHAK PERTAMA.</b>
                </td>
            </tr>
            <tr>
                <td width="5%">II</td>
                <td width="15%"><?= $data[0]->vendor_direktur; ?></td>
                <td width="5%">:</td>
                <td width="75%" style="text-align: justify;">
                    Direktur Utama <b>PT TRITUNGGAL SWARNA</b>
                    yang berkedudukan di <?= $data[0]->alamat; ?>.
                    dalam Berita Acara Serah Terima Barang ini disebut
                    <b>PIHAK KEDUA</b>
                </td>
            </tr>
        </tbody>
    </table>
    <br/>
    <table border="0" cellpadding="5" cellspacing="5" style="width: 120%">
        <tbody>
            <tr>
                <td width="40%">Berdasarkan Kontrak Rinci (KR)</td>
                <td width="35%"><?= $data[0]->no_kontrak;?></td>
                <td width="25%" style="text-align: right"><?= date('d F Y', strtotime($data[0]->awal_kontrak));?></td>
            </tr>
            <tr>
                <td width="40%">Kontrak Kesepakatan Harga Satuan (KHS)</td>
                <td width="35%"><?= $data[0]->nomor_khs;?></td>
                <td width="25%" style="text-align: right"><?= date('d F Y', strtotime($data[0]->tanggal));?></td>
            </tr>
            <tr>
                <td width="40%">Tanggal Akhir Kontrak</td>
                <td width="35%" style="text-align: center;">s/d</td>
                <td width="25%" style="text-align: right"><?= date('d F Y', strtotime($data[0]->akhir_kontrak));?></td>
            </tr>
        </tbody>
    </table>
    <br/>
    <table border="0" cellpadding="2" cellspacing="0" style="width: 120%">
        <tbody>
            <tr>
                <td width="5%">1.</td>
                <td width="95%" style="text-align: justify;"><b>PIHAK KEDUA</b> telah menyerahkan barang di gudang <b>PIHAK PERTAMA</b> dan telah menerima barang tersebut sebagai penerimaan block stock karantina sesuai Slip Penerimaan Barang-Barang/Spare Parts ( Karantina ) dengan nomor slip dan tanggal terlampir, dengan rincian material sebagai berikut :</td>
            </tr>
        </tbody>
    </table>
    <br/>
    <table border="1" cellpadding="5" cellspacing="0" style="width: 120%; border-collapse: collapse;">
        <tbody>
            <tr>
                <td style="text-align: center;" width="5%"><b>NO</b></td>
                <td style="text-align: center;" width="45%"><b>NAMA BARANG</b></td>
                <td style="text-align: center;" width="15%"><b>JUMLAH</b></td>
                <td style="text-align: center;" width="10%"><b>SATUAN</b></td>
                <td style="text-align: center;" width="25%"><b>KETERANGAN</b></td>
            </tr>
            <?php
                $i = 1;
                $total = 0;
                foreach ($material as $m) {
                    $total += $m->volume;
                ?>
                <tr>
                    <td style="text-align: right;"><?= $i++; ?></td>
                    <td><?= $m->material; ?></td>
                    <td style="text-align: right;"><?= $m->volume; ?></td>
                    <td style="text-align: center;"><?= $m->satuan; ?></td>
                    <td><?= $m->keterangan; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2" style="text-align: center;">TOTAL</td>
                <td style="text-align: right;"><?= $total; ?></td>
                <td style="text-align: center;"><?= $material[0]->satuan; ?></td>
                <td style="text-align: center;"></td>
            </tr>
        </tbody>
    </table>
    <br/>
    <table border="0" cellpadding="5" cellspacing="0" style="width: 120%">
        <tbody>
            <tr>
                <td width="5%">2.</td>
                <td width="95%" style="text-align: justify;">
                    Jenis dan jumlah barang-barang yang diterima dan dimasukan kedalam stock / persediaan sesuai slip penerimaan barang-barang / Spare Parts (Persediaan) dengan nomor slip dan tanggal terlampir, dengan rincian material sebagai berikut :
                </td>
            </tr>
        </tbody>
    </table>
    <br/>
    <table border="1" cellpadding="5" cellspacing="0" style="width: 120%; border-collapse: collapse;">
        <tbody>
            <tr>
                <td style="text-align: center;" width="5%"><b>NO</b></td>
                <td style="text-align: center;" width="45%"><b>NAMA BARANG</b></td>
                <td style="text-align: center;" width="15%"><b>JUMLAH</b></td>
                <td style="text-align: center;" width="10%"><b>SATUAN</b></td>
                <td style="text-align: center;" width="25%"><b>KETERANGAN</b></td>
            </tr>
            <?php
                $i = 1;
                $total = 0;
                foreach ($material as $m) {
                    $total += $m->volume;
                ?>
                <tr>
                    <td style="text-align: right;"><?= $i++; ?></td>
                    <td><?= $m->material; ?></td>
                    <td style="text-align: right;"><?= $m->volume; ?></td>
                    <td style="text-align: center;"><?= $m->satuan; ?></td>
                    <td><?= $m->keterangan; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2" style="text-align: center;">TOTAL</td>
                <td style="text-align: right;"><?= $total; ?></td>
                <td style="text-align: center;"><?= $material[0]->satuan; ?></td>
                <td style="text-align: center;"></td>
            </tr>
        </tbody>
    </table>
    <br/>
    <table border="0" cellpadding="2" cellspacing="0" style="width: 120%">
        <tbody>
            <tr>
                <td width="5%">3.</td>
                <td width="95%" style="text-align: justify;">
                    Barang-barang tersebut diatas dilengkapi dengan
                </td>
            </tr>
            <tr>
                <td width="5%"></td>
                <td width="95%" style="text-align: justify;">
                    -	Berita Acara Pemeriksaan Barang/Spare Part: Terlampir.
                </td>
            </tr>
            <tr>
                <td width="5%"></td>
                <td width="95%" style="text-align: justify;">
                    -	Berita Acara Pemeriksaan dan pengujian Material : Terlampir
                </td>
            </tr>
        </tbody>
    </table>
    <br/>
    <table border="0" cellpadding="2" cellspacing="0" style="width: 120%">
        <tbody>
            <tr>
                <td width="5%">4.</td>
                <td width="40%" style="text-align: justify;">Terlampir Nilai Total</td>
                <td width="10%" style="text-align: justify;"></td>
                <td width="20%" style="text-align: justify;"></td>
                <td width="25%" style="text-align: justify;"></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: justify;">Nilai Tagihan</td>
                <td style="text-align: justify;">: Rp.</td>
                <td style="text-align: right;"><?= number_format($data[0]->before_ppn, 0, ',', '.')?>,-</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: justify;">PPN 11%</td>
                <td style="text-align: justify;">: Rp.</td>
                <td style="text-align: right;"><?= number_format($data[0]->ppn, 0, ',', '.')?>,-</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: justify;">Total Tagihan + (PPN 11%)</td>
                <td style="text-align: justify;">: Rp.</td>
                <td style="text-align: right;"><?= number_format($data[0]->after_ppn, 0, ',', '.')?>,-</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: justify;">Denda</td>
                <td style="text-align: justify;">: Rp.</td>
                <td style="text-align: right;"></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <p style="text-align: justify;">Demikian Berita Acara Serah Terima Barang di Gudang PT. PLN (Persero) Unit Induk Distribusi Jakarta Raya ini dibuat dalam rangkap 4(empat) untuk dipergunakan sebagaimana mestinya</p>
    <br/>
    <table border="0" cellpadding="2" cellspacing="0" width="120%">
        <tbody>
            <tr>
                <td width="40%" style="text-align: center;"><b>PIHAK KEDUA</b></td>
                <td width="20%" style="text-align: center;"></td>
                <td width="40%" style="text-align: center;"><b>PIHAK PERTAMA</b></td>
            </tr>
            <tr>
                <td width="40%" style="text-align: center;"><b><?= $data[0]->vendor; ?></b></td>
                <td width="20%" style="text-align: center;"></td>
                <td width="40%" style="text-align: center;"><b>PT. PLN (Persero)<br/>UNIT INDUK DISTRIBUSI JAKARTA RAYA</b></td>
            </tr>
        </tbody>
    </table>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <table border="0" cellpadding="2" cellspacing="0" width="120%">
        <tbody>
            <tr>
                <td width="40%" style="text-align: center;"><u><b><?= $data[0]->vendor_direktur; ?></b></u></td>
                <td width="20%" style="text-align: center;"></td>
                <td width="40%" style="text-align: center;"><u><b><?= $data[0]->nama_manager; ?></b></u></td>
            </tr>
            <tr>
                <td width="40%" style="text-align: center;"><b><?= $data[0]->vendor_jabatan; ?></b></td>
                <td width="20%" style="text-align: center;"></td>
                <td width="40%" style="text-align: center;"><b><?= $data[0]->jabatan_manager; ?></b></td>
            </tr>
        </tbody>
    </table>
    <br style="page-break-before: always;">
    <p style="text-align: center;"><?= $data[0]->no_kontrak; ?><br/><b><?= $data[0]->vendor; ?></b></p>
    <table border="1" cellpadding="3" cellspacing="0" style="width: 120%; border-collapse: collapse;">
        <thead style="background-color: #008B8B">
            <tr>
                <th style="text-align: center; color: white;">NO</th>
                <th style="text-align: center; color: white;">UNIT</th>
                <th style="text-align: center; color: white;">MATERIAL</th>
                <th style="text-align: center; color: white;">VOL<br/>KIRIM</th>
                <th style="text-align: center; color: white;">AKHIR<br/>KONTRAK</th>
                <th style="text-align: center; color: white;">TANGGAL<br/>TERIMA</th>
                <th style="text-align: center; color: white;">SLIP<br/>PENERIMAAN</th>
                <th style="text-align: center; color: white;">TANGGAL<br/>PERSEDIAAN</th>
                <th style="text-align: center; color: white;">SLIP<br/>PERSEDIAAN</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; $total = 0; foreach ($material_dtl as $d) {
                $total += $d->volume;
            ?>
            <tr>
                <td><?= $i++;?></td>
                <td><?= $d->name; ?></td>
                <td><?= $d->material; ?></td>
                <td><?= $d->volume; ?></td>
                <td><?= $d->akhir_kontrak; ?></td>
                <td><?= $d->tanggal_penerimaan; ?></td>
                <td><?= $d->slip_penerimaan; ?></td>
                <td><?= $d->tanggal_persediaan; ?></td>
                <td><?= $d->no_persediaan; ?></td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="3">Jumlah Total</td>
                <td><?= $total; ?></td>
                <td colspan="5"></td>
            </tr>
        </tbody>
    </table>
</body>