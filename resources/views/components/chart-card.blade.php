@props(['canvasId', 'height' => 'h-72', 'scrollable' => false])

{{-- 
    Props:
    - canvasId : ID untuk canvas element (wajib)
    - height : Tinggi container chart (default: h-72)
    - scrollable : Apakah chart bisa di-scroll horizontal (default: false)
--}}

<div
    class="{{ $scrollable ? 'overflow-x-auto' : 'min-h-[300px] h-full' }} max-h-[340px] w-full rounded-xl bg-white p-6 shadow-lg">
    <div class="{{ $height }} {{ $scrollable ? 'min-w-[600px]' : '' }} relative w-full">
        {{-- Loading Skeleton --}}
        <div id="{{ $canvasId }}-loading"
            class="chart-loading absolute inset-0 flex items-center justify-center">
            <div class="text-center">
                <div
                    class="mb-4 inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-blue-500 border-r-transparent">
                </div>
                <p class="text-sm text-gray-500">Memuat data...</p>
            </div>
        </div>

        {{-- Canvas Chart (hidden saat loading) --}}
        <canvas id="{{ $canvasId }}"
            class="hidden"></canvas>
    </div>
</div>
