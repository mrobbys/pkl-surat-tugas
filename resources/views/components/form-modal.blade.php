@props([
    'show',
    'title',
    'size',
    'onClose',
    'onSubmit',
    'onKeydownEnter' => '',
    'loading',
    'showSubmit' => true,
    'submitDisabled' => 'loading',
])

{{--
    Props:
    - show: variable dari alpine.js untuk kontrol modal tampil/tidak.
    - title: variable dari alpine.js untuk judul modal.
    - size: max-width untuk modal.
    - onClose: fungsi untuk menutup modal.
    - onSubmit: fungsi untuk men-submit form.
    - onKeydownEnter: fungsi untuk menangani penekanan tombol Enter.
    - loading: ekspresi alpine.js yang menunjukkan status loading
    - showSubmit: tampilkan tombol submit atau tidak.
    - submitDisabled: ekspresi alpine.js untuk menonaktifkan tombol submit (default: loading)
    
    Slots:
    - default: Modal body content
--}}

<div x-cloak
    x-show="{{ $show }}"
    x-transition.opacity.duration.200ms
    x-trap.inert.noscroll="{{ $show }}"
    x-on:keydown.esc.window="! {{ $loading }} && {{ $onClose }}"
    @if ($onKeydownEnter) x-on:keydown.enter.prevent="{{ $onKeydownEnter }}" @endif
    class="fixed inset-0 z-30 flex items-center justify-center bg-black/20 p-4 backdrop-blur-md"
    role="dialog"
    aria-modal="true"
    aria-labelledby="defaultModalTitle">
    <!-- Modal Dialog -->
    <div x-show="{{ $show }}"
        x-transition:enter="transition delay-100 duration-200 ease-out motion-reduce:transition-opacity"
        x-transition:enter-start="scale-y-0 opacity-0"
        x-transition:enter-end="scale-y-100 opacity-100"
        class="{{ $size }} flex max-h-[90dvh] w-full flex-col gap-4 rounded-sm border border-neutral-300 bg-white text-neutral-600">
        <!-- Dialog Header -->
        <div
            class="flex items-center justify-between border-b border-neutral-300 bg-neutral-50/60 p-4">
            <h3 id="defaultModalTitle"
                class="font-semibold tracking-wide text-neutral-900"
                x-text="{{ $title }}"></h3>
            <button :disabled="{{ $loading }}"
                aria-label="close modal"
                x-on:click="{{ $onClose }}">
                <i
                    class="ri-close-fill cursor-pointer rounded-lg p-2 text-2xl transition-colors duration-300 hover:bg-gray-200"></i>
            </button>
        </div>
        <!-- Dialog Body -->
        <div class="overflow-y-auto p-4 lg:overflow-y-visible">
            
            {{-- div dibawah ini untuk testing form telaah staf --}}
            {{-- <div class="overflow-y-auto p-4"> --}}
            <form @submit.prevent="{{ $onSubmit }}"
                class="space-y-4">
                {{ $slot }}
            </form>
        </div>
        <!-- Dialog Footer -->
        <div
            class="flex flex-col-reverse justify-between gap-2 border-t border-neutral-300 bg-neutral-50/60 p-4 md:flex-row md:justify-end">
            <button x-on:click="{{ $onClose }}"
                :disabled="{{ $loading }}"
                type="button"
                class="cursor-pointer whitespace-nowrap rounded-sm px-4 py-2 text-center text-sm font-medium tracking-wide text-neutral-600 transition hover:bg-gray-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0">
                Tutup
            </button>

            <button x-cloak
                x-show="{{ $showSubmit }}"
                @click="{{ $onSubmit }}"
                type="submit"
                x-bind:disabled="{{ $submitDisabled }}"
                :class="{
                    'cursor-not-allowed bg-black/30 text-neutral-300': {{ $submitDisabled }},
                    'cursor-pointer bg-black text-neutral-100 hover:opacity-75': !(
                        {{ $submitDisabled }})
                }"
                class="whitespace-nowrap rounded-sm border px-4 py-2 text-center text-sm font-medium tracking-wide transition-colors duration-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0">
                <span x-show="! {{ $loading }}"
                    x-text="textSubmit"></span>
                <span x-cloak
                    x-show="{{ $loading }}"
                    class="flex items-center justify-center">
                    <svg class="-ml-1 mr-2 h-4 w-4 animate-spin text-white"
                        fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Memuat...
                </span>
            </button>
        </div>
    </div>
</div>
