<x-layouts.app-layout :title="'Dashboard'">
    <div x-data="dashboardManager()">
        {{-- cards start --}}
        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
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

        <div class="relative my-8 grid grid-cols-1 xl:grid-cols-3 xl:gap-4">
            <div class="space-y-4 xl:col-span-2">
                {{-- chart intensitas surat tugas --}}
                <x-chart-card canvasId="intensityChart"
                    height="h-52"
                    scrollable="true" />

                {{-- table 5 surat terakhir --}}
                <x-table-recent-surat :recentSurat="$recentSurat" />

                {{-- calendar untuk tampilan desktop --}}
                <x-dashboard-calendar calendarId="calendar-desktop"
                    customClass="hidden xl:block" />

            </div>
            <div class="grid auto-rows-min grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-1">
                {{-- chart statistik status pengajuan surat --}}
                <x-chart-card canvasId="statusChart" />

                {{-- chart proporsi penugasan pegawai berdasarkan golongan --}}
                <x-chart-card canvasId="golonganChart" />

                @can('view activity log')
                    {{-- table 5 aktivitas terakhir --}}
                    <x-table-recent-activities :recentActivities="$recentActivities" />
                @endcan

                {{-- calendar untuk tampilan mobile --}}
                <x-dashboard-calendar calendarId="calendar-mobile"
                    customClass="block xl:hidden md:col-span-2" />

            </div>
        </div>
    </div>
</x-layouts.app-layout>
