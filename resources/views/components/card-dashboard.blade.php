@props([
    'title',
    'value',
    'icon',
    'iconBgClass' => '',
    'iconClass' => '',
])

{{-- 
    Props:
    - title : judul card.
    - value : nilai utama yang ditampilkan.
    - icon : class icon (menggunakan remix icon).
    - iconBgClass (opsional) : class tambahan untuk latar belakang icon.
    - iconClass (opsional) : class tambahan untuk icon.
--}}

<div
    class="relative overflow-hidden rounded-lg bg-white p-4 shadow-md transition-transform duration-300 hover:-translate-y-1.5">
    <div class="flex items-center justify-between">
        <div class="space-y-2">
            {{-- title --}}
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>

            {{-- value --}}
            <p class="text-3xl font-bold text-gray-800">{{ $value }}</p>
        </div>

        {{-- icon --}}
        <div class="{{ $iconBgClass }} rounded-full p-3">
            <i class="{{ $icon }} text-4xl {{ $iconClass }}"></i>
        </div>
    </div>

    {{-- background gradient --}}
    <div
        class="pointer-events-none absolute bottom-0 right-0 -mb-16 -mr-16 h-32 w-32 rounded-tl-full bg-gradient-to-br from-gray-100 via-gray-200 to-gray-300 opacity-50">
    </div>
</div>
