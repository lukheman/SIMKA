<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class DatabaseImportController extends Controller
{
    public function index()
    {
        return view('hidden-import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|mimes:sql,txt',
        ]);

        try {
            $file = $request->file('sql_file');
            $sql = file_get_contents($file->getRealPath());

            // Nonaktifkan foreign key checks agar proses import berjalan lancar
            DB::unprepared('SET FOREIGN_KEY_CHECKS=0;');
            DB::unprepared($sql);
            DB::unprepared('SET FOREIGN_KEY_CHECKS=1;');

            return back()->with('success', 'Database berhasil diimport!');
        } catch (Exception $e) {
            DB::unprepared('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function export()
    {
        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            
            $filename = 'backup_' . $database . '_' . date('Y_m_d_His') . '.sql';
            $filePath = storage_path('app/public/' . $filename);

            // Construct the mysqldump command secara cross-platform menggunakan escapeshellarg
            $passwordParam = empty($password) ? '' : '-p' . escapeshellarg($password);
            
            $command = sprintf(
                'mysqldump -u %s %s -h %s %s > %s',
                escapeshellarg($username),
                $passwordParam,
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($filePath)
            );

            // Untuk Windows environment (seperti XAMPP), jika mysqldump tidak ada di PATH, eksekusi mungkin gagal.
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                return back()->with('error', 'Gagal melakukan export database. Pastikan mysqldump tersedia di server/PATH sistem Anda (Return Code: ' . $returnVar . ').');
            }

            return response()->download($filePath)->deleteFileAfterSend(true);

        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage());
        }
    }
}
