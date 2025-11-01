<div
    x-data="{ userDropdownIsOpen: false }"
    class="relative"
    x-on:keydown.esc.window="userDropdownIsOpen = false"
>
    <button
        type="button"
        class="group w-full cursor-pointer px-4 py-2 focus:outline-0"
        {{-- x-bind:class="userDropdownIsOpen ? 'bg-black/10' : ''" --}}
        aria-haspopup="true"
        x-on:click="userDropdownIsOpen = ! userDropdownIsOpen"
        x-bind:aria-expanded="userDropdownIsOpen"
    >
        <div class="flex items-center justify-center gap-2">
            <i
                class="ri-user-3-line text-2xl text-gray-900 transition-transform duration-300 group-hover:scale-110"
            ></i>
            <span class="sr-only">profile settings</span>
        </div>
    </button>

    {{-- Menu --}}
    <div
        x-cloak
        x-show="userDropdownIsOpen"
        class="absolute top-10 right-0 z-20 h-fit w-64 rounded-xl bg-[#F5F5F5] py-2 shadow-2xl"
        role="menu"
        x-on:click.outside="userDropdownIsOpen = false"
        x-on:keydown.down.prevent="$focus.wrap().next()"
        x-on:keydown.up.prevent="$focus.wrap().previous()"
        x-transition=""
        x-trap="userDropdownIsOpen"
    >
        <div class="flex flex-col px-3 py-2">
            <p class="text-base font-medium">{{ Auth::user()->nama_lengkap }}</p>
            <p class="text-xs text-neutral-500">{{ Auth::user()->email }}</p>
        </div>

        <div class="flex flex-col border-t border-neutral-300 px-3 py-2">
            <form id="logoutForm" action="{{ route("logout") }}" method="POST" class="hidden">
                @csrf
            </form>
            <button
                x-data
                x-on:click.prevent="
                    Swal.fire({
                        title: 'Yakin ingin logout?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, logout!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('logoutForm').submit()
                        }
                    })
                "
                type="submit"
                class="flex w-full cursor-pointer items-center gap-2 rounded-md bg-red-500 p-2 text-sm font-medium text-white transition-colors duration-300 hover:bg-red-700 focus:outline-hidden focus-visible:underline"
                role="menuitem"
            >
                <i class="ri-logout-box-r-line text-xl"></i>
                <span>Sign Out</span>
            </button>
        </div>
    </div>
</div>
