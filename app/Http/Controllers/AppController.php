<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AppController extends Controller
{
    public function getInitialData()
    {
        return response()->json([
            'users' => DB::table('users')->get(),
            'teachers' => DB::table('master_guru')->get(),
            'classes' => DB::table('master_kelas')->get(),
            'subjects' => DB::table('master_mapel')->get(),
            'students' => DB::table('master_siswa')->get(),
            'schedules' => DB::table('schedules')->get(),
            'attendances' => DB::table('attendances')->get(),
            'studentAttendances' => DB::table('student_attendances')->get(),
            'teacherStudentAttendances' => DB::table('teacher_student_attendances')->get(),
        ]);
    }

    private function getTableName($frontendTableName) {
        $tables = [
            'USERS' => 'users',
            'TEACHERS' => 'master_guru',
            'CLASSES' => 'master_kelas',
            'SUBJECTS' => 'master_mapel',
            'STUDENTS' => 'master_siswa',
            'SCHEDULES' => 'schedules',
            'ATTENDANCES' => 'attendances',
            'STUDENT_ATTENDANCES' => 'student_attendances',
            'TEACHER_STUDENT_ATTENDANCES' => 'teacher_student_attendances'
        ];
        return $tables[$frontendTableName] ?? null;
    }

    public function saveRecord(Request $request)
    {
        $tableName = $this->getTableName($request->input('table'));
        $data = $request->input('data');
        unset($data['showPassword']);

        DB::table($tableName)->updateOrInsert(['id' => $data['id']], $data);
        return response()->json(['status' => 'success']);
    }

    // FUNGSI YANG DIPERBAIKI (Tahan Bentrokan & Sangat Cepat)
    public function saveMultipleRecords(Request $request)
    {
        $tableName = $this->getTableName($request->input('table'));
        $records = $request->input('data');

        if (empty($records)) {
            return response()->json(['status' => 'success']);
        }

        $cleanRecords = [];
        foreach ($records as $data) {
            unset($data['showPassword']);
            $cleanRecords[] = $data;
        }

        // Ambil nama kolom yang akan di-update (Kecuali ID)
        $columnsToUpdate = array_keys($cleanRecords[0]);
        $columnsToUpdate = array_diff($columnsToUpdate, ['id']);

        // Mulai Transaksi Database
        DB::beginTransaction();
        try {
            // Upsert: Simpan semua data massal hanya dengan 1 Query!
            DB::table($tableName)->upsert($cleanRecords, ['id'], $columnsToUpdate);
            
            DB::commit(); // Simpan permanen jika mulus
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada tabrakan, cegah data masuk setengah-setengah
            return response()->json(['status' => 'error', 'message' => 'Server sibuk, silakan coba lagi.'], 500);
        }
    }

    // FUNGSI IMPORT EXCEL YANG DIPERBAIKI
    public function saveBatchRecords(Request $request)
    {
        // Menggunakan logika canggih yang sama dengan saveMultipleRecords
        return $this->saveMultipleRecords($request);
    }

    public function deleteRecord(Request $request)
    {
        $tableName = $this->getTableName($request->input('table'));
        $id = $request->input('id');

        DB::table($tableName)->where('id', $id)->delete();
        return response()->json(['status' => 'success']);
    }

    public function saveDbTable(Request $request)
    {
        $tableName = $this->getTableName($request->input('table'));
        $records = $request->input('data');

        DB::beginTransaction();
        try {
            DB::table($tableName)->truncate();
            
            $cleanRecords = array_map(function($item) {
                unset($item['showPassword']);
                return $item;
            }, $records);

            // Jika array sangat besar, pecah per 500 baris agar RAM server aman
            foreach (array_chunk($cleanRecords, 500) as $chunk) {
                DB::table($tableName)->insert($chunk);
            }
            
            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error'], 500);
        }
    }
}