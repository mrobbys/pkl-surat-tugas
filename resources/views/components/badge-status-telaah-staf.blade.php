@props(['status'])

{{-- 
    Props:
    - status: status dari telaah staf
 --}}

@php
    // mapping status ke label dan warna
    $statusConfig = [
        'diajukan' => [
            'label' => 'Diajukan',
            'class' => 'bg-blue-100 text-blue-800 border-blue-200',
        ],
        'disetujui_kabid' => [
            'label' => 'Disetujui Kabid',
            'class' => 'bg-green-100 text-green-800 border-green-200',
        ],
        'revisi_kabid' => [
            'label' => 'Revisi Kabid',
            'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        ],
        'ditolak_kabid' => [
            'label' => 'Ditolak Kabid',
            'class' => 'bg-red-100 text-red-800 border-red-200',
        ],
        'disetujui_kadis' => [
            'label' => 'Disetujui Kadis',
            'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        ],
        'revisi_kadis' => [
            'label' => 'Revisi Kadis',
            'class' => 'bg-orange-100 text-orange-800 border-orange-200',
        ],
        'ditolak_kadis' => [
            'label' => 'Ditolak Kadis',
            'class' => 'bg-rose-100 text-rose-800 border-rose-200',
        ],
    ];

    // default config
    $config = $statusConfig[$status] ?? [
        'label' => ucfirst(str_replace('_', ' ', $status)),
        'class' => 'bg-gray-100 text-gray-800 border-gray-200',
    ];
@endphp

<span
    class="{{ $config['class'] }} inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-semibold">
    {{-- icon berdasarkan status --}}
    @if (str_contains($status, 'disetujui'))
        <i class="ri-checkbox-circle-line"></i>
    @elseif(str_contains($status, 'ditolak'))
        <i class="ri-close-circle-line"></i>
    @elseif(str_contains($status, 'revisi'))
        <i class="ri-error-warning-line"></i>
    @else
        <i class="ri-time-line"></i>
    @endif

    <span>{{ $config['label'] }}</span>
</span>
