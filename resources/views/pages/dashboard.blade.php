<x-layouts.app-layout :title="'Dashboard'">
    <div x-data="dashboardManager()">
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
                <x-card-dashboard title="Total Surat Dibuat"
                    value="{{ $totalSurat }}"
                    icon="ri-file-list-3-line"
                    iconBgClass="bg-amber-100"
                    iconClass="text-amber-700" />
            @endcan
        </div>

        {{-- table 5 surat terakhir --}}
        <div class="relative my-5 overflow-hidden rounded-xl bg-white shadow-lg">
            <div class="px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">5 Surat Terakhir</h2>
                <p class="mt-1 text-sm text-gray-500">Daftar surat yang baru dibuat</p>
            </div>
            <div class="w-full overflow-hidden overflow-x-auto rounded-sm">
                <table class="w-full text-left text-sm text-neutral-600">
                    <thead class="bg-neutral-50 text-sm text-neutral-900">
                        <tr>
                            <th scope="col"
                                class="p-4">Nomor Telaahan</th>
                            <th scope="col"
                                class="p-4">Tanggal Diajukan</th>
                            <th scope="col"
                                class="p-4">Dibuat Oleh</th>
                            <th scope="col"
                                class="p-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-300">
                        @forelse ($recentSurat as $surat)
                            <tr class="even:bg-black/5">
                                <td class="p-4">{{ $surat->nomor_telaahan }}</td>
                                <td class="p-4">
                                    {{ \Carbon\Carbon::parse($surat->tanggal_telaahan)->translatedFormat('d F Y') }}
                                </td>
                                <td class="p-4">{{ $surat->pembuat->nama_lengkap }}</td>
                                <td class="p-4">
                                    <x-badge-status-dashboard :status="$surat->status" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="p-4 text-center text-sm text-gray-500">
                                    Tidak ada data surat perjalanan dinas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- calendar --}}
        <div class="relative my-5 rounded-xl bg-white p-4 shadow-lg">
            <h2 class="mb-4 text-xl font-semibold text-gray-800">Kalender Surat Tugas</h2>
            <div id="calendar"></div>
        </div>
    </div>
</x-layouts.app-layout>
