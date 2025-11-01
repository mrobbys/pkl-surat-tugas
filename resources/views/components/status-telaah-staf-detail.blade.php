@props(['data'])

<div x-cloak class="relative bg-white shadow-md rounded-md p-4 my-8">
    <h3 class="text-lg font-semibold text-gray-800">
        Status Telaah Staf:
        <x-badge-status-telaah-staf :status="$data['status']" />
    </h3>

    {{-- respon dari para penyetuju telaah staf --}}
    <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
        {{-- Respon dari penyetuju satu --}}
        <div class="rounded-lg border border-gray-300 bg-gray-50 p-4 shadow-md">
            <h4 class="mb-3 text-base font-semibold text-gray-700">
                <i class="ri-user-line mr-1"></i>
                Respon Penyetuju 1 (Kabid)
            </h4>
            
            <div class="space-y-2 text-sm">
                <p>
                    <strong class="text-gray-700">Penyetuju:</strong>
                    <span class="text-gray-600">
                        {{ $data['penyetuju_satu_nama'] ?? '-' }}
                    </span>
                </p>
                
                <p>
                    <strong class="text-gray-700">Status:</strong>
                    <x-badge-status-approval-telaah-staf :status="$data['status_penyetuju_satu'] ?? 'pending'" />
                </p>
                
                <p>
                    <strong class="text-gray-700">Catatan:</strong>
                    <span class="text-gray-600">
                        {{ $data['catatan_satu'] ?? '-' }}
                    </span>
                </p>
                
                <p>
                    <strong class="text-gray-700">Tanggal:</strong>
                    <span class="text-gray-600">
                        {{ $data['tanggal_status_satu_formatted'] ?? '-' }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Respon dari penyetuju dua --}}
        <div class="rounded-lg border border-gray-300 bg-gray-50 p-4 shadow-md">
            <h4 class="mb-3 text-base font-semibold text-gray-700">
                <i class="ri-user-line mr-1"></i>
                Respon Penyetuju 2 (Kadis)
            </h4>
            
            <div class="space-y-2 text-sm">
                <p>
                    <strong class="text-gray-700">Penyetuju:</strong>
                    <span class="text-gray-600">
                        {{ $data['penyetuju_dua_nama'] ?? '-' }}
                    </span>
                </p>
                
                <p>
                    <strong class="text-gray-700">Status:</strong>
                    <x-badge-status-approval-telaah-staf :status="$data['status_penyetuju_dua'] ?? 'pending'" />
                </p>
                
                <p>
                    <strong class="text-gray-700">Catatan:</strong>
                    <span class="text-gray-600">
                        {{ $data['catatan_dua'] ?? '-' }}
                    </span>
                </p>
                
                <p>
                    <strong class="text-gray-700">Tanggal:</strong>
                    <span class="text-gray-600">
                        {{ $data['tanggal_status_dua_formatted'] ?? '-' }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>