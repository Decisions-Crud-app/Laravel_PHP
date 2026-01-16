<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Items CRUD App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
</head>
<body class="bg-light">

<header class="bg-dark text-white p-3">
    <h1 class="text-center">Items CRUD App</h1>
</header>


<div id="app"></div>

<footer class="bg-light text-center p-3 mt-4">
    © 2026 Items Management System
</footer>

</body>
</html>

