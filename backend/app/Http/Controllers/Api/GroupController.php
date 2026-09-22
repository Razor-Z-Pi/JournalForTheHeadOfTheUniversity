<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        // Возвращаем группы с подгруппами и количеством студентов
        return response() -> json(
            Group::with('subgroups') -> withCount('students') -> orderBy('name') ->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request -> validate([
            'name' => 'required|string|max:100|unique:groups,name',
        ]);
        $group = Group::create($validated);
        return response() -> json($group -> load('subgroups'), 201);
    }

    public function show(string $id)
    {
        return response() -> json(
            Group::with(['subgroups', 'students']) -> findOrFail($id)
        );
    }

    public function update(Request $request, string $id)
    {
        $group = Group::findOrFail($id);
        $group -> update($request -> only('name'));
        return response() -> json($group);
    }

    public function destroy(string $id)
    {
        Group::findOrFail($id) -> delete();
        return response() -> json(['message' => 'Группа удалена!!!']);
    }
}