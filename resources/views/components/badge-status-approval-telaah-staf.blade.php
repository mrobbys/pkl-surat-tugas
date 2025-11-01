@props(['status'])

{{-- 
    Props:
    - status: status approval dari penyetuju telaah staf
 --}}

@php
    $statusConfig = [
        'pending' => [
            'label' => 'Pending',
            'class' => 'bg-gray-100 text-gray-700 border-gray-200',
            'icon' => 'ri-time-line'
        ],
        'disetujui' => [
            'label' => 'Disetujui',
            'class' => 'bg-green-100 text-green-700 border-green-200',
            'icon' => 'ri-checkbox-circle-line'
        ],
        'ditolak' => [
            'label' => 'Ditolak',
            'class' => 'bg-red-100 text-red-700 border-red-200',
            'icon' => 'ri-close-circle-line'
        ],
        'revisi' => [
            'label' => 'Revisi',
            'class' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'icon' => 'ri-error-warning-line'
        ],
    ];

    // default config
    $config = $statusConfig[$status] ?? [
        'label' => ucfirst($status),
        'class' => 'bg-gray-100 text-gray-700 border-gray-200',
        'icon' => 'ri-question-line'
    ];
@endphp

<span class="inline-flex items-center gap-1 rounded-md border px-2 py-1 text-xs font-medium {{ $config['class'] }}">
    <i class="{{ $config['icon'] }}"></i>
    <span>{{ $config['label'] }}</span>
</span>