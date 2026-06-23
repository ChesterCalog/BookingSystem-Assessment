<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Portal | Grand Horizon Resort</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@200..800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FAF9F5] text-stone-800 antialiased min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <span class="text-3xl block mb-2">💼</span>
        <h2 class="serif mt-2 text-center text-3xl font-bold tracking-tight text-stone-900">Staff Portal Access</h2>
        <p class="mt-2 text-center text-sm text-stone-500 font-light">
            Authorized personnel only.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-10 px-4 shadow-sm border border-stone-200/60 sm:rounded-3xl sm:px-10">
            
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-emerald-600 bg-emerald-50 p-3 rounded-xl border border-emerald-100">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('staff.login.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold tracking-widest uppercase text-stone-600">Employee Email</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="block w-full rounded-xl border-0 py-3 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 placeholder:text-stone-400 focus:ring-2 focus:ring-inset focus:ring-amber-800 sm:text-sm sm:leading-6 px-4">
                        @if ($errors->has('email'))
                            <p class="mt-2 text-red-600 text-xs font-medium">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold tracking-widest uppercase text-stone-600">Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" required class="block w-full rounded-xl border-0 py-3 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 placeholder:text-stone-400 focus:ring-2 focus:ring-inset focus:ring-amber-800 sm:text-sm sm:leading-6 px-4">
                        @if ($errors->has('password'))
                            <p class="mt-2 text-red-600 text-xs font-medium">{{ $errors->first('password') }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded border-stone-300 text-amber-800 focus:ring-amber-800">
                        <label for="remember_me" class="ml-2 block text-sm text-stone-600 font-light">Remember me</label>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-amber-700 hover:text-amber-800 transition">Forgot password?</a>
                        </div>
                    @endif
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center bg-stone-900 text-white px-4 py-3 rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-amber-800 transition shadow-md">
                        Authenticate System
                    </button>
                </div>
                
                <div class="pt-4 text-center border-t border-stone-100">
                    <a href="{{ route('home') }}" class="text-xs text-stone-400 hover:text-stone-600 transition font-medium">← Return to Resort Homepage</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
