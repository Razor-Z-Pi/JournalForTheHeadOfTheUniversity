<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\JournalEntry;
use Illuminate\Http\Request;

class JournalController extends Controller {
    public function index($disciplineId) {
        $students = Student::where('is_active', true) -> orderBy('full_name') -> get();
        $entries = JournalEntry::where('discipline_id', $disciplineId) -> get();
        return response() -> json(['students' => $students, 'entries' => $entries]);
    }

    public function updateCell(Request $request) {
        $validated = $request -> validate([
            'student_id' => 'required|exists:students,id',
            'discipline_id' => 'required|exists:disciplines,id',
            'week_number' => 'required|integer|min:1|max:18',
            'missed_hours' => 'nullable|integer',
            'grade' => 'nullable|string|max:10',
            'note' => 'nullable|string|max:255',
        ]);

        $entry = JournalEntry::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'discipline_id' => $validated['discipline_id'],
                'week_number' => $validated['week_number'],
            ],
            [
                'missed_hours' => $validated['missed_hours'] ?? 0,
                'grade' => $validated['grade'] ?? null,
                'note' => $validated['note'] ?? null,
            ]
        );
        return response() -> json(['entry' => $entry]);
    }
}