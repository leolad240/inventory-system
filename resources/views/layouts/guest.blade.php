<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'InventoryPro') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Left: Branding -->
            <div class="hidden lg:flex lg:w-1/2 bg-gray-900 flex-col justify-between p-12">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">InventoryPro</span>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-white mb-4 leading-tight">
                        Manage your inventory<br>with confidence.
                    </h1>
                    <p class="text-gray-400 text-lg">Track stock, manage orders, and generate insightful reports — all in one place.</p>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    @foreach([['Products', 'Track every SKU'], ['Orders', 'Purchase & Sales'], ['Reports', 'Real-time analytics']] as $feat)
                        <div class="bg-gray-800 rounded-xl p-4">
                            <p class="text-white font-semibold text-sm">{{ $feat[0] }}</p>
                            <p class="text-gray-400 text-xs mt-1">{{ $feat[1] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Form -->
            <div class="flex-1 flex items-center justify-center p-8">
                <div class="w-full max-w-sm">
                    <div class="lg:hidden flex items-center gap-2 mb-8">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-900">InventoryPro</span>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
