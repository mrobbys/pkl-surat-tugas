<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="shortcut icon" href="{{ asset("assets/logo-bjb.png") }}" type="image/x-icon" />
        <title>{{ $title }} | Surat Tugas Lapangan</title>

        {{-- css & js --}}
        @vite(["resources/css/app.css", "resources/js/app.js"])

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>

    <body class="overflow-x-hidden bg-[#edf2f9]">
        <div
            class="container mx-auto flex min-h-dvh w-full max-w-7xl items-center justify-center px-4"
        >
            {{ $slot }}
        </div>

        {{-- Scripts --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if (session()->has('alert'))
                    Swal.fire({
                        title: '{{ e(session('alert.title')) }}',
                        text: '{{ e(session('alert.text')) }}',
                        icon: '{{ e(session('alert.icon')) }}',
                        confirmButtonText: 'OK',
                    })
                @endif
            });
        </script>
        @stack("scripts")
    </body>
</html>
