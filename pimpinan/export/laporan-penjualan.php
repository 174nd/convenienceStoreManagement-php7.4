<?php
$backurl = '../../';
require_once($backurl . 'pimpinan/config/settings.php');
require $backurl . 'plugins/PhpSpreadSheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();

$inputFileType = 'Xlsx';
$inputFileName = './laporan-penjualan.xlsx';
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
$spreadsheet = $reader->load($inputFileName);

$tgl_penjualan = ($_POST['tgl_awal'] == $_POST['tgl_akhir']) ? tanggal_indo($_POST['tgl_awal']) : tanggal_indo($_POST['tgl_awal']) . ' - ' . tanggal_indo($_POST['tgl_akhir']);
$judul = "Laporan Penjualan " . $tgl_penjualan;

$no = 1;
$numrow = 5;
$sql = mysqli_query($conn, "SELECT * FROM penjualan JOIN barang WHERE penjualan.id_barang=barang.id_barang AND tgl_penjualan BETWEEN '$_POST[tgl_awal]' AND '$_POST[tgl_akhir]' ORDER BY tgl_penjualan DESC");
if (mysqli_num_rows($sql) > 0) {
  $spreadsheet->getActiveSheet()->setCellValue('C2', $tgl_penjualan);
  while ($data = mysqli_fetch_array($sql)) {
    $spreadsheet->getActiveSheet()->setCellValue('A' . $numrow, tanggal_indo($data['tgl_penjualan']));
    $spreadsheet->getActiveSheet()->setCellValue('B' . $numrow, $data['nm_barang']);
    $spreadsheet->getActiveSheet()->setCellValue('C' . $numrow, $data['qty_beli']);
    $spreadsheet->getActiveSheet()->setCellValue('D' . $numrow, format_rupiah($data['hrg_barang']));
    $spreadsheet->getActiveSheet()->insertNewRowBefore($numrow + 1, 1);
    $spreadsheet->getActiveSheet()->getRowDimension($numrow)->setRowHeight(-1);
    $numrow++;
    $no++;
  }
  (mysqli_num_rows($sql) > 0) ? $spreadsheet->getActiveSheet()->removeRow(($numrow + 1), 1) : '';
}


// $spreadsheet->setActiveSheetIndexByName('Realisasi');
$spreadsheet->getProperties()->setCreator('AndL')->setLastModifiedBy('AndL')->setTitle($judul)->setSubject("Kartu Kontrol")->setDescription("Export Data $judul")->setKeywords("$judul");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $judul . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
