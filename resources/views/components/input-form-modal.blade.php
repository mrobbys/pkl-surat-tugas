@props([
    'id',
    'labelClass' => '',
    'divClass' => '',
    'label',
    'name',
    'type' => 'text',
    'placeholder' => '',
    'isShowMode' => null,
    'mode' => null,
])

{{--
    Props:
    - id: id untuk label dan input.
    - labelClass (opsional): class tambahan untuk label.
    - divClass (opsional): class tambahan untuk div pembungkus.
    - label: teks untuk label.
    - name: nama untuk input dan properti form di alpine.js.
    - type (opsional): tipe input, default 'text'.
    - placeholder (opsional): placeholder untuk input, default ''.
    - isShowMode (opsional): untuk menampilkan tanda * pada label jika dalam mode create/edit.
    - mode (opsional): mode untuk input form, bisa 'show' untuk menonaktifkan input. Penggunaan utamanya digunakan untuk bagian detail/show surat telaah staf.
--}}

<div class="{{ $divClass }} flex w-full flex-col gap-1 text-neutral-600">
    <label for="{{ $id }}"
        class="{{ $labelClass }} w-fit pl-0.5 text-sm font-bold">
        {{ $label }}

        @if ($isShowMode)
            <span x-show="{{ $isShowMode }}"
                class="text-red-500">*</span>
        @else
            <span class="text-red-500">*</span>
        @endif
    </label>
    <input
        {{ $attributes->merge([
            'id' => $id,
            'name' => $name,
            'type' => $type,
            'placeholder' => $placeholder,
            'autocomplete' => 'off',
        ]) }}
        @class([
            'w-full rounded-sm border border-neutral-300 px-2 py-3 text-sm',
            'bg-neutral-50' => !$mode || $mode !== 'show',
            'bg-[#eaeaea] cursor-not-allowed' => $mode === 'show',
        ]) />
    <small class="text-red-500"
        x-show="errors.{{ $name }}"
        x-text="errors.{{ $name }}"></small>
</div>
