<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Portal Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#fcfbf7] min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl border border-stone-200 shadow-xl space-y-6">
        <div class="text-center space-y-2">
            <span class="text-4xl">🔑</span>
            <h1 class="text-2xl font-serif font-bold text-stone-800">Resort Management Portal</h1>
            <p class="text-sm text-stone-400">Authorized Personnel & Members Only</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1">Email Address</label>
                <input type="email" name="email" :value="old('email')" required autofocus class="w-full p-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
                @if($errors->has('email'))
                    <span class="text-xs text-rose-600 mt-1 block">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1">Password</label>
                <input type="password" name="password" required class="w-full p-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
                @if($errors->has('password'))
                    <span class="text-xs text-rose-600 mt-1 block">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="flex items-center justify-between text-xs font-medium text-stone-500 pt-1">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-stone-300 text-amber-800 focus:ring-amber-600">
                    <span>Keep me logged in</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="hover:text-amber-800 transition">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="w-full bg-stone-900 text-white p-3 rounded-xl font-bold text-sm tracking-wide hover:bg-amber-900 transition shadow-md pt-3">
                Access System
            </button>
        </form>

        <div class="text-center text-xs text-stone-400 border-t border-stone-100 pt-4">
            <a href="{{ route('home') }}" class="hover:underline">← Return to Public Booking Site</a>
        </div>
    </div>

</body>
</html>