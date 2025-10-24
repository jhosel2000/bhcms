<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BHCMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <div class="flex min-h-screen bg-gray-100">
                @auth
                    @if(auth()->user()->role === 'doctor')
                        <x-sidebar-doctor></x-sidebar-doctor>
                    @elseif(auth()->user()->role === 'patient')
                        <x-sidebar-patient></x-sidebar-patient>
                    @elseif(auth()->user()->role === 'bhw')
                        <x-sidebar-bhw></x-sidebar-bhw>
                    @elseif(auth()->user()->role === 'midwife')
                        <x-sidebar-midwife></x-sidebar-midwife>
                    @endif
                @endauth

                <div class="flex-1 p-6">
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <!-- Page Content -->

                    <main class="space-y-6">
                        @if (session('success'))
                            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(isset($slot))
                            {{ $slot }}
                        @else
                            @yield('content')
                        @endif
                    </main>

                </div>

            </div>
