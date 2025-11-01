<x-layouts.auth-layout :title="'Login'">
    <div x-data="loginManager()"
        class="w-full max-w-md rounded-2xl bg-white px-4 py-8 shadow-md">
        <div class="mb-4 flex flex-col items-center justify-center">
            <img src="{{ asset('assets/diskominfo-bjb.png') }}"
                alt="logo diskominfo bjb"
                class="h-18 mb-8 w-auto transition duration-300 hover:scale-105 hover:drop-shadow-2xl" />
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Surat Tugas Lapangan</h1>
            <p class="text-sm text-gray-500">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        {{-- pesan error dari backend start --}}
        @if ($errors->has('email') || $errors->has('password'))
            <div
                class="my-4 flex flex-col items-start justify-center gap-3 rounded-md bg-red-100 p-2">
                <ul class="list-inside list-disc">
                    {{-- pesan error email start --}}
                    @error('email')
                        <li class="text-sm text-red-500">{{ $message }}</li>
                    @enderror

                    {{-- pesan error email end --}}

                    {{-- pesan error password start --}}
                    @if ($errors->has('password') && is_array($errors->get('password')))
                        @foreach ($errors->get('password') as $msg)
                            <li class="text-sm text-red-500">{{ $msg }}</li>
                        @endforeach
                    @endif

                    {{-- pesan error password end --}}
                </ul>
            </div>
        @endif
        {{-- pesan error dari backend end --}}

        {{-- Form Login Start --}}
        <form class="space-y-4"
            method="POST"
            action="{{ route('login.store') }}"
            x-on:submit="loading = true">
            @csrf
            {{-- Input Email Start --}}
            <div class="flex w-full flex-col gap-1 text-neutral-600">
                <label for="email"
                    class="w-fit pl-0.5 text-sm">Email</label>
                <input id="email"
                    type="email"
                    class="{{ $errors->has('email') ? 'border border-red-500' : 'border border-neutral-300' }} w-full rounded-sm bg-neutral-100 px-2 py-3 text-sm transition duration-300 focus:outline-none focus:ring-0"
                    :class="errors.email ? 'border border-red-500' : 'focus:border focus:border-blue-500'"
                    name="email"
                    placeholder="nama@gmail.com"
                    autocomplete="off"
                    x-model="email"
                    @input="validateForm('email')" />
                {{-- pesan error validasi dari js start --}}
                <small class="text-red-500"
                    x-text="errors.email"></small>
                {{-- pesan error validasi dari js end --}}
            </div>
            {{-- Input Email End --}}

            {{-- Input Password Start --}}
            <div class="flex w-full flex-col gap-1 text-neutral-600">
                <label for="password"
                    class="w-fit pl-0.5 text-sm">Password</label>
                <div class="relative">
                    <input id="password"
                        x-model="password"
                        :type="showPassword ? 'text' : 'password'"
                        class="{{ $errors->has('password') ? 'border border-red-500' : 'border border-neutral-300' }} w-full rounded-sm bg-neutral-100 px-2 py-3 text-sm transition duration-300 focus:outline-none focus:ring-0"
                        :class="errors.password ? 'border border-red-500' :
                            'focus:border focus:border-blue-500'"
                        name="password"
                        autocomplete="off"
                        placeholder="••••••••"
                        @input="validateForm('password')" />
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
                {{-- pesan error validasi dari js start --}}
                <div x-show="errors.password"
                    class="flex flex-col items-start">
                    <template x-if="Array.isArray(errors.password)">
                        <ul class="list-inside list-disc">
                            <template x-for="msg in errors.password"
                                :key="msg">
                                <li x-text="msg"
                                    class="text-sm text-red-500"></li>
                            </template>
                        </ul>
                    </template>
                </div>
                {{-- pesan error validasi dari js end --}}
            </div>
            {{-- Input Password End --}}

            {{-- Input Remember Me Start --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox"
                        id="remember"
                        name="remember"
                        value="1"
                        class="w-4 h-4 cursor-pointer" />
                    <label for="remember"
                        class="ml-2 block cursor-pointer select-none text-sm text-gray-700">
                        Ingat Saya
                    </label>
                </div>
            </div>
            {{-- Input Remember Me End --}}

            {{-- Button Submit Start --}}
            <div>
                <button x-cloak
                    type="submit"
                    class="w-full rounded-lg px-3 py-2"
                    x-bind:class="{
                        'bg-gray-400 text-gray-600 cursor-not-allowed': !isFormValid(),
                        'bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 cursor-pointer': isFormValid(),
                        'opacity-50': loading,
                    }">
                    <span x-show="!loading">Masuk</span>
                    <span x-cloak
                        x-show="loading"
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
            {{-- Button Submit End --}}
        </form>
        {{-- Form Login End --}}

        <hr class="mt-6 border-gray-200" />
        <p class="text-center text-sm text-gray-500 mt-4">&copy; 2025 Diskominfo Kota Banjarbaru</p>
        
    </div>
</x-layouts.auth-layout>
