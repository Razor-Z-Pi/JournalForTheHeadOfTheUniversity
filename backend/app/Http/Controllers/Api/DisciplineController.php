<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    public function index()
    {
        return response() -> json(
            Discipline::orderBy('name') -> get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request -> validate([
            'name' => 'required|string|max:255',
            'teacher_name' => 'nullable|string|max:255',
        ]);

        $discipline = Discipline::create($validated);

        return response() -> json($discipline, 201);
    }

    public function show(string $id)
    {
        return response() -> json(Discipline::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $discipline = Discipline::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'teacher_name' => 'nullable|string|max:255',
        ]);

        $discipline -> update($validated);

        return response() -> json($discipline);
    }

    public function destroy(string $id)
    {
        Discipline::findOrFail($id) -> delete();

        return response() -> json(['message' => 'Дисциплина удалена!!!']);
    }
}