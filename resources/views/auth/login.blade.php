<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-18 px-8 sm:px-3 lg:px-0">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-md w-full">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-6 rounded-t-2xl">
                <div class="text-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto h-16 w-auto mb-4 rounded-full" />
                    <h2 class="text-3xl font-extrabold text-white">Login</h2>
                    <p class="mt-2 text-gray-100">Barangay Health Center Monitoring Scheduling System</p>
                </div>
            </div>
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all duration-200" required autofocus autocomplete="username">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password" type="password" name="password" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all duration-200" required autocomplete="current-password">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 space-y-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="remember" class="text-gray-700">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 transform hover:scale-105">
                            Log in
                        </button>

                        <div class="text-center space-y-2">
                            <p class="text-sm text-green-600">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="font-medium text-gray-600 hover:text-gray-500 transition-colors duration-200">
                                        Forgot your password? Click Here
                                    </a>
                                @endif
                            </p>
                            <p class="text-sm text-green-600">
                                <a href="{{ url('/') }}" class="font-medium text-gray-600 hover:text-gray-500 transition-colors duration-200">
                                    &larr; Back to Welcome Page
                                </a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
