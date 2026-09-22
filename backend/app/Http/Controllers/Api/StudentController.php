<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Список всех студентов
     */
    public function index()
    {
        return response() -> json(
            Student::with(['group', 'subgroup'])
                -> orderBy('full_name')
                -> get()
        );
    }

    /**
     * Создать нового студента
     */
    public function store(Request $request)
    {
        $validated = $request -> validate([
            'full_name' => 'required|string|max:255',
            'group_id' => 'nullable|exists:groups,id',
            'subgroup_id' => 'nullable|exists:subgroups,id',
        ]);
        $student = Student::create($validated);
        return response() -> json($student -> load(['group', 'subgroup']), 201);
    }

    /**
     * Показать одного студента
     */
    public function show(string $id)
    {
        return response() -> json(Student::findOrFail($id));
    }

    /**
     * Обновить студента
     */
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);
        $student -> update($request -> only('full_name', 'is_active', 'group_id', 'subgroup_id'));
        return response() -> json($student -> load(['group', 'subgroup']));
    }

    /**
     * Удалить студента
     */
    public function destroy(string $id)
    {
        Student::findOrFail($id) -> delete();

        return response() -> json(['message' => 'Студент удалён!!!']);
    }
}