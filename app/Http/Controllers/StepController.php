<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StepController extends Controller
{

    public function index(Goal $goal)
    {
        // Check if the goal belongs to the authenticated user
        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $steps = $goal->steps()->orderBy('created_at')->get();

        return response()->json([
            'steps' => $steps
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'goal_id' => 'required|exists:goals,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Check if the goal belongs to the authenticated user
        $goal = Goal::findOrFail($request->goal_id);
        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Create the step
        $step = Step::create([
            'goal_id' => $request->goal_id,
            'title' => $request->title,
        ]);

        // Update the goal's progress
        //$goal->updateProgress();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Step created successfully',
                'step' => $step
            ], 201);
        }

        return redirect()->back()->with('success', 'Step added successfully!');
    }


    public function show(Step $step)
    {
        // Check if the step's goal belongs to the authenticated user
        if ($step->goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return response()->json([
            'step' => $step
        ]);
    }


    public function update(Request $request, Step $step)
    {
        // Check if the step's goal belongs to the authenticated user
        if ($step->goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $step->update([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Step updated successfully',
                'step' => $step
            ]);
        }

        return redirect()->back()->with('success', 'Step updated successfully!');
    }


    public function toggle(Step $step)
    {
        // Check if the step's goal belongs to the authenticated user
        if ($step->goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $step->update([
            'is_completed' => !$step->is_completed,
        ]);

        // Update the goal's progress
        //$step->goal->updateProgress();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Step status updated',
                'step' => $step
            ]);
        }

        return redirect()->back()->with('success', 'Step status updated!');
    }


    public function destroy(Step $step)
    {
        // Check if the step's goal belongs to the authenticated user
        if ($step->goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $goal = $step->goal;
        $step->delete();

        // Update the goal's progress
        //$goal->updateProgress();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Step deleted successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Step deleted successfully!');
    }


}
