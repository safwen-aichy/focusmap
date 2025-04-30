<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index()
    {
        $goals = auth()->user()->goals()->latest()->get();
        return view('goals.index', compact('goals'));
    }

    public function create()
    {
        return view('goals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location_latitude' => 'nullable|string|max:255',
            'location_longitude' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
            'visibility' => 'required|in:private,public,friends',
            'status' => 'nullable|in:active,upcoming,completed',
        ]);

        $validated['status'] = 'active';

        auth()->user()->goals()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Goal created successfully.');
    }

    public function edit(Goal $goal)
    {
        //$this->authorize('update', $goal);

        return view('goals.edit', compact('goal'));
    }

    public function update(Request $request, Goal $goal)
    {
        //$this->authorize('update', $goal);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'target_date' => 'nullable|date',
            'visibility' => 'required|in:private,public,friends',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $goal->update($validated);

        return redirect()->route('goals.edit', $goal->id)->with('success', 'Goal updated successfully.');
    }

    public function destroy(Goal $goal)
    {
        //$this->authorize('delete', $goal);

        $goal->delete();

        return redirect()->route('dashboard')->with('success', 'Goal deleted successfully.');
    }
}
