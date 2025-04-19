<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use App\Models\privasi\Student;

class StudentController
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'image_profile' => 'nullable|string',
        'roll_number' => 'required|string', 
        'users_id' => 'required|uuid|exists:users,id',
        'majors_id' => 'required|exists:majors,id',
        'pregister_id' => 'required|exists:pregister_schools,id',
    ]);

    $kelas10 = Classes::whereHas('mainClass', function ($q) {
        $q->where('class', '10');
    })->get();

    $filtered = $kelas10->filter(function ($kelas) {
        return $kelas->students()->count() < 30;
    });

    if ($filtered->isEmpty()) {
        return response()->json(['message' => 'Semua kelas 10 penuh'], 400);
    }

    $selectedClass = $filtered->random();

    $noAbsen = $selectedClass->students()->count() + 1;

    $student = Student::create([
        'name' => $validated['name'],
        'image_profile' => $validated['image_profile'] ?? null,
        'roll_number' => $validated['roll_number'],
        'status_at-leatest' => 'offline',
        'class_id' => $selectedClass->id,
        'majors_id' => $validated['majors_id'],
        'users_id' => $validated['users_id'],
        'pregister_id' => $validated['pregister_id'],
        'no_absen' => $noAbsen,
    ]);

    return response()->json([
        'message' => 'Siswa berhasil didaftarkan ke kelas acak tingkat 10',
        'student' => $student
    ]);
}

}
