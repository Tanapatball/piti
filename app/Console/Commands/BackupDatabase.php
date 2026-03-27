<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'สำรองฐานข้อมูลอัตโนมัติ';

    public function handle(): int
    {
        $this->info('เริ่มสำรองฐานข้อมูล...');

        try {
            $disk = Storage::disk('backups');
            $backupName = config('backup.backup.name', 'Laravel');
            $filename = date('Y-m-d-H-i-s') . '-db.sql';
            $path = $backupName . '/' . $filename;

            // สร้างโฟลเดอร์ถ้ายังไม่มี
            if (!$disk->exists($backupName)) {
                $disk->makeDirectory($backupName);
            }

            // Export database
            $sql = $this->exportDatabase();

            // บันทึกไฟล์
            $disk->put($path, $sql);

            // ลบไฟล์เก่าเกิน 30 วัน
            $this->cleanOldBackups($disk, $backupName, 30);

            $this->info('สำรองฐานข้อมูลสำเร็จ: ' . $filename);

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('เกิดข้อผิดพลาด: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

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

    private function cleanOldBackups($disk, string $backupName, int $days): void
    {
        $files = $disk->files($backupName);
        $cutoff = now()->subDays($days)->timestamp;

        foreach ($files as $file) {
            if ($disk->lastModified($file) < $cutoff) {
                $disk->delete($file);
                $this->info('ลบไฟล์เก่า: ' . basename($file));
            }
        }
    }
}
