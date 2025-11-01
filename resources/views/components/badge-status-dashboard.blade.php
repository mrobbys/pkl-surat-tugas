@props(['status'])

{{-- 
    Props:
    - status: status surat perjalanan dinas.
--}}

@php
    $statusConfig = [
        'diajukan' => [
            'label' => 'Diajukan',
            'class' => 'bg-blue-100 text-blue-800 border-blue-200',
            'icon' => 'ri-time-line',
        ],
        'disetujui_kabid' => [
            'label' => 'Disetujui Kabid',
            'class' => 'bg-green-100 text-green-800 border-green-200',
            'icon' => 'ri-checkbox-circle-line',
        ],
        'revisi_kabid' => [
            'label' => 'Revisi Kabid',
            'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'icon' => 'ri-error-warning-line',
        ],
        'ditolak_kabid' => [
            'label' => 'Ditolak Kabid',
            'class' => 'bg-red-100 text-red-800 border-red-200',
            'icon' => 'ri-close-circle-line',
        ],
        'disetujui_kadis' => [
            'label' => 'Disetujui Kadis',
            'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'icon' => 'ri-checkbox-circle-line',
        ],
        'revisi_kadis' => [
            'label' => 'Revisi Kadis',
            'class' => 'bg-orange-100 text-orange-800 border-orange-200',
            'icon' => 'ri-error-warning-line',
        ],
        'ditolak_kadis' => [
            'label' => 'Ditolak Kadis',
            'class' => 'bg-rose-100 text-rose-800 border-rose-200',
            'icon' => 'ri-close-circle-line',
        ],
    ];

    $config = $statusConfig[$status] ?? [
        'label' => $status,
        'class' => 'bg-gray-100 text-gray-800 border-gray-200',
        'icon' => 'ri-question-line',
    ];
@endphp

<span
    class="{{ $config['class'] }} inline-flex items-center gap-1.5 whitespace-nowrap rounded-full border px-2.5 py-1 text-xs font-semibold">
    <i class="{{ $config['icon'] }}"></i>
    <span>{{ $config['label'] }}</span>
</span>
