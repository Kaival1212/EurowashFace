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
    <body class="bg-gray-100">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <div
                class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out"
            >
                <a href="#" class="text-white flex items-center space-x-2 px-4">
                    <i class="fas fa-camera text-2xl"></i>
                    <span class="text-2xl font-extrabold">Face Detection</span>
                </a>

                <nav>
                    <a
                        href="{{ route('dashboard.dashboard') }}"
                        class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('dashboard.dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}"
                    >
                        <i class="fas fa-chart-line mr-2"></i>Dashboard
                    </a>
                    <a
                        href="{{ route('dashboard.faces') }}"
                        class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('dashboard.faces') ? 'bg-gray-900' : 'hover:bg-gray-700' }}"
                    >
                        <i class="fas fa-images mr-2"></i>Face Logs
                    </a>
                    <a
                        href="{{ route('dashboard.statistics') }}"
                        class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('dashboard.statistics') ? 'bg-gray-900' : 'hover:bg-gray-700' }}"
                    >
                        <i class="fas fa-chart-bar mr-2"></i>Statistics
                    </a>
                </nav>
            </div>

            <!-- Content -->
            <div class="flex-1">
                <div class="p-8">@yield('content')</div>
            </div>
        </div>

        <script>
            // Mobile menu toggle
            document.addEventListener("DOMContentLoaded", function () {
                const menuButton = document.querySelector("[data-menu-button]");
                const sidebar = document.querySelector("[data-sidebar]");

                if (menuButton && sidebar) {
                    menuButton.addEventListener("click", () => {
                        sidebar.classList.toggle("-translate-x-full");
                    });
                }
            });
        </script>
    </body>
</html>
