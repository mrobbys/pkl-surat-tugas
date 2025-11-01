<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" href="{{ asset("assets/logo-bjb.png") }}" type="image/x-icon" />
        <title>{{ $title }} | Surat Tugas Lapangan</title>

        {{-- css & js --}}
        @vite(["resources/css/app.css", "resources/js/app.js"])

        {{-- datatables --}}
        <link
            rel="stylesheet"
            href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css"
        />

        {{-- ckeditor --}}
        <script src="{{ asset("ckeditor/ckeditor.js") }}"></script>
        
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
        @stack("styles")
    </head>

    <body class="overflow-x-hidden">
        <div x-data="{ sidebarIsOpen: false }" class="relative flex w-full flex-col lg:flex-row">
            <!-- This allows screen readers to skip the sidebar and go directly to the main content. -->
            <a class="sr-only" href="#main-content">skip to the main content</a>

            {{-- Dark overlay for when the sidebar is open on smaller screens --}}
            <div
                x-cloak
                x-show="sidebarIsOpen"
                class="fixed inset-0 z-20 bg-neutral-950/10 backdrop-blur-xs lg:hidden"
                x-on:click="sidebarIsOpen = false"
                x-transition.opacity
            ></div>

            {{-- Sidebar --}}
            <x-sidebar />

            {{-- Top navbar & main content --}}
            <div class="h-dvh w-full overflow-y-auto bg-[#edf2f9]">
                <div class="absolute min-h-75 w-full bg-[#009BDE] pointer-events-none"></div>
                {{-- Top navbar --}}
                <x-top-navbar :title="$title" />

                {{-- Main content --}}
                <div id="main-content" class="my-10">
                    <div class="overflow-y-auto px-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Scripts --}}

        {{-- jquery for datatables --}}
        <script
            src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"
        ></script>
        {{-- datatables --}}
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if (session()->has('alert'))
                    Swal.fire({
                        title: '{{ e(session('alert.title')) }}',
                        text: '{{ e(session('alert.text')) }}',
                        icon: '{{ e(session('alert.icon')) }}',
                        timer: 5000,
                    })
                @endif
            });
        </script>
        @stack("scripts")
    </body>
</html>
