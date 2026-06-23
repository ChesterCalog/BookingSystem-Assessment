<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Administration Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-6">

    <div class="bg-slate-800 p-10 rounded-2xl shadow-xl border border-slate-700 text-center max-w-lg w-full space-y-6">
        <div class="w-16 h-16 bg-amber-900/50 text-amber-500 rounded-full flex items-center justify-center mx-auto text-2xl border border-amber-800">
            🛡️
        </div>
        
        <div>
            <h1 class="text-2xl font-bold text-white">Staff Admin Portal</h1>
            <p class="text-slate-400 mt-2 text-sm">System Access Granted. Management layout pending.</p>
        </div>

        <div class="bg-slate-900/50 rounded-xl p-6 border border-dashed border-slate-700">
            <p class="text-slate-300 font-medium">🚧 Dashboard Placeholder</p>
            <p class="text-sm text-slate-500 mt-1">Ready for backend data integration.</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-amber-700 text-white rounded-lg px-4 py-3 text-sm font-semibold hover:bg-amber-600 transition">
                End Shift (Log Out)
            </button>
        </form>
    </div>

</body>
</html>