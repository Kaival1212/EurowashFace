<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Face Detection Dashboard</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div class="min-h-screen flex flex-col">
            <!-- Top Nav -->
            <header
                class="bg-white shadow flex items-center justify-between px-6 h-16"
            >
                <div class="flex items-center space-x-4">
                    <button
                        class="md:hidden text-gray-500 focus:outline-none"
                        id="sidebarToggle"
                    >
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="text-xl font-bold text-gray-800"
                        >Face Detection Dashboard</span
                    >
                </div>
                <div class="flex items-center space-x-4">
                    <span
                        class="text-gray-600 text-sm"
                        >{{ Auth::user()->name ?? 'User' }}</span
                    >
                    <div class="relative group">
                        <button class="focus:outline-none flex items-center">
                            <i
                                class="fas fa-user-circle text-2xl text-gray-500"
                            ></i>
                            <svg
                                class="ml-1 w-4 h-4 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>
                        <div
                            class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg py-2 z-20 hidden group-hover:block"
                        >
                            <a
                                href="/user/profile"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                >Profile</a
                            >
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
                                >
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            <div class="flex flex-1">
                <!-- Sidebar -->
                <aside
                    id="sidebar"
                    class="bg-gray-900 text-white w-64 space-y-6 py-7 px-2 hidden md:block transition-all duration-200"
                >
                    <nav class="space-y-2">
                        <a
                            href="{{ route('dashboard.dashboard') }}"
                            class="flex items-center px-4 py-2 rounded {{ request()->routeIs('dashboard.dashboard') ? 'bg-gray-800' : 'hover:bg-gray-800' }}"
                        >
                            <i class="fas fa-chart-line mr-3"></i> Dashboard
                        </a>
                        <a
                            href="{{ route('dashboard.faces') }}"
                            class="flex items-center px-4 py-2 rounded {{ request()->routeIs('dashboard.faces') ? 'bg-gray-800' : 'hover:bg-gray-800' }}"
                        >
                            <i class="fas fa-images mr-3"></i> Face Logs
                        </a>
                        <a
                            href="{{ route('dashboard.statistics') }}"
                            class="flex items-center px-4 py-2 rounded {{ request()->routeIs('dashboard.statistics') ? 'bg-gray-800' : 'hover:bg-gray-800' }}"
                        >
                            <i class="fas fa-chart-bar mr-3"></i> Statistics
                        </a>
                    </nav>
                </aside>
                <!-- Content -->
                <main class="flex-1 bg-gray-50 p-8 overflow-y-auto">
                    @yield('content')
                </main>
            </div>
        </div>
        <script>
            // Sidebar toggle for mobile
            document
                .getElementById("sidebarToggle")
                ?.addEventListener("click", function () {
                    const sidebar = document.getElementById("sidebar");
                    sidebar.classList.toggle("hidden");
                });
        </script>
    </body>
</html>
