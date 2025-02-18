<?php
class MYPDF extends Pdf{

}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->SetAutoPageBreak(false, 0);
$pdf->SetFont('helvetica', '', 18);

foreach ($data as $d) {
    $pdf->AddPage('P', array(70, 70), 'mm');

    $style = array(
        'border' => 0,
        'vpadding' => 0,
        'hpadding' => 0,
        'fgcolor' => array(0,0,0),
        'bgcolor' => false,
        'module_width' => 1,
        'module_height' => 1
    );
    $pdf->write2DBarcode(html_escape($d->id), 'QRCODE,H', 10, 5, 50, 50, $style, 'N', false);
    $pdf->Text(23, 60, html_escape($d->id));
}

$pdf->Output('Barcode.pdf');