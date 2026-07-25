@if (file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}?t={{ time() }}">
    <script type="module" src="{{ Vite::asset('resources/js/app.js') }}?t={{ time() }}"></script>
@endif
