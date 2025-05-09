@extends('dashboard.layouts.dashboard') @section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Statistics</h1>
        <div class="text-sm text-gray-500">
            Last updated: {{ now()->format('M d, Y H:i') }}
        </div>
    </div>

    <!-- Hourly Statistics -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Hourly Statistics</h2>
        <canvas id="hourlyStatsChart" height="100"></canvas>
    </div>

    <!-- Monthly Statistics -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Monthly Statistics</h2>
        <canvas id="monthlyStatsChart" height="100"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hourlyStats = @json($hourlyStats);
        const monthlyStats = @json($monthlyStats);

        // Hourly Chart
        new Chart(document.getElementById('hourlyStatsChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: hourlyStats.map(stat => `${stat.hour}:00`),
                datasets: [
                    {
                        label: 'Total Detections',
                        data: hourlyStats.map(stat => stat.total),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    },
                    {
                        label: 'Covered Faces',
                        data: hourlyStats.map(stat => stat.covered),
                        backgroundColor: 'rgba(239, 68, 68, 0.5)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
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

        // Monthly Chart
        new Chart(document.getElementById('monthlyStatsChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: monthlyStats.map(stat => stat.month),
                datasets: [
                    {
                        label: 'Total Detections',
                        data: monthlyStats.map(stat => stat.total),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    },
                    {
                        label: 'Covered Faces',
                        data: monthlyStats.map(stat => stat.covered),
                        backgroundColor: 'rgba(239, 68, 68, 0.5)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
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
