@props([
    'click',
])

{{--
    Props:
    - click: fungsi javascript untuk event click (open modal create)
--}}

<button
    @click="{{ $click }}"
    type="button"
    class="flex cursor-pointer items-center justify-center gap-1 rounded-lg border border-neutral-300 bg-white px-4 py-2 font-semibold text-green-500 shadow-sm transition-all duration-300 hover:text-green-700 hover:shadow-md focus:outline-none"
>
    <i class="ri-add-large-fill text-lg"></i>
    {{ $slot }}
</button>
