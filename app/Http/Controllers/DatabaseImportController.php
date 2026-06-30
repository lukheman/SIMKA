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
}
