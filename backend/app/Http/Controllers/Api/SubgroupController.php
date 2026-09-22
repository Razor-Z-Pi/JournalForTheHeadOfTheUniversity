<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subgroup;
use Illuminate\Http\Request;

class SubgroupController extends Controller
{
    public function index(Request $request)
    {
        $query = Subgroup::with('group');
        if ($request -> has('group_id')) {
            $query -> where('group_id', $request->group_id);
        }
        return response() -> json($query -> orderBy('name') -> get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:100',
        ]);
        $subgroup = Subgroup::create($validated);
        return response() -> json($subgroup, 201);
    }

    public function show(string $id)
    {
        return response() -> json(Subgroup::with('students') -> findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $subgroup = Subgroup::findOrFail($id);
        $subgroup -> update($request -> only('name', 'group_id'));
        return response() -> json($subgroup);
    }

    public function destroy(string $id)
    {
        Subgroup::findOrFail($id) -> delete();
        return response() -> json(['message' => 'Подгруппа удалена!!!']);
    }
}