<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/asap_logo.jpg.avif') }}">
   @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    ])
    <title>{{'Client Record Management'}}</title>
</head>
<body>
   
    <main>
        {{$slot}}
    </main>
</html>

