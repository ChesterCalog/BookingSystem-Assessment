<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Member Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#fcfbf7] min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl border border-stone-200 shadow-xl space-y-5">
        <div class="text-center space-y-1">
            <span class="text-4xl">✨</span>
            <h1 class="text-2xl font-serif font-bold text-stone-800">Join Grand Horizon</h1>
            <p class="text-xs text-stone-400">Unlock members-only tier pricing and track reservations</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1">Full Name</label>
                <input type="text" name="name" :value="old('name')" required autofocus class="w-full p-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1">Email Address</label>
                <input type="email" name="email" :value="old('email')" required class="w-full p-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
                @if($errors->has('email'))
                    <span class="text-xs text-rose-600 block mt-1">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1">Password</label>
                <input type="password" name="password" required class="w-full p-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
                @if($errors->has('password'))
                    <span class="text-xs text-rose-600 block mt-1">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full p-2.5 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
            </div>

            <button type="submit" class="w-full bg-amber-800 text-white p-3 rounded-xl font-bold text-sm tracking-wide hover:bg-amber-900 transition shadow-md">
                Register Account
            </button>
        </form>

        <div class="text-center text-xs text-stone-400 border-t border-stone-100 pt-4">
            Already have an account? <a href="{{ route('login') }}" class="text-amber-800 font-semibold hover:underline">Log in</a>
        </div>
    </div>

</body>
</html>