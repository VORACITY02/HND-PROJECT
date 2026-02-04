<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Auth' }} - Internship Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">

    <div class="fixed top-4 right-4">
    <button type="button" data-theme-toggle class="bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 px-4 py-2 rounded-lg font-semibold transition-all shadow-sm" title="Toggle theme">
        <span data-theme-label>Theme</span>
    </button>
</div>

@yield('content')

</body>
</html>