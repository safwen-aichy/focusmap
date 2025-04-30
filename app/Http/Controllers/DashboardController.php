<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\MotivationEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all goals for the authenticated user
        $goals = Goal::where('user_id', auth()->id())->get();

        // Separate goals into active, completed, and upcoming
        $activeGoals = $goals->where('status', 'active');
        $completedGoals = $goals->where('status', 'completed');
        $upcomingGoals = $goals->where('status', 'upcoming');

        // Calculate percentages for active and upcoming goals
        $totalGoals = $goals->count();
        $activeGoalsPercentage = $totalGoals > 0 ? ($activeGoals->count() / $totalGoals) * 100 : 0;
        $upcomingGoalsPercentage = $totalGoals > 0 ? ($upcomingGoals->count() / $totalGoals) * 100 : 0;

        // Get motivation entries for the authenticated user
        $motivationEntries = MotivationEntry::where('user_id', auth()->id())->get();

        // Calculate weekly average motivation
        $weeklyMotivationAverage = $motivationEntries
            ->where('created_at', '>=', Carbon::now()->subWeek())
            ->avg('rating');

        // Prepare data for the motivation trend chart
        $motivationTrend = $motivationEntries->sortBy('created_at');
        $motivationDates = $motivationTrend->pluck('created_at')->map(function ($date) {
            return $date->format('Y-m-d');
        });
        $motivationValues = $motivationTrend->pluck('rating');

        return view('dashboard', compact(
            'goals', 
            'activeGoals', 
            'completedGoals', 
            'upcomingGoals',
            'activeGoalsPercentage',
            'upcomingGoalsPercentage',
            'motivationEntries',
            'weeklyMotivationAverage',
            'motivationTrend',
            'motivationDates',
            'motivationValues'
        ));
    }
}
