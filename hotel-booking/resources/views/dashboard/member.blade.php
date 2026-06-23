<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-200 text-center max-w-lg w-full space-y-6">
        <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto text-2xl">
            👤
        </div>
        
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Member Dashboard Area</h1>
            <p class="text-gray-500 mt-2 text-sm">Welcome back! You are logged in as a customer.</p>
        </div>

        <div class="bg-gray-50 rounded-xl p-6 border border-dashed border-gray-300">
            <p class="text-gray-600 font-medium">🚧 Under Construction</p>
            <p class="text-sm text-gray-400 mt-1">This layout is ready for UI/UX development.</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-gray-900 text-white rounded-lg px-4 py-3 text-sm font-semibold hover:bg-gray-800 transition">
                Log Out
            </button>
        </form>
    </div>

</body>
</html>