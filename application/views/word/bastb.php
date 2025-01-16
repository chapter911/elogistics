<?php
    use PhpOffice\PhpWord\PhpWord;
    use PhpOffice\PhpWord\Writer\Word2007;
    use PhpOffice\PhpWord\Style\Table;
    use PhpOffice\PhpWord\Style\Cell;

    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $phpWord->setDefaultFontName('Arial Narrow');
    $phpWord->setDefaultFontSize(11);

    $cm = 566.893;

    $section = $phpWord->addSection([
        'marginLeft' => 2 * $cm,
        'marginRight' => 2 * $cm,
        'marginTop' => 2.5 * $cm,
        'marginBottom' => 0.51 * $cm
    ]);

    $styleTable = array(
        'borderSize' => 1,
        'borderColor' => '000000',
        'cellMargin' => 50
    );

    $total_denda = 0;
    foreach ($denda as $d) {
        $total_denda += $d->jumlah_denda;
}

    function terbilang($x) {
        $angka = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($x < 12) {
            $temp = " " . $angka[$x];
        } else if ($x < 20) {
            $temp = terbilang($x - 10) . " Belas";
        } else if ($x < 100) {
            $temp = terbilang($x / 10) . " Puluh" . terbilang($x % 10);
        } else if ($x < 200) {
            $temp = " Seratus" . terbilang($x - 100);
        } else if ($x < 1000) {
            $temp = terbilang($x / 100) . " Ratus" . terbilang($x % 100);
        } else if ($x < 2000) {
            $temp = " Seribu" . terbilang($x - 1000);
        } else if ($x < 1000000) {
            $temp = terbilang($x / 1000) . " Ribu" . terbilang($x % 1000);
        } else if ($x < 1000000000) {
            $temp = terbilang($x / 1000000) . " Juta" . terbilang($x % 1000000);
        } else if ($x < 1000000000000) {
            $temp = terbilang($x / 1000000000) . " Milyar" . terbilang($x % 1000000000);
        }
        return $temp;
    }

    $section->addText('BERITA ACARA SERAH TERIMA BARANG', ['bold' => true, 'size' => 16], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $section->addText('Antara', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $section->addText('PT. PLN (Persero) UNIT INDUK DISTRIBUSI JAKARTA RAYA', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $section->addText('Dengan', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $section->addText($data[0]->vendor, ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $section->addText('Nomor : ' . $data[0]->no_bastb, ['bold' => true], ['align' => 'center']);

    // Paragraph
    $section->addText('Pada hari ini, ' . $tanggal . ' (' . date("d/m/Y", strtotime($data[0]->tanggal)) . '), yang bertanda tangan dibawah ini :', [], ['align' => 'both']);
    // Table
    $table = $section->addTable();
    $table->addRow();
    $table->addCell(1 * $cm)->addText('I.');
    $table->addCell(5 * $cm)->addText($data[0]->nama_manager, ['bold' => true]);
    $table->addCell(1 * $cm)->addText(':');
    $textRun = $table->addCell(12 * $cm)->addTextRun(['align' => 'both']);
    $textRun->addText($data[0]->jabatan_manager);
    $textRun->addText(' PT. PLN ( Persero ) Unit Induk Distribusi Jakarta Raya', ['bold' => true]);
    $textRun->addText(' yang berkedudukan di Jl. M.I.R Rais No. 1 - Jakarta Pusat, dalam Berita Acara Serah Terima Barang ini, bertindak untuk dan atas nama ');
    $textRun->addText(' PIHAK PERTAMA.', ['bold' => true], ['spaceBefore' => 0, 'spaceAfter' => 0]);

    $table->addRow();
    $table->addCell(1 * $cm)->addText('II.');
    $table->addCell(5 * $cm)->addText($data[0]->vendor_direktur, ['bold' => true]);
    $table->addCell(1 * $cm)->addText(':');
    $textRun = $table->addCell(12 * $cm)->addTextRun(['align' => 'both']);
    $textRun->addText($data[0]->vendor_jabatan);
    $textRun->addText(' ' . $data[0]->vendor);
    $textRun->addText(' yang berkedudukan di ' . $data[0]->alamat . '. dalam Berita Acara Serah Terima Barang ini disebut');
    $textRun->addText(' PIHAK KEDUA.', ['bold' => true], ['spaceBefore' => 0, 'spaceAfter' => 0]);

    $table = $section->addTable();
    $table->addRow();
    $table->addCell(7 * $cm)->addText('Berdasarkan Kontrak Rinci (KR)', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(6 * $cm)->addText($data[0]->no_kontrak, [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(6 * $cm)->addText('tanggal ' . date('d F Y', strtotime($data[0]->awal_kontrak)), [], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addRow();
    $table->addCell(7 * $cm)->addText('Kontrak Kesepakatan Harga Satuan (KHS)', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(6 * $cm)->addText($data[0]->nomor_khs, [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(6 * $cm)->addText('tanggal ' . date('d F Y', strtotime($data[0]->tanggal)), [], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addRow();
    $table->addCell(7 * $cm)->addText('Tanggal Akhir Kontrak', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(6 * $cm)->addText('s/d', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(6 * $cm)->addText('tanggal ' . date('d F Y', strtotime($data[0]->akhir_kontrak)), [], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);

    $section->addTextBreak();
    $textRun = $section->addTextRun(['align' => 'both']);
    $textRun->addText('1.   PIHAK KEDUA ', ['bold' => true]);
    $textRun->addText('telah menyerahkan barang di gudang ');
    $textRun->addText('PIHAK PERTAMA ', ['bold' => true]);
    $textRun->addText('dan telah menerima barang tersebut sebagai penerimaan block stock karantina sesuai Slip Penerimaan Barang-Barang/Spare Parts ( Karantina ) dengan nomor slip dan tanggal terlampir, dengan rincian material sebagai berikut :');

    $styleTable = array('borderSize' => 1, 'borderColor' => '000000');
    $table = $section->addTable($styleTable);
    $table->addRow();
    $table->addCell(1 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText('NO.', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(9 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText('NAMA BARANG', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(2 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText('JUMLAH', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(2 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText('SATUAN', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(5 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText('KETERANGAN', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    for ($i = 0; $i < count($material); $i++) {
        $table->addRow();
        $table->addCell(1 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText($i + 1, [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(9 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText($material[$i]->material, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(2 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText($material[$i]->volume, [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(2 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText($material[$i]->satuan, [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(5 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText($material[$i]->keterangan, [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    }

    $section->addTextBreak();
    $section->addText('2.   Jenis dan jumlah barang - barang yang diterima dan dimasukan kedalam stock / persediaan sesuai slip penerimaan barang-barang / Spare Parts (Persediaan) dengan nomor slip dan  tanggal  terlampir,  dengan rincian material sebagai berikut :', [], ['align' => 'both']);

    $table = $section->addTable($styleTable);
    $table->addRow();
    $table->addCell(1 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText('NO.', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(9 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText('NAMA BARANG', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(2 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText('JUMLAH', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(2 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText('SATUAN', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(5 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText('KETERANGAN', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    for ($i = 0; $i < count($material); $i++) {
        $table->addRow();
        $table->addCell(1 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText($i + 1, [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(9 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText($material[$i]->material, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(2 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText($material[$i]->volume, [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(2 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText($material[$i]->satuan, [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(5 * $cm, array('borderLeftSize' => 1, 'borderLeftColor' => '000000'))->addText($material[$i]->keterangan, [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    }

    $section->addTextBreak();
    $section->addText('3.   Barang - barang tersebut diatas dilengkapi dengan  :', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
    $section->addText('-	Berita Acara Pemeriksaan Barang/Spare Part: Terlampir.', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
    $section->addText('-	Berita Acara Pemeriksaan dan pengujian Material : Terlampir', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

    $section->addTextBreak();
    $section->addText('4.   Terlampir Nilai Total', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
    $table = $section->addTable();
    $table->addRow();
    $table->addCell(4 * $cm)->addText('Nilai Tagihan', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(1 * $cm)->addText(': Rp.', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(2 * $cm)->addText(number_format($data[0]->before_ppn, 0, ',', '.'), [], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addRow();
    $table->addCell(4 * $cm)->addText('PPN 11 %', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(1 * $cm)->addText(': Rp.', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(2 * $cm)->addText(number_format($data[0]->ppn, 0, ',', '.'), [], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addRow();
    $table->addCell(4 * $cm)->addText('Total Tagihan + (PPN 11%)', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(1 * $cm)->addText(': Rp.', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(2 * $cm)->addText(number_format($data[0]->after_ppn, 0, ',', '.'), [], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addRow();
    $table->addCell(4 * $cm)->addText('Denda', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(1 * $cm)->addText(': Rp.', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(2 * $cm)->addText(number_format($total_denda, 0, ',', '.'), [], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addRow();
    $table->addCell(4 * $cm)->addText('', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(1 * $cm)->addText('  Rp.', [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(2 * $cm)->addText(number_format($data[0]->after_ppn - $total_denda, 0, ',', '.'), [], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);

    $section->addTextBreak();
    $section->addText('Demikian Berita Acara Serah Terima Barang di Gudang PT. PLN ( Persero ) Unit Induk Distribusi Jakarta Raya ini dibuat dalam rangkap 4 ( empat ) untuk dipergunakan sebagaimana mestinya.');

    $section->addTextBreak();
    $table = $section->addTable();
    $table->addRow();
    $table->addCell(9.5 * $cm)->addText('PIHAK KEDUA', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(9.5 * $cm)->addText('PIHAK PERTAMA', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addRow();
    $table->addCell(9.5 * $cm)->addText('', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(9.5 * $cm)->addText('PT PLN (Persero)', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addRow();
    $table->addCell(9.5 * $cm)->addText($data[0]->vendor, ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(9.5 * $cm)->addText('UNIT INDUK DISTRIBUSI JAKARTA RAYA', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addRow();
    $table->addRow();
    $table->addRow();
    $table->addRow();
    $table->addRow();
    $table->addRow();
    $table->addCell(9.5 * $cm)->addText($data[0]->vendor_direktur, ['bold' => true,  'underline' => 'single'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(9.5 * $cm)->addText($data[0]->nama_manager, ['bold' => true, 'underline' => 'single'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addRow();
    $table->addCell(9.5 * $cm)->addText($data[0]->vendor_jabatan, ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(9.5 * $cm)->addText($data[0]->jabatan_manager, ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);

    $footer = $section->addFooter();
    $footer->addText('Paraf : ...............', [], ['align' => 'right']);

    $section->addPageBreak();

    $section = $phpWord->addSection([
        'orientation' => 'landscape',
        'marginLeft' => 1 * $cm,
        'marginRight' => 1 * $cm,
        'marginTop' => 2.5 * $cm,
        'marginBottom' => 0.51 * $cm
    ]);

    $section->addText('LAMPIRAN BASTB KR ' . $data[0]->no_kontrak, ['size' => 16], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $section->addText($data[0]->vendor, ['bold' => true, 'size' => 16], ['align' => 'center']);

    $table = $section->addTable($styleTable);
    $table->addRow();
    $table->addCell(1 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('NO', ['bold' => true, 'color' => 'white'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(6 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('UNIT', ['bold' => true, 'color' => 'white'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(8 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('MATERIAL', ['bold' => true, 'color' => 'white'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(1.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('VOL KIRIM', ['bold' => true, 'color' => 'white'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(3 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('AKHIR KONTRAK', ['bold' => true, 'color' => 'white'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(3 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('TANGGAL TERIMA', ['bold' => true, 'color' => 'white'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(3 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('SLIP PENERIMAAN', ['bold' => true, 'color' => 'white'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(3 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('TANGGAL PERSEDIAAN', ['bold' => true, 'color' => 'white'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(3 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('SLIP PERSEDIAAN', ['bold' => true, 'color' => 'white'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $i = 1;
    $total = 0;
    foreach ($material_dtl as $d) {
        $total += $d->volume;
        $table->addRow();
        $table->addCell(1 * $cm, ['valign' => 'center'])->addText($i++, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(6 * $cm, ['valign' => 'center'])->addText($d->name, ['size' => 9], ['spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(8 * $cm, ['valign' => 'center'])->addText($d->material, ['size' => 9], ['spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(1.5 * $cm, ['valign' => 'center'])->addText($d->volume, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(3 * $cm, ['valign' => 'center'])->addText($d->akhir_kontrak, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(3 * $cm, ['valign' => 'center'])->addText($d->tanggal_penerimaan, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(3 * $cm, ['valign' => 'center'])->addText($d->slip_penerimaan, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(3 * $cm, ['valign' => 'center'])->addText($d->tanggal_persediaan, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(3 * $cm, ['valign' => 'center'])->addText($d->no_persediaan, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    }
    $table->addRow();
    $table->addCell(null, ['valign' => 'center', 'gridSpan' => 3])->addText('Jumlah Total', ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(null, ['valign' => 'center', 'gridSpan' => 1])->addText($total, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
    $table->addCell(null, ['valign' => 'center', 'gridSpan' => 5])->addText('', ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);

    $footer = $section->addFooter();
    $footer->addText('Paraf : ...............', [], ['align' => 'right']);

    if($total_denda != 0){
        $section->addPageBreak();

        $section = $phpWord->addSection([
            'orientation' => 'landscape',
            'marginLeft' => 1 * $cm,
            'marginRight' => 1 * $cm,
            'marginTop' => 2.5 * $cm,
            'marginBottom' => 0.51 * $cm
        ]);

        $table = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END]);
        $table->addRow();
        $table->addCell(3 * $cm)->addText('Lamp. BASTB', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(1 * $cm)->addText(':', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(4 * $cm)->addText($data[0]->no_bastb, [], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addRow();
        $table->addCell(3 * $cm)->addText('Tanggal', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(1 * $cm)->addText(':', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(4 * $cm)->addText($data[0]->tanggal, [], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);

        $section->addText('PERINCIAN PERHITUNGAN SANKSI / DENDA', ['bold' => true, 'underline' => 'single'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $section->addText('KR : ' . $data[0]->no_kontrak . ' tanggal ' . date('d-m-Y', strtotime($data[0]->awal_kontrak)), [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $section->addText('KHS : ' . $data[0]->nomor_khs . ' tanggal ' . date('d F Y', strtotime($data[0]->tanggal)), [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $section->addText('PELAKSANA : ' . $data[0]->vendor, [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $section->addText('NILAI SP/SPK/PJN : Rp ' . number_format($data[0]->before_ppn, 0, ',', '.'), [], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);

        $section->addTextBreak();

        $table = $section->addTable($styleTable);
        $table->addRow();
        $table->addCell((5 / 100) * 27.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('NO', ['bold' => true, 'color' => 'white','size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((10 / 100) * 27.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('GUDANG', ['bold' => true, 'color' => 'white','size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((20 / 100) * 27.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('MATERIAL', ['bold' => true, 'color' => 'white','size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((3 / 100) * 27.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('SAT', ['bold' => true, 'color' => 'white','size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((5 / 100) * 27.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('VOLUME', ['bold' => true, 'color' => 'white','size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((10 / 100) * 27.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('NILAI SP/SPK/PJN', ['bold' => true, 'color' => 'white','size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((10 / 100) * 27.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('BATAS WAKTU PENGIRIMAN', ['bold' => true, 'color' => 'white','size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((10 / 100) * 27.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('TGL PENYELESAIAN PENYERAHAN BARANG', ['bold' => true, 'color' => 'white','size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((7 / 100) * 27.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('JUMLAH HARI KETERLAMBATAN', ['bold' => true, 'color' => 'white','size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((10 / 100) * 27.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('DENDA / HARI', ['bold' => true, 'color' => 'white','size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((10 / 100) * 27.5 * $cm, ['bgColor' => '008b8b', 'valign' => 'center'])->addText('JUMLAH DENDA', ['bold' => true, 'color' => 'white','size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $i = 1;
        $total = 0;
        $total_denda = 0;
        foreach ($denda as $d) {
            $total += $d->volume;
            $total_denda += $d->jumlah_denda;
            $table->addRow();
            $table->addCell(null, ['valign' => 'center'])->addText($i++, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
            $table->addCell(null, ['valign' => 'center'])->addText($d->name, ['size' => 9], ['spaceBefore' => 0, 'spaceAfter' => 0]);
            $table->addCell(null, ['valign' => 'center'])->addText($d->material, ['size' => 9], ['spaceBefore' => 0, 'spaceAfter' => 0]);
            $table->addCell(null, ['valign' => 'center'])->addText($d->satuan, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
            $table->addCell(null, ['valign' => 'center'])->addText(number_format($d->volume, 0, ',', '.'), ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
            $table->addCell(null, ['valign' => 'center'])->addText(number_format($d->nilai, 0, ',', '.'), ['size' => 9], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
            $table->addCell(null, ['valign' => 'center'])->addText($d->batas, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
            $table->addCell(null, ['valign' => 'center'])->addText($d->tanggal_penerimaan, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
            $table->addCell(null, ['valign' => 'center'])->addText($d->terlambat, ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
            $table->addCell(null, ['valign' => 'center'])->addText(number_format($d->denda, 0, ',', '.'), ['size' => 9], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
            $table->addCell(null, ['valign' => 'center'])->addText(number_format($d->jumlah_denda, 0, ',', '.'), ['size' => 9], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        }
        $table->addRow();
        $table->addCell(null, ['valign' => 'center'])->addText('', ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(null, ['valign' => 'center'])->addText('', ['size' => 9], ['spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(null, ['valign' => 'center'])->addText('', ['size' => 9], ['spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(null, ['valign' => 'center'])->addText('', ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(null, ['valign' => 'center'])->addText(number_format($total, 0, ',', '.'), ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(null, ['valign' => 'center'])->addText(number_format($data[0]->before_ppn, 0, ',', '.'), ['size' => 9], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(null, ['valign' => 'center'])->addText('', ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(null, ['valign' => 'center'])->addText('', ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(null, ['valign' => 'center'])->addText('', ['size' => 9], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(null, ['valign' => 'center'])->addText('Total Denda', ['size' => 9], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell(null, ['valign' => 'center'])->addText(number_format($total_denda, 0, ',', '.'), ['size' => 9], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);

        $table->addRow();
        $table->addCell(null, ['valign' => 'center', 'gridSpan' => 11])->addText('Terbilang : ' . terbilang($total_denda), ['size' => 9], ['align' => 'right', 'spaceBefore' => 0, 'spaceAfter' => 0]);

        $section->addTextBreak();
        $table = $section->addTable();
        $table->addRow();
        $table->addCell((50 / 100) * 27.5 * $cm)->addText('PIHAK KEDUA', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((50 / 100) * 27.5 * $cm)->addText('PIHAK PERTAMA', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addRow();
        $table->addCell((50 / 100) * 27.5 * $cm)->addText('', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((50 / 100) * 27.5 * $cm)->addText('PT PLN (Persero)', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addRow();
        $table->addCell((50 / 100) * 27.5 * $cm)->addText($data[0]->vendor, ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((50 / 100) * 27.5 * $cm)->addText('UNIT INDUK DISTRIBUSI JAKARTA RAYA', ['bold' => true], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addRow();
        $table->addRow();
        $table->addRow();
        $table->addRow();
        $table->addCell((50 / 100) * 27.5 * $cm)->addText($data[0]->vendor_direktur, ['bold' => true, 'underline' => 'single'], ['align' => 'center',  'underline' => true, 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((50 / 100) * 27.5 * $cm)->addText($data[0]->nama_manager, ['bold' => true, 'underline' => 'single'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addRow();
        $table->addCell((50 / 100) * 27.5 * $cm)->addText($data[0]->vendor_jabatan, ['bold' => true, 'underline' => 'single'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);
        $table->addCell((50 / 100) * 27.5 * $cm)->addText($data[0]->jabatan_manager, ['bold' => true, 'underline' => 'single'], ['align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0]);

        $footer = $section->addFooter();
        $footer->addText('Paraf : ...............', [], ['align' => 'right']);
    }

    $writer = new Word2007($phpWord);

    $filename = "BASTB " . $data[0]->vendor . " " . $data[0]->tanggal;

    header('Content-Type: application/msword');
    header('Content-Disposition: attachment;filename="'. $filename .'.docx"');
    header('Cache-Control: max-age=0');

    ob_clean();
    $writer->save('php://output');