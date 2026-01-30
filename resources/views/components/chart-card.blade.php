@props(['title', 'subtitle' => null, 'canvasId', 'height' => 'h-72'])

{{-- 
    Props:
    - title : Judul chart
    - subtitle : Subtitle/deskripsi (opsional)
    - canvasId : ID untuk canvas element (wajib)
    - height : Tinggi container chart (default: h-72)
--}}

<div class="rounded-xl bg-white p-6 shadow-lg">
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-gray-800">{{ $title }}</h2>
        @if ($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="{{ $height }} w-full">
        <canvas id="{{ $canvasId }}"></canvas>
    </div>
</div>
