<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
<div class="text-center px-4">
    <h1 class="text-9xl font-bold text-indigo-600">@yield('code')</h1>
    <p class="text-2xl font-medium mt-4 text-gray-800">@yield('message')</p>
    <p class="text-gray-500 mt-2 max-w-md mx-auto">
        @yield('description')
    </p>
    <a href="/" class="mt-8 inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition duration-200">
        Back to Safety
    </a>
</div>
</body>
</html>
