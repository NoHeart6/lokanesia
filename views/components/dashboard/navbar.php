<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
?>
<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="text-2xl font-bold text-blue-600">Lokanesia</a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="/dashboard" 
                       class="<?= $_SERVER['REQUEST_URI'] === '/dashboard' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="/dashboard/reviews" 
                       class="<?= $_SERVER['REQUEST_URI'] === '/dashboard/reviews' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Ulasan
                    </a>
                    <a href="/dashboard/itineraries" 
                       class="<?= $_SERVER['REQUEST_URI'] === '/dashboard/itineraries' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Rencana Perjalanan
                    </a>
                </div>
            </div>

            <!-- Right side -->
            <div class="flex items-center">
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <!-- Profile dropdown -->
                    <div class="ml-3 relative">
                        <div class="flex items-center space-x-4">
                            <a href="/dashboard/profile" 
                               class="<?= $_SERVER['REQUEST_URI'] === '/dashboard/profile' ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' ?> text-sm font-medium">
                                <?= htmlspecialchars($_SESSION['user_name'] ?? 'Profile') ?>
                            </a>
                            <a href="/logout" 
                               class="text-sm font-medium text-gray-600 hover:text-gray-900">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button type="button" 
                            onclick="toggleMobileMenu()"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="sm:hidden hidden" id="mobileMenu">
        <div class="pt-2 pb-3 space-y-1">
            <a href="/dashboard" 
               class="<?= $_SERVER['REQUEST_URI'] === '/dashboard' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Dashboard
            </a>
            <a href="/dashboard/reviews" 
               class="<?= $_SERVER['REQUEST_URI'] === '/dashboard/reviews' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Ulasan
            </a>
            <a href="/dashboard/itineraries" 
               class="<?= $_SERVER['REQUEST_URI'] === '/dashboard/itineraries' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Rencana Perjalanan
            </a>
            <a href="/dashboard/profile" 
               class="<?= $_SERVER['REQUEST_URI'] === '/dashboard/profile' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Profile
            </a>
            <a href="/logout" 
               class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Logout
            </a>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('hidden');
}
</script> 