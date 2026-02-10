@props([
    'title',
    'subtitle',
    'action',
    'method' => 'POST',
    'icon' => 'ri-file-chart-line',
    'color' => 'teal', // teal, blue, indigo, purple, rose, amber
    'filterText' => '',
])

{{-- 
  Props:
  - title: judul kartu laporan
  - subtitle: subjudul kartu laporan
  - action: URL aksi form
  - method: metode form (default: POST)
  - icon: ikon di header kartu (default: ri-file-chart-line)
  - color: warna tema kartu (default: teal). Pilihan ada di $colorClasses
  - name: digunakan untuk atribut for pada label dan atribut id & name pada input
--}}

@php
    $colorClasses = [
        'teal' => [
            'header' => 'bg-teal-600',
            'button' => 'bg-teal-700 hover:bg-teal-800',
            'filterInfo' => 'bg-teal-50 text-teal-700',
        ],
        'amber' => [
            'header' => 'bg-amber-600',
            'button' => 'bg-amber-700 hover:bg-amber-800',
            'filterInfo' => 'bg-amber-50 text-amber-700',
        ],
        'indigo' => [
            'header' => 'bg-indigo-600',
            'button' => 'bg-indigo-700 hover:bg-indigo-800',
            'filterInfo' => 'bg-indigo-50 text-indigo-700',
        ],
        'fuchsia' => [
            'header' => 'bg-fuchsia-600',
            'button' => 'bg-fuchsia-700 hover:bg-fuchsia-800',
            'filterInfo' => 'bg-fuchsia-50 text-fuchsia-700',
        ],
        'rose' => [
            'header' => 'bg-rose-600',
            'button' => 'bg-rose-700 hover:bg-rose-800',
            'filterInfo' => 'bg-rose-50 text-rose-700',
        ],
    ];

    $colors = $colorClasses[$color] ?? $colorClasses['teal'];
@endphp

<div
    class="report-card flex h-full flex-col overflow-hidden rounded-xl bg-white shadow-lg transition-shadow duration-300 hover:shadow-xl">
    {{-- header start --}}
    <div class="{{ $colors['header'] }} flex px-6 py-8">
        <div class="flex w-full items-center justify-between">
            <div class="space-y-2">
                <h3 class="text-2xl font-bold text-zinc-100">{{ $title }}</h3>
                <p class="text-base text-zinc-200">{{ $subtitle }}</p>
            </div>
            <div class="rounded-lg bg-white/20 p-2 text-zinc-100">
                <i class="{{ $icon }} text-4xl"></i>
            </div>
        </div>
    </div>
    {{-- header end --}}

    <form action="{{ $action }}"
        method="{{ $method }}"
        target="_blank"
        class="flex flex-1 flex-col space-y-6 p-6">
        @csrf
        <div class="flex-grow space-y-6">
            <div class="mb-3 rounded-md {{ $colors['filterInfo'] }} p-2 text-xs">
                <i class="ri-information-line"></i>
                Filter berdasarkan {{ $filterText }}.
            </div>
            {{ $slot }}
        </div>
        <button type="submit"
            class="{{ $colors['button'] }} mt-auto flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition-colors duration-300">
            Cetak Laporan
        </button>
    </form>
</div>
