<x-layouts.app-layout :title="'Dashboard'">
    <div x-data="dashboardManager()">
        {{-- cards start --}}
        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-8">
            @can('view users')
                {{-- card total pegawai --}}
                <x-card-dashboard title="Total Pegawai"
                    value="{{ $totalPegawai }}"
                    icon="ri-team-line"
                    iconBgClass="bg-sky-100"
                    iconClass="text-sky-700" />
            @endcan

            @can('view roles')
                {{-- card total roles --}}
                <x-card-dashboard title="Total Roles"
                    value="{{ $totalRoles }}"
                    icon="ri-shield-user-line"
                    iconBgClass="bg-emerald-100"
                    iconClass="text-emerald-700" />
            @endcan

            @can('view telaah staf')
                {{-- card total surat dibuat --}}
                <x-card-dashboard title="Total Surat Dibuat ({{ $currentYear }})"
                    value="{{ $totalSurat }}"
                    icon="ri-file-list-3-line"
                    iconBgClass="bg-amber-100"
                    iconClass="text-amber-700" />
            @endcan
        </div>
        {{-- cards end --}}

        {{-- chart start --}}
        <div class="relative mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
            {{-- chart statistik status pengajuan surat --}}
            <x-chart-card title="Statistik Status Pengajuan Surat {{ $currentYear }}"
                canvasId="statusChart" />

            {{-- chart proporsi penugasan pegawai berdasarkan golongan --}}
            <x-chart-card title="Proporsi Penugasan Berdasarkan Golongan {{ $currentYear }}"
                canvasId="golonganChart" />
        </div>
        {{-- chart end --}}

        <x-table-recent-surat :recentSurat="$recentSurat" />

        {{-- calendar start --}}
        <x-dashboard-calendar />
        {{-- calendar end --}}
    </div>
</x-layouts.app-layout>
