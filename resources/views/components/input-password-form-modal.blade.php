@props([
    'id',
    'label' => 'Password',
    'name',
    'placeholder' => 'Masukkan Password',
    'isShowMode' => null,
    'labelClass' => '',
])

{{-- 
    Props:
    - id: id untuk label dan input.
    - label: teks untuk label.
    - name: nama untuk input dan properti form di alpine.js.
    - placeholder (opsional): placeholder untuk input, default 'Masukkan Password'.
    - isShowMode (opsional): untuk menampilkan tanda * pada label jika dalam mode create/edit.
    - labelClass (opsional): kelas tambahan untuk label.
--}}

<div class="flex w-full flex-col gap-1 text-neutral-600">
    <label for="{{ $id }}"
        class="w-fit pl-0.5 text-sm font-bold">
        {{ $label }}
        @if ($isShowMode)
            <span x-show="{{ $isShowMode }}"
                class="text-red-500">*</span>
        @else
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="relative">
        <input
            {{ $attributes->merge([
                'id' => $id,
                'name' => $name,
                'type' => 'password',
                'placeholder' => $placeholder,
                'class' => 'w-full border border-neutral-300 bg-neutral-50 rounded-sm px-2 py-3 text-sm',
                'autocomplete' => 'off',
            ]) }}
            :type="showPassword ? 'text' : 'password'" />
        <button type="button"
            @click="showPassword = !showPassword"
            class="text-on-surface absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer"
            aria-label="Show password">
            <i x-show="!showPassword"
                class="ri-eye-line ri-lg"></i>
            <i x-cloak
                x-show="showPassword"
                class="ri-eye-off-line ri-lg"></i>
        </button>
    </div>
    <div x-show="errors.{{ $name }}"
        class="flex flex-col items-start">
        <template x-if="Array.isArray(errors.{{ $name }})">
            <ul class="list-inside list-disc">
                <template x-for="msg in errors.{{ $name }}"
                    :key="msg">
                    <li x-text="msg"
                        class="text-sm text-red-500"></li>
                </template>
            </ul>
        </template>
        <template x-if="!Array.isArray(errors.{{ $name }})">
            <small class="text-red-500"
                x-text="errors.{{ $name }}"></small>
        </template>
    </div>
</div>
