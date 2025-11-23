<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
class DatabaseBackupController extends Controller
{
    private function getBackupDirectory()
    {
        $backupDir = storage_path('database_backups');
        
        if (!File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }
        
        return $backupDir;
    }

    private function getMysqlPath()
    {
        $possiblePaths = [
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.27-winx64\\bin',
            'C:\\laragon\\bin\\mysql\\mysql-5.7.24-winx64\\bin',
            'C:\\laragon\\bin\\mariadb\\mariadb-10.4.10-winx64\\bin',
            'C:\\xampp\\mysql\\bin',
            'C:\\wamp64\\bin\\mysql\\mysql8.0.27\\bin',
        ];

        exec('where mysqldump 2>&1', $output, $returnCode);
        if ($returnCode === 0 && !empty($output)) {
            return '';
        }

        foreach ($possiblePaths as $path) {
            if (file_exists($path . '\\mysqldump.exe')) {
                return $path;
            }
        }

        return null;
    }

    public function export()
    {
        try {
            $this->cleanupTempFiles();
            
            $backupDir = $this->getBackupDirectory();
            $fileName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupDir . DIRECTORY_SEPARATOR . $fileName;

            $dbHost = env('DB_HOST', '127.0.0.1');
            $dbPort = env('DB_PORT', '3306');
            $dbUser = env('DB_USERNAME', 'root');
            $dbPass = env('DB_PASSWORD', '');
            $dbName = env('DB_DATABASE');

            $mysqlPath = $this->getMysqlPath();
            
            if ($mysqlPath === null) {
                return back()->with('error', 'MySQL/MariaDB tidak ditemukan di sistem! Pastikan MySQL sudah terinstall di Laragon.');
            }

            if ($mysqlPath !== '') {
                $mysqldumpCmd = '"' . $mysqlPath . '\\mysqldump.exe"';
            } else {
                $mysqldumpCmd = 'mysqldump';
            }
            
            $passwordArg = '';
            if ($dbPass !== '') {
                $passwordArg = '-p' . escapeshellarg($dbPass);
            }
            
            $command = $mysqldumpCmd . ' -h ' . escapeshellarg($dbHost) . 
                       ' -P ' . escapeshellarg($dbPort) .
                       ' -u ' . escapeshellarg($dbUser) .
                       ' ' . $passwordArg .
                       ' --single-transaction --routines --triggers ' .
                       escapeshellarg($dbName) . 
                       ' > ' . escapeshellarg($filePath) . ' 2>&1';

            $output = [];
            $returnCode = null;
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $errorMsg = implode("\n", $output);
                
                if (strpos($errorMsg, 'Access denied') !== false) {
                    return back()->with('error', 'Username atau password MySQL salah! Periksa file .env');
                } elseif (strpos($errorMsg, 'Unknown database') !== false) {
                    return back()->with('error', 'Database tidak ditemukan! Periksa DB_DATABASE di .env');
                } elseif (strpos($errorMsg, "Can't connect") !== false) {
                    return back()->with('error', 'Tidak bisa connect ke MySQL! Pastikan service MySQL running.');
                } else {
                    return back()->with('error', 'Gagal export database: ' . $errorMsg);
                }
            }

            if (!file_exists($filePath) || filesize($filePath) == 0) {
                return back()->with('error', 'File backup gagal dibuat atau kosong.');
            }

            $downloadPath = $backupDir . DIRECTORY_SEPARATOR . 'download_' . $fileName;
            copy($filePath, $downloadPath);

            return response()->download($downloadPath, $fileName, [
                'Content-Type' => 'application/sql',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function exportAndDownload()
    {
        try {
            $this->cleanupTempFiles();
            
            $backupDir = $this->getBackupDirectory();
            $fileName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupDir . DIRECTORY_SEPARATOR . $fileName;

            $dbHost = env('DB_HOST', '127.0.0.1');
            $dbPort = env('DB_PORT', '3306');
            $dbUser = env('DB_USERNAME', 'root');
            $dbPass = env('DB_PASSWORD', '');
            $dbName = env('DB_DATABASE');

            $mysqlPath = $this->getMysqlPath();
            
            if ($mysqlPath === null) {
                return redirect()->route('db-backup.import-page')
                    ->with('error', 'MySQL/MariaDB tidak ditemukan di sistem!');
            }

            if ($mysqlPath !== '') {
                $mysqldumpCmd = '"' . $mysqlPath . '\\mysqldump.exe"';
            } else {
                $mysqldumpCmd = 'mysqldump';
            }
            
            $passwordArg = '';
            if ($dbPass !== '') {
                $passwordArg = '-p' . escapeshellarg($dbPass);
            }
            
            $command = $mysqldumpCmd . ' -h ' . escapeshellarg($dbHost) . 
                       ' -P ' . escapeshellarg($dbPort) .
                       ' -u ' . escapeshellarg($dbUser) .
                       ' ' . $passwordArg .
                       ' --single-transaction --routines --triggers ' .
                       escapeshellarg($dbName) . 
                       ' > ' . escapeshellarg($filePath) . ' 2>&1';

            $output = [];
            $returnCode = null;
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $errorMsg = implode("\n", $output);
                
                if (strpos($errorMsg, 'Access denied') !== false) {
                    return redirect()->route('db-backup.import-page')
                        ->with('error', 'Username atau password MySQL salah!');
                } elseif (strpos($errorMsg, 'Unknown database') !== false) {
                    return redirect()->route('db-backup.import-page')
                        ->with('error', 'Database tidak ditemukan!');
                } elseif (strpos($errorMsg, "Can't connect") !== false) {
                    return redirect()->route('db-backup.import-page')
                        ->with('error', 'Tidak bisa connect ke MySQL!');
                } else {
                    return redirect()->route('db-backup.import-page')
                        ->with('error', 'Gagal export database: ' . $errorMsg);
                }
            }

            if (!file_exists($filePath) || filesize($filePath) == 0) {
                return redirect()->route('db-backup.import-page')
                    ->with('error', 'File backup gagal dibuat atau kosong.');
            }

            $downloadUrl = route('db-backup.download', ['file' => urlencode($fileName)]);
            
            return redirect()->route('db-backup.import-page')
                ->with('success', 'Backup database berhasil dibuat: ' . $fileName)
                ->with('download_url', $downloadUrl);

        } catch (\Exception $e) {
            return redirect()->route('db-backup.import-page')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function importPage()
    {
        $backupDir = $this->getBackupDirectory();
        $backups = [];

        if (File::isDirectory($backupDir)) {
            $files = File::files($backupDir);
            
            foreach ($files as $file) {
                if (strpos($file->getFilename(), 'download_') === 0) {
                    continue;
                }
                
                if ($file->getExtension() === 'sql') {
                    $backups[] = [
                        'name' => $file->getFilename(),
                        'path' => $file->getPathname(),
                        'size' => $this->formatBytes($file->getSize()),
                        'size_raw' => $file->getSize(),
                        'date' => Carbon::createFromTimestamp($file->getMTime())
                            ->locale('id')
                            ->translatedFormat('d F Y H:i:s'),
                        'mtime' => $file->getMTime()
                    ];
                }
            }

            usort($backups, function($a, $b) {
                return $b['mtime'] - $a['mtime'];
            });
        }

        return view('database_backup.export-import', compact('backups'));
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'sql_file' => [
                    'required',
                    'file',
                    'max:102400',
                    function ($attribute, $value, $fail) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        if ($extension !== 'sql') {
                            $fail('File harus bertipe .sql (ekstensi: ' . $extension . ')');
                        }
                    }
                ]
            ], [
                'sql_file.required' => 'File SQL harus dipilih',
                'sql_file.file' => 'File tidak valid',
                'sql_file.max' => 'Ukuran file maksimal 100MB'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->with('error', 'Validasi gagal: ' . collect($e->errors())->flatten()->first());
        }

        try {
            $backupDir = $this->getBackupDirectory();
            $uploadedFile = $request->file('sql_file');

            $mysqlPath = $this->getMysqlPath();
            
            if ($mysqlPath === null) {
                return back()->with('error', 'MySQL/MariaDB tidak ditemukan! Pastikan sudah terinstall.');
            }

            $preImportBackup = 'backup_before_import_' . date('Y-m-d_H-i-s') . '.sql';
            $preImportPath = $backupDir . DIRECTORY_SEPARATOR . $preImportBackup;

            $dbHost = env('DB_HOST', '127.0.0.1');
            $dbPort = env('DB_PORT', '3306');
            $dbUser = env('DB_USERNAME', 'root');
            $dbPass = env('DB_PASSWORD', '');
            $dbName = env('DB_DATABASE');

            if ($mysqlPath !== '') {
                $mysqldumpCmd = '"' . $mysqlPath . '\\mysqldump.exe"';
            } else {
                $mysqldumpCmd = 'mysqldump';
            }
            
            $passwordArg = '';
            if ($dbPass !== '') {
                $passwordArg = '-p' . escapeshellarg($dbPass);
            }

            $backupCommand = $mysqldumpCmd . ' -h ' . escapeshellarg($dbHost) . 
                        ' -P ' . escapeshellarg($dbPort) .
                        ' -u ' . escapeshellarg($dbUser) .
                        ' ' . $passwordArg .
                        ' --single-transaction --routines --triggers ' .
                        escapeshellarg($dbName) . 
                        ' > ' . escapeshellarg($preImportPath) . ' 2>&1';

            $output = [];
            $returnCode = null;
            exec($backupCommand, $output, $returnCode);

            if ($returnCode !== 0) {
                $errorMsg = implode("\n", $output);
                return back()->with('error', 'Gagal backup database sebelum import: ' . $errorMsg);
            }

            $importFileName = 'import_' . date('Y-m-d_H-i-s') . '.sql';
            $importFilePath = $backupDir . DIRECTORY_SEPARATOR . $importFileName;
            $uploadedFile->move($backupDir, $importFileName);

            if ($mysqlPath !== '') {
                $mysqlCmd = '"' . $mysqlPath . '\\mysql.exe"';
            } else {
                $mysqlCmd = 'mysql';
            }
            
            $importCommand = $mysqlCmd . ' -h ' . escapeshellarg($dbHost) .
                        ' -P ' . escapeshellarg($dbPort) .
                        ' -u ' . escapeshellarg($dbUser) .
                        ' ' . $passwordArg . ' ' .
                        escapeshellarg($dbName) .
                        ' < ' . escapeshellarg($importFilePath) . ' 2>&1';

            $output = [];
            exec($importCommand, $output, $returnCode);

            if ($returnCode !== 0) {
                $errorMsg = implode("\n", $output);
                return back()->with('error', 'Gagal import database: ' . $errorMsg);
            }

            $message = 'Database berhasil diimport! Backup sebelum import: ' . $preImportBackup;

            return redirect()->route('penghuni.index')->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function download($file)
    {
        try {
            $backupDir = $this->getBackupDirectory();
            $filePath = $backupDir . DIRECTORY_SEPARATOR . basename($file);

            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan!');
            }

            if (realpath($filePath) === false || strpos(realpath($filePath), realpath($backupDir)) !== 0) {
                return back()->with('error', 'File tidak valid!');
            }

            return response()->download($filePath);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete($file)
    {
        try {
            $backupDir = $this->getBackupDirectory();
            $filePath = $backupDir . DIRECTORY_SEPARATOR . basename($file);

            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan!');
            }

            if (realpath($filePath) === false || strpos(realpath($filePath), realpath($backupDir)) !== 0) {
                return back()->with('error', 'File tidak valid!');
            }

            File::delete($filePath);

            return back()->with('success', 'File backup berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    private function cleanupTempFiles()
    {
        $backupDir = $this->getBackupDirectory();
        $files = File::files($backupDir);
        
        foreach ($files as $file) {
            if (strpos($file->getFilename(), 'download_') === 0) {
                if (time() - $file->getMTime() > 3600) {
                    File::delete($file->getPathname());
                }
            }
        }
    }
}