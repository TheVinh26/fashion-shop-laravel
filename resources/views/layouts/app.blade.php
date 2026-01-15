{{-- @extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Home</h2>
@endsection --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shop Fashion')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    @include('components.navbar')
    
    {{-- @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border border-green-300">
            {{ session('success') }}
        </div>
    @endif --}}


    <main class="container mx-auto py-6">
        @yield('content')
    </main>

    @include('components.footer')

</body>
</html>
