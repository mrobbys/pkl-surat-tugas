<div class="relative my-5 h-fit overflow-hidden rounded-xl bg-white shadow-lg">
    <div class="px-6 py-4">
        <h2 class="text-xl font-semibold text-gray-800">5 Surat Terakhir</h2>
        <p class="mt-1 text-sm text-gray-500">Daftar surat yang baru dibuat</p>
    </div>
    <div class="w-full overflow-hidden overflow-x-auto rounded-sm">
        <table class="w-full text-left text-sm text-neutral-600">
            <thead class="bg-neutral-50 text-sm text-neutral-900">
                <tr>
                    <th scope="col"
                        class="p-4 whitespace-nowrap">Nomor Telaahan</th>
                    <th scope="col"
                        class="p-4 whitespace-nowrap">Tanggal Diajukan</th>
                    <th scope="col"
                        class="p-4 whitespace-nowrap">Dibuat Oleh</th>
                    <th scope="col"
                        class="p-4 whitespace-nowrap">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-300">
                @forelse ($recentSurat as $surat)
                    <tr class="even:bg-black/5">
                        <td class="p-4 whitespace-nowrap">{{ $surat->nomor_telaahan }}</td>
                        <td class="p-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($surat->tanggal_telaahan)->translatedFormat('d F Y') }}
                        </td>
                        <td class="p-4 whitespace-nowrap">{{ $surat->pembuat->nama_lengkap }}</td>
                        <td class="p-4 whitespace-nowrap">
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
