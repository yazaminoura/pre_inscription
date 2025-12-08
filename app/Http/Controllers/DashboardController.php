<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\User;
use App\Models\Inscription;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
{
    // Basic stats
    $stats = [
        'total_formations' => Formation::count(),
        'total_admins' => User::count(),
        'total_inscriptions' => Inscription::count(),
    ];

    // Time-based calculations
    $today = Carbon::today();
    $lastWeek = Carbon::today()->subWeek();
    
    $stats['today_inscriptions'] = Inscription::whereDate('created_at', $today)->count();
    $stats['lastweek_inscriptions'] = Inscription::whereDate('created_at', '>=', $lastWeek)
                                                ->whereDate('created_at', '<', $today)
                                                ->count();

    // Recent data
    $recentFormations = Formation::withCount('inscriptions')
                               ->latest()
                               ->take(5)
                               ->get();

    $recentInscriptions = Inscription::with(['formation', 'candidat'])
                                   ->latest()
                                   ->take(5)
                                   ->get();

    return view('dashboard', compact(
        'stats',
        'recentFormations',
        'recentInscriptions'
    ));
}
}
