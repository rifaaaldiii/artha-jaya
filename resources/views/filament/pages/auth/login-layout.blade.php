<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PT. Artha Jaya Mas - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />
    
    @filamentStyles
    @vite('resources/css/app.css')
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    {{ $slot }}

    @filamentScripts
    @vite('resources/js/app.js')
    
    @stack('scripts')
</body>
</html>
