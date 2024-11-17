<?php
require_once '../core/database.php';
require_once '../Models/Kunjungan.php';
require_once '../../vendor/autoload.php'; // pastikan path benar ke PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Create a database connection
$database = new Database();
$db = $database->getConnection();

// Initialize the Kunjungan class
$kunjungan = new Kunjungan($db);

// Fetch all visits data
$stmt = $kunjungan->getAllVisits();
$visits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header columns
$headerColumns = ['A1' => 'No', 'B1' => 'Nama Pengunjung', 'C1' => 'Kelas', 'D1' => 'No Kartu', 'E1' => 'Tanggal Kunjungan', 'F1' => 'Keperluan'];
foreach ($headerColumns as $cell => $text) {
    $sheet->setCellValue($cell, $text);
}

// Style for header
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['argb' => 'FFFFFFFF'],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FF4CAF50',
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
$sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

// Populate data rows
$row = 2; // Starting row after headers
$no = 1;
foreach ($visits as $visit) {
    $sheet->setCellValue('A' . $row, $no++);
    $sheet->setCellValue('B' . $row, $visit['nama_lengkap']);
    $sheet->setCellValue('C' . $row, $visit['kelas']);
    $sheet->setCellValue('D' . $row, $visit['no_kartu']);
    $sheet->setCellValue('E' . $row, $visit['tanggal_kunjungan']);
    $sheet->setCellValue('F' . $row, $visit['keperluan']);
    $row++;
}

// Auto size columns
foreach (range('A', 'F') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

ob_end_clean(); // Tambahkan ini sebelum header HTTP

// Set headers for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Data_Kunjungan.xlsx"');
header('Cache-Control: max-age=0');

// Write to Excel format
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
