<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function saveMultipleRecords(Request $request)
    {
        $tableName = $this->getTableName($request->input('table'));
        $records = $request->input('data');

        foreach ($records as $data) {
            unset($data['showPassword']);
            DB::table($tableName)->updateOrInsert(['id' => $data['id']], $data);
        }
        return response()->json(['status' => 'success']);
    }

    // FUNGSI BARU KHUSUS UNTUK IMPORT EXCEL
    public function saveBatchRecords(Request $request)
    {
        $tableName = $this->getTableName($request->input('table'));
        $records = $request->input('data');

        foreach ($records as $data) {
            unset($data['showPassword']);
            DB::table($tableName)->updateOrInsert(['id' => $data['id']], $data);
        }
        return response()->json(['status' => 'success']);
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

        DB::table($tableName)->truncate();
        
        $cleanRecords = array_map(function($item) {
            unset($item['showPassword']);
            return $item;
        }, $records);

        DB::table($tableName)->insert($cleanRecords);
        return response()->json(['status' => 'success']);
    }
}