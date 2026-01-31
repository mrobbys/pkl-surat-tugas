<div
    class="relative h-fit overflow-hidden rounded-xl bg-white shadow-lg md:col-span-2 xl:col-span-1">
    <div class="px-6 py-4">
        <h2 class="text-xl font-semibold text-gray-800">5 Aktivitas Terakhir</h2>
        <p class="mt-1 text-sm text-gray-500">Daftar aktivitas terbaru pengguna</p>
    </div>
    <div class="w-full overflow-hidden rounded-sm px-6 pb-6 pt-4">
        <ol class="relative ml-2 space-y-6 border-s border-gray-200">
            @forelse ($recentActivities as $activity)
                <li class="ms-6">
                    {{-- warna dot berdasarkan field event pada table activity_log --}}
                    @php
                        $dotColor = match ($activity->event) {
                            'created' => 'bg-green-500',
                            'updated' => 'bg-blue-500',
                            'deleted' => 'bg-red-500',
                            default => 'bg-gray-500',
                        };
                    @endphp

                    <div
                        class="{{ $dotColor }} absolute -start-2 mt-1.5 h-4 w-4 rounded-full border-4 border-white shadow-sm">
                    </div>

                    <div
                        class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between xl:flex-col xl:items-start 2xl:flex-row 2xl:items-center">
                        <h3 class="max-w-[200px] truncate text-sm font-bold text-gray-900"
                            title="{{ $activity->causer?->email ?? 'System' }}">
                            {{ $activity->causer?->email ?? 'System' }}
                        </h3>
                        <time class="text-xs font-medium tracking-wide text-gray-400">
                            {{ $activity->created_at?->diffForHumans() ?? '-' }}
                        </time>
                    </div>

                    <p class="mt-1 text-sm font-normal text-gray-500">
                        {{ ucfirst($activity->description) }}
                    </p>
                </li>
            @empty
                <div class="py-4 text-center text-sm italic text-gray-400">
                    Tidak ada aktivitas terbaru.
                </div>
            @endforelse
        </ol>
    </div>
</div>
