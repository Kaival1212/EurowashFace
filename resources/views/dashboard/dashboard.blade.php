@extends('dashboard.layouts.dashboard') @section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Dashboard</h1>
        <div class="text-sm text-gray-500">
            Last updated: {{ now()->format('M d, Y H:i') }}
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <i class="fas fa-camera text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-600 text-sm">Total Detections</h2>
                    <p class="text-2xl font-semibold">{{ $totalFaces }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-500">
                    <i class="fas fa-mask text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-600 text-sm">Covered Faces</h2>
                    <p class="text-2xl font-semibold">{{ $coveredFaces }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <i class="fas fa-smile text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-600 text-sm">Uncovered Faces</h2>
                    <p class="text-2xl font-semibold">{{ $uncoveredFaces }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Daily Stats Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Daily Statistics</h2>
        <canvas id="dailyStatsChart" height="100"></canvas>
    </div>
    <!-- Recent Detections -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Detections</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Time
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Image
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentFaces as $face)
                        <tr>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ $face->created_at->format('M d, Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $face->face_covered ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}"
                                    >{{ $face->face_covered ? 'Covered' : 'Uncovered' }}</span
                                >
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img
                                    src="{{ asset('storage/' . $face->image_path) }}"
                                    alt="Face"
                                    class="h-12 w-12 rounded-lg object-cover"
                                />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('dailyStatsChart').getContext('2d');
        const dailyStats = @json($dailyStats);
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyStats.map(stat => stat.date),
                datasets: [
                    {
                        label: 'Total Detections',
                        data: dailyStats.map(stat => stat.total),
                        borderColor: 'rgb(59, 130, 246)',
                        tension: 0.1
                    },
                    {
                        label: 'Covered Faces',
                        data: dailyStats.map(stat => stat.covered),
                        borderColor: 'rgb(239, 68, 68)',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
