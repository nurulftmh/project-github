<!-- resources/views/components/guest-layout.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Guest Layout' }}</title>
    <!-- Tambahkan CSS atau asset lainnya di sini -->
</head>
<body>
    {{ $slot }}
</body>
</html>
