<nav x-cloak
    class="fixed left-0 z-30 flex h-dvh w-60 shrink-0 flex-col border-r border-neutral-300 bg-white p-4 shadow-xl transition-transform duration-300 lg:relative lg:w-64 lg:translate-x-0"
    x-bind:class="sidebarIsOpen ? 'translate-x-0' : '-translate-x-60'">
    {{-- Logo --}}
    <a href="#"
        class="ml-2 w-fit text-2xl font-bold text-neutral-900">
        <span class="sr-only">homepage</span>
        <img src="{{ asset('assets/diskominfo-bjb.png') }}"
            alt="logo"
            class="w-full" />
    </a>

    {{-- Sidebar links --}}
    <div class="my-5 flex flex-col gap-2 overflow-y-auto border-t border-neutral-400 py-5">
        {{-- dashboard start --}}
        <x-sidebar-link href="{{ route('dashboard') }}"
            icon="ri-dashboard-fill"
            colorIcon="text-[#009BDE]">
            Dashboard
        </x-sidebar-link>
        {{-- dashboard end --}}

        {{-- manajemen role (atur jika permissionnya bisa view roles) start --}}
        @can('view roles')
            <x-sidebar-link href="{{ route('roles.index') }}"
                icon="ri-user-star-fill"
                colorIcon="text-[#64748B]">
                Manajemen Role
            </x-sidebar-link>
        @endcan

        {{-- manajemen role (atur jika permissionnya bisa view roles) end --}}

        {{-- manajemen pangkat (atur jika permissionnya bisa view pangkat golongan) start --}}
        @can('view pangkat golongan')
            <x-sidebar-link href="{{ route('pangkat-golongan.index') }}"
                icon="ri-medal-line"
                colorIcon="text-[#F59E0B]">
                Pangkat & Golongan
            </x-sidebar-link>
        @endcan

        {{-- manajemen pangkat (atur jika permissionnya bisa view pangkat golongan) end --}}

        {{-- manajemen user (atur jika permissionnya bisa view users) start --}}
        @can('view users')
            <x-sidebar-link href="{{ route('users.index') }}"
                icon="ri-shield-user-fill"
                colorIcon="text-[#10B981]">
                Manajemen User
            </x-sidebar-link>
        @endcan

        {{-- manajemen user (atur jika permissionnya bisa view users) end --}}

        {{-- surat perjalanan dinas start --}}
        @can('view telaah staf')
            <x-sidebar-link href="{{ route('surat.index') }}"
                icon="ri-file-list-3-fill"
                colorIcon="text-[#EF4444]">
                Surat Tugas
            </x-sidebar-link>
        @endcan
        {{-- surat perjalanan dinas end --}}
    </div>
</nav>
