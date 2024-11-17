<?php
require '../../vendor/autoload.php'; // Pastikan path ke autoload.php benar
require_once '../Controller/peminjamancontroller.php';
require_once '../core/Database.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Inisialisasi Controller
$peminjamanController = new PeminjamanController();
$peminjamans = $peminjamanController->getAllPeminjaman(); // Mengambil semua data peminjaman

// Membuat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$headers = ['A1' => 'ID Peminjaman', 'B1' => 'Nama Peminjam', 'C1' => 'Judul Buku', 'D1' => 'Tanggal Peminjaman', 'E1' => 'Tanggal Pengembalian'];
foreach ($headers as $cell => $text) {
    $sheet->setCellValue($cell, $text);
}

// Styling header
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4F81BD'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];

$sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

// Menulis data ke dalam sheet
$rowNumber = 2; // Baris dimulai dari 2 karena 1 untuk header
foreach ($peminjamans as $peminjaman) {
    $sheet->setCellValue('A' . $rowNumber, $peminjaman['id_peminjaman']);
    $sheet->setCellValue('B' . $rowNumber, $peminjaman['nama_lengkap']);
    $sheet->setCellValue('C' . $rowNumber, $peminjaman['judul_buku']);
    $sheet->setCellValue('D' . $rowNumber, $peminjaman['tanggal_peminjaman']);
    $sheet->setCellValue('E' . $rowNumber, $peminjaman['tanggal_kembalian']);
    $rowNumber++;
}

// Mengatur lebar kolom agar otomatis menyesuaikan konten
foreach (range('A', 'E') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Membuat file Excel
$writer = new Xlsx($spreadsheet);
$filename = 'data_peminjaman.xlsx';
ob_end_clean(); // Tambahkan ini sebelum header HTTP

// Set header HTTP untuk unduh file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Expires: Fri, 11 Nov 2024 12:00:00 GMT');
header('Pragma: public');

// Tulis file Excel ke output
$writer->save('php://output');
exit();
?>
