<?php

namespace App\Http\Controllers;

use App\Models\Face;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Device;
use Carbon\Carbon;



class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalFaces = Face::count();
        $coveredFaces = Face::where('face_covered', true)->count();
        $uncoveredFaces = Face::where('face_covered', false)->count();
        $devices = Device::all()->map(function ($device) {
            $device->is_online = $device->last_seen && Carbon::parse($device->last_seen)->gt(now()->subSeconds(60));
            return $device;
        });

        $recentFaces = Face::latest()->take(5)->get();

        $dailyStats = Face::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN face_covered = 1 THEN 1 ELSE 0 END) as covered')
        )
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->take(7)
        ->get();

        return view('dashboard.dashboard', compact(
            'totalFaces',
            'coveredFaces',
            'uncoveredFaces',
            'recentFaces',
            'dailyStats',
            'devices'
        ));
    }

    public function faces(Request $request)
    {
        $query = Face::query();

        if ($request->has('covered')) {
            $query->where('face_covered', $request->boolean('covered'));
        }

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $faces = $query->latest()->paginate(20);

        return view('dashboard.faces', compact('faces'));
    }

    public function statistics()
    {
        $hourlyStats = Face::select(
            DB::raw("strftime('%H', created_at) as hour"),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN face_covered = 1 THEN 1 ELSE 0 END) as covered')
        )
        ->groupBy('hour')
        ->orderBy('hour')
        ->get();

        $monthlyStats = Face::select(
            DB::raw("strftime('%Y-%m', created_at) as month"),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN face_covered = 1 THEN 1 ELSE 0 END) as covered')
        )
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get();

        return view('dashboard.statistics', compact('hourlyStats', 'monthlyStats'));
    }
}
