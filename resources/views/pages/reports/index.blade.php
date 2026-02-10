<x-layouts.app-layout :title="'Laporan'">
    <div class="relative overflow-hidden">
        {{-- grid card --}}
        <div class="my-6 grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2 xl:grid-cols-3">
            {{-- laporan rekapitulasi telaah staf start --}}
            <x-report-card title="Laporan Rekapitulasi"
                subtitle="Surat Telaahan Staf"
                :action="route('reports.rekapitulasi-surat-telaahan-staf')"
                icon="ri-file-list-3-line"
                color="teal"
                filterText="tanggal telaahan staf"
                >
                
                <x-report-date-input name="tanggal_awal"
                    label="Tanggal Awal"
                    placeholder="Pilih tanggal awal"
                    class="datepicker-start" />

                <x-report-date-input name="tanggal_akhir"
                    label="Tanggal Akhir"
                    placeholder="Pilih tanggal akhir"
                    class="datepicker-end" />
            </x-report-card>
            {{-- laporan rekapitulasi telaah staf end --}}

            {{-- laporan rekapitulasi nota dinas start --}}
            <x-report-card title="Laporan Rekapitulasi"
                subtitle="Surat Nota Dinas"
                :action="route('reports.rekapitulasi-surat-nota-dinas')"
                icon="ri-chat-forward-line"
                color="amber"
                filterText="tanggal disetujui kadis"
                >

                <x-report-date-input name="tanggal_awal"
                    label="Tanggal Awal"
                    placeholder="Pilih tanggal awal"
                    class="datepicker-start" />

                <x-report-date-input name="tanggal_akhir"
                    label="Tanggal Akhir"
                    placeholder="Pilih tanggal akhir"
                    class="datepicker-end" />
            </x-report-card>
            {{-- laporan rekapitulasi nota dinas end --}}

            {{-- laporan rekapitulasi surat tugas start --}}
            <x-report-card title="Laporan Rekapitulasi"
                subtitle="Surat Tugas"
                :action="route('reports.rekapitulasi-surat-tugas')"
                icon="ri-route-line"
                color="indigo"
                filterText="tanggal disetujui kadis"
                >

                <x-report-date-input name="tanggal_awal"
                    label="Tanggal Awal"
                    placeholder="Pilih tanggal awal"
                    class="datepicker-start" />

                <x-report-date-input name="tanggal_akhir"
                    label="Tanggal Akhir"
                    placeholder="Pilih tanggal akhir"
                    class="datepicker-end" />
            </x-report-card>
            {{-- laporan rekapitulasi surat tugas end --}}

            {{-- laporan rekapitulasi aktivitas pegawai start --}}
            <x-report-card title="Laporan Rekapitulasi"
                subtitle="Aktivitas Pegawai"
                :action="route('reports.rekapitulasi-aktivitas-pegawai')"
                icon="ri-pulse-line"
                color="fuchsia"
                filterText="tanggal pelaksanaan kegiatan (surat yang disetujui kadis)"
                >

                <x-report-date-input name="tanggal_awal"
                    label="Tanggal Awal"
                    placeholder="Pilih tanggal awal"
                    class="datepicker-start" />

                <x-report-date-input name="tanggal_akhir"
                    label="Tanggal Akhir"
                    placeholder="Pilih tanggal akhir"
                    class="datepicker-end" />
            </x-report-card>
            {{-- laporan rekapitulasi aktivitas pegawai end --}}

            {{-- laporan master pegawai start --}}
            <x-report-card title="Laporan Master"
                subtitle="Pegawai"
                :action="route('reports.master-pegawai')"
                icon="ri-database-2-line"
                color="rose"
                filterText="tanggal registrasi akun pegawai (Created At)"
                >

                <x-report-date-input name="tanggal_awal"
                    label="Tanggal Awal"
                    placeholder="Pilih tanggal awal"
                    class="datepicker-start" />

                <x-report-date-input name="tanggal_akhir"
                    label="Tanggal Akhir"
                    placeholder="Pilih tanggal akhir"
                    class="datepicker-end" />
            </x-report-card>
            {{-- laporan master pegawai end --}}
        </div>
    </div>

    {{-- scripts --}}
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // config flatpickr
                const config = {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "j F Y",
                    allowInput: false,
                    maxDate: 'today',
                    disableMobile: "true"
                };

                // ambil semua card dengan class report-card
                const reportCards = document.querySelectorAll(".report-card");

                reportCards.forEach((card) => {
                    const start = card.querySelector(".datepicker-start");
                    const end = card.querySelector(".datepicker-end");
                    const submitBtn = card.querySelector("button[type='submit']");
                    const form = card.querySelector("form");

                    // cek jika elemen tidak ditemukan
                    if (!start || !end || !submitBtn || !form) return;

                    // start picker
                    const startPicker = flatpickr(start, {
                        ...config,
                        onChange(selectedDates, dateStr) {
                            endPicker.set('minDate', dateStr);
                            // pastikan input start sudah terisi sebelum fokus ke end
                            if (start.value) {
                                setTimeout(() => endPicker.open(), 100);
                            }
                        }
                    });

                    // end picker
                    const endPicker = flatpickr(end, {
                        ...config,
                        onChange(selectedDates, dateStr) {
                            startPicker.set('maxDate', dateStr);
                            submitBtn.focus();
                        }
                    });

                    // handle form submit
                    form.addEventListener("submit", (e) => {
                        if (!start.value || !end.value) {
                            e.preventDefault();
                            Swal.fire({
                                icon: "warning",
                                title: "Tanggal belum lengkap",
                                text: "Silakan pilih tanggal awal dan tanggal akhir terlebih dahulu.",
                            });
                        }

                        // kosongkan value masing-masing picker setelah submit
                        setTimeout(() => {
                            startPicker.clear();
                            endPicker.clear();
                            endPicker.close();
                        }, 500);

                    });
                });
            })
        </script>
    @endpush

</x-layouts.app-layout>
