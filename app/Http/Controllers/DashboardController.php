<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ngitung Statistik Task
        $totalTasks = $user->planners()->count();
        $completedTasks = $user->planners()->where('status', 'completed')->count();
        $inProgressTasks = $user->planners()->where('status', 'pending')->count(); // <-- Ini In Progress

        // Ngitung Total File Hosted
        $filesHosted = Attachment::whereHas('planner', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        // Ambil Log Aktivitas (5 Terakhir)
        $recentActivities = $user->activityLogs()->latest()->take(5)->get();

        // Ambil Task yang mau Deadline
        $upcomingDeadlines = $user->planners()
                                  ->where('status', 'pending')
                                  ->whereNotNull('deadline')
                                  ->orderBy('deadline', 'asc')
                                  ->take(3)
                                  ->get();

        return view('dashboard', compact(
            'totalTasks', 'completedTasks', 'inProgressTasks', 'filesHosted', 
            'recentActivities', 'upcomingDeadlines'
        ));
    }
}