@extends('dashboard.layouts.dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">Face Detection Logs</h1>

        <!-- Filters -->
        <div class="flex space-x-4">
            <form action="{{ route('dashboard.faces') }}" method="GET" class="flex space-x-4">
                <select name="covered" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">All Status</option>
                    <option value="1" {{ request('covered') === '1' ? 'selected' : '' }}>Covered</option>
                    <option value="0" {{ request('covered') === '0' ? 'selected' : '' }}>Uncovered</option>
                </select>

                <input type="date" name="date" value="{{ request('date') }}"
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Faces Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eyes</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mouth</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Face</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frame</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($faces as $face)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $face->created_at->format('M d, Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $face->face_covered ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $face->face_covered ? 'Covered' : 'Uncovered' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($face->eyes_visible)
                                <i class="fas fa-eye text-green-500"></i>
                            @else
                                <i class="fas fa-eye-slash text-red-400"></i>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($face->mouth_visible)
                                <i class="fas fa-smile text-green-500"></i>
                            @else
                                <i class="fas fa-meh text-red-400"></i>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-xs">{{ number_format($face->confidence, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ asset('storage/' . $face->image_path) }}" alt="Face" class="h-12 w-12 rounded-lg object-cover">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($face->frame_path)
                                <img src="{{ asset('storage/' . $face->frame_path) }}" alt="Frame" class="h-12 w-20 rounded-lg object-cover">
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $faces->links() }}
        </div>
    </div>
</div>
@endsection
