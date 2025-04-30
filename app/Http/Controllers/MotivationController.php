<?php

namespace App\Http\Controllers;

use App\Models\MotivationEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MotivationController extends Controller
{
    /**
     * Store a newly created motivation entry in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:500',
        ]);

        $motivationEntry = MotivationEntry::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'notes' => $request->notes,
            'date' => Carbon::today(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Motivation entry logged successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entries = MotivationEntry::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('motivation.index', compact('entries'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MotivationEntry  $motivationEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(MotivationEntry $motivation)
    {
        // Check if the entry belongs to the authenticated user
        if ($motivation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('motivation.edit', compact('motivation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MotivationEntry  $motivationEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MotivationEntry $motivation)
    {
        // Check if the entry belongs to the authenticated user
        if ($motivation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string|max:500',
        ]);

        $motivation->update([
            'rating' => $request->rating,
            'notes' => $request->notes,
        ]);

        return redirect()->route('motivation.index')
            ->with('success', 'Motivation entry updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MotivationEntry  $motivationEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(MotivationEntry $motivation)
    {
        // Check if the entry belongs to the authenticated user
        if ($motivation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $motivation->delete();

        return redirect()->route('motivation.index')
            ->with('success', 'Motivation entry deleted successfully!');
    }
}
