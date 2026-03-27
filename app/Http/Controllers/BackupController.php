<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk('backups');
        $files = [];

        $backupName = config('backup.backup.name', 'Laravel');

        if ($disk->exists($backupName)) {
            $backupFiles = $disk->files($backupName);

            foreach ($backupFiles as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($ext, ['zip', 'sql', 'xlsx'])) {
                    $type = 'Full';
                    if ($ext === 'sql') {
                        $type = 'SQL';
                    } elseif ($ext === 'xlsx') {
                        $type = 'Excel';
                    }
                    $files[] = [
                        'path' => $file,
                        'name' => basename($file),
                        'size' => $this->formatBytes($disk->size($file)),
                        'date' => date('Y-m-d H:i:s', $disk->lastModified($file)),
                        'type' => $type,
                    ];
                }
            }

            // เรียงตามวันที่ล่าสุดก่อน
            usort($files, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
        }

        return view('backups.index', compact('files'));
    }

    public function create()
    {
        try {
            Artisan::call('backup:run');
            return back()->with('success', 'สำรองข้อมูลสำเร็จ');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * สำรองฐานข้อมูลด้วย PHP (ไม่ใช้ mysqldump)
     */
    public function createDbOnly()
    {
        try {
            $disk = Storage::disk('backups');
            $backupName = config('backup.backup.name', 'Laravel');
            $filename = date('Y-m-d-H-i-s') . '-db.sql';
            $path = $backupName . '/' . $filename;

            // สร้างโฟลเดอร์ถ้ายังไม่มี
            if (!$disk->exists($backupName)) {
                $disk->makeDirectory($backupName);
            }

            // Export database ด้วย PHP
            $sql = $this->exportDatabase();

            // บันทึกไฟล์
            $disk->put($path, $sql);

            return back()->with('success', 'สำรองฐานข้อมูลสำเร็จ (' . $filename . ')');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Export database เป็น SQL string ด้วย PHP
     */
    private function exportDatabase(): string
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $key = 'Tables_in_' . $dbName;

        $sql = "-- Database Backup\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: " . $dbName . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "SET AUTOCOMMIT = 0;\n";
        $sql .= "START TRANSACTION;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$key;

            // Get CREATE TABLE statement
            $createTable = DB::select("SHOW CREATE TABLE `$tableName`");
            $sql .= "-- Table structure for `$tableName`\n";
            $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

            // Get table data
            $rows = DB::table($tableName)->get();

            if ($rows->count() > 0) {
                $sql .= "-- Data for `$tableName`\n";

                foreach ($rows as $row) {
                    $values = [];
                    foreach ((array) $row as $value) {
                        if (is_null($value)) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = "'" . addslashes($value) . "'";
                        }
                    }
                    $sql .= "INSERT INTO `$tableName` VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        $sql .= "COMMIT;\n";

        return $sql;
    }

    public function download($filename)
    {
        $disk = Storage::disk('backups');
        $backupName = config('backup.backup.name', 'Laravel');
        $path = $backupName . '/' . $filename;

        if (!$disk->exists($path)) {
            return back()->with('error', 'ไม่พบไฟล์ที่ต้องการดาวน์โหลด');
        }

        $fullPath = $disk->path($path);

        return response()->download($fullPath, $filename);
    }

    public function destroy($filename)
    {
        $disk = Storage::disk('backups');
        $backupName = config('backup.backup.name', 'Laravel');
        $path = $backupName . '/' . $filename;

        if (!$disk->exists($path)) {
            return back()->with('error', 'ไม่พบไฟล์ที่ต้องการลบ');
        }

        $disk->delete($path);

        return back()->with('success', 'ลบไฟล์สำรองข้อมูลเรียบร้อย');
    }

    public function clean()
    {
        try {
            Artisan::call('backup:clean');
            return back()->with('success', 'ทำความสะอาดไฟล์สำรองข้อมูลเก่าเรียบร้อย');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * สำรองข้อมูลทั้ง SQL และ Excel
     */
    public function createDbWithExcel()
    {
        try {
            $disk = Storage::disk('backups');
            $backupName = config('backup.backup.name', 'Laravel');
            $timestamp = date('Y-m-d-H-i-s');

            // สร้างโฟลเดอร์ถ้ายังไม่มี
            if (!$disk->exists($backupName)) {
                $disk->makeDirectory($backupName);
            }

            // 1. Export SQL
            $sqlFilename = $timestamp . '-db.sql';
            $sqlPath = $backupName . '/' . $sqlFilename;
            $sql = $this->exportDatabase();
            $disk->put($sqlPath, $sql);

            // 2. Export Excel
            $excelFilename = $timestamp . '-db.xlsx';
            $excelPath = $backupName . '/' . $excelFilename;
            $excelContent = $this->exportDatabaseToExcel();
            $disk->put($excelPath, $excelContent);

            return back()->with('success', 'สำรองข้อมูลสำเร็จ (SQL: ' . $sqlFilename . ', Excel: ' . $excelFilename . ')');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Export เฉพาะ Excel
     */
    public function createExcel()
    {
        try {
            $disk = Storage::disk('backups');
            $backupName = config('backup.backup.name', 'Laravel');
            $timestamp = date('Y-m-d-H-i-s');
            $filename = $timestamp . '-db.xlsx';
            $path = $backupName . '/' . $filename;

            // สร้างโฟลเดอร์ถ้ายังไม่มี
            if (!$disk->exists($backupName)) {
                $disk->makeDirectory($backupName);
            }

            // Export Excel
            $excelContent = $this->exportDatabaseToExcel();
            $disk->put($path, $excelContent);

            return back()->with('success', 'สำรองข้อมูล Excel สำเร็จ (' . $filename . ')');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * Export database เป็น Excel โดยแยก sheet ตามตาราง
     */
    private function exportDatabaseToExcel(): string
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $key = 'Tables_in_' . $dbName;

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // ลบ sheet เริ่มต้น

        $sheetIndex = 0;
        foreach ($tables as $table) {
            $tableName = $table->$key;

            // ดึงข้อมูลจากตาราง
            $rows = DB::table($tableName)->get();

            // สร้าง sheet ใหม่
            $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $this->sanitizeSheetName($tableName));
            $spreadsheet->addSheet($sheet, $sheetIndex);

            if ($rows->count() > 0) {
                // ดึง column headers จาก row แรก
                $columns = array_keys((array) $rows->first());

                // เขียน header row
                $colIndex = 1;
                foreach ($columns as $column) {
                    $sheet->setCellValue([$colIndex, 1], $column);
                    $colIndex++;
                }

                // Style header
                $lastCol = count($columns);
                $headerRange = 'A1:' . $this->getColumnLetter($lastCol) . '1';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // เขียนข้อมูล
                $rowIndex = 2;
                foreach ($rows as $row) {
                    $colIndex = 1;
                    foreach ((array) $row as $value) {
                        $sheet->setCellValue([$colIndex, $rowIndex], $value);
                        $colIndex++;
                    }
                    $rowIndex++;
                }

                // Auto-size columns
                foreach (range(1, $lastCol) as $col) {
                    $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
                }

                // Style data area
                if ($rowIndex > 2) {
                    $dataRange = 'A2:' . $this->getColumnLetter($lastCol) . ($rowIndex - 1);
                    $sheet->getStyle($dataRange)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E7EB']],
                        ],
                    ]);
                }
            } else {
                // ตารางว่าง - ใส่ข้อความแจ้ง
                $sheet->setCellValue('A1', 'ไม่มีข้อมูล');
            }

            $sheetIndex++;
        }

        // ตั้งค่า sheet แรกเป็น active
        if ($spreadsheet->getSheetCount() > 0) {
            $spreadsheet->setActiveSheetIndex(0);
        }

        // Export เป็น string
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return $content;
    }

    /**
     * แปลงตัวเลข column เป็นตัวอักษร (1=A, 2=B, ...)
     */
    private function getColumnLetter(int $column): string
    {
        $letter = '';
        while ($column > 0) {
            $column--;
            $letter = chr(65 + ($column % 26)) . $letter;
            $column = intval($column / 26);
        }
        return $letter;
    }

    /**
     * ทำให้ชื่อ sheet ปลอดภัย (ไม่เกิน 31 ตัวอักษร, ไม่มีอักขระพิเศษ)
     */
    private function sanitizeSheetName(string $name): string
    {
        // ลบอักขระที่ไม่อนุญาต
        $name = preg_replace('/[\[\]\*\?\/\\\\:]/', '', $name);
        // จำกัดความยาว
        return mb_substr($name, 0, 31);
    }
}
