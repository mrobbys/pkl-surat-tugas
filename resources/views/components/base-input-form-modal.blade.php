@props([
    'idLabel' => '',
    'label' => '',
    'nameError' => '',
    'isShowMode' => null,
    'divClass' => '',
    'required' => true,
])

{{-- 
    Props:
    - idLabel: id untuk label.
    - label: teks untuk label.
    - nameError: nama properti error di alpine.js.
    - isShowMode (opsional): untuk menampilkan tanda * pada label jika dalam mode create/edit.
    - divClass (opsional): class tambahan untuk div pembungkus.
    - required (opsional): menampilkan tanda * pada label jika true.
--}}

<div class="{{ $divClass }} flex w-full flex-col gap-1 text-neutral-600">
    <label for="{{ $idLabel }}"
        class="w-fit pl-0.5 text-sm font-bold">
        {{ $label }}

        @if ($required)
            @if ($isShowMode)
                <span x-show="{{ $isShowMode }}"
                    class="text-red-500">*</span>
            @else
                <span class="text-red-500">*</span>
            @endif
        @endif

    </label>
    {{ $slot }}
    <small class="text-red-500"
        x-show="errors.{{ $nameError }}"
        x-text="errors.{{ $nameError }}"></small>
</div>
