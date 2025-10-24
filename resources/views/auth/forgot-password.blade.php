<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-18 px-8 sm:px-3 lg:px-0">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-md w-full">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-6 rounded-t-2xl">
                <div class="text-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto h-16 w-auto mb-4" />
                    <h2 class="text-3xl font-extrabold text-white">Forgot Password</h2>
                    <p class="mt-2 text-gray-100">Barangay Health Center Monitoring Scheduling System</p>
                </div>
            </div>
            <div class="px-8 py-8">
                <p class="mb-6 text-gray-700">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <input id="email" type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all duration-200" required autofocus>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 transform hover:scale-105">
                        Email Password Reset Link
                    </button>

                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            Remember your password?
                            <a href="{{ route('login') }}" class="font-medium text-gray-600 hover:text-gray-500 transition-colors duration-200">
                                Back to Login
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
