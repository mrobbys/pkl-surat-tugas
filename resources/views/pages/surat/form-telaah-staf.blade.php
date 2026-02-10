@php
    $formConfig = [
        'indexUrl' => route('surat.index'),
        'storeUrl' => route('telaah-staf.store'),
        'updateUrl' => route('telaah-staf.update', ['surat' => '__ID__']),
        'pegawais' => $pegawais,
    ];
@endphp

<x-layouts.app-layout :title="$mode === 'create' ? 'Buat Telaah Staf' : ($mode === 'edit' ? 'Edit Telaah Staf : ' . $surat->nomor_telaahan : 'Detail Telaah Staf : ' . $surat->nomor_telaahan)">
    <div class="relative my-10 rounded-xl bg-white p-4 shadow-xl"
        x-data="telaahStafForm(
            @js($mode),
            @js($surat ?? null),
            @js($data ?? null),
            @js($formConfig)
        )"
        x-init="init();
            if(mode === 'show'){
                form.tanggal_telaahan = @js($data['tanggal_telaahan_formatted'] ?? '');
                form.tanggal_mulai = @js($data['tanggal_mulai_formatted'] ?? '');
                form.tanggal_selesai = @js($data['tanggal_selesai_formatted'] ?? '');
            }
        ">
        <div class="mb-6 flex items-center justify-start gap-2 border-b pb-4">
            <a href="{{ route('surat.index') }}"
                class="flex cursor-pointer items-center justify-center gap-1 rounded-lg bg-gray-200 px-4 py-2 font-semibold text-neutral-600 shadow-sm transition-all duration-300 hover:bg-gray-300 focus:outline-none">
                <i class="ri-arrow-left-s-line text-xl"></i>
                Kembali
            </a>
            {{-- edit button, muncul ketika mode show --}}
            @if (
                $mode === 'show' &&
                    $surat->status !== 'disetujui_kadis' &&
                    $surat->status !== 'ditolak_kabid' &&
                    $surat->status !== 'ditolak_kadis')
                @can('edit telaah staf')
                    <a href="{{ route('telaah-staf.edit', $surat->id) }}"
                        class="flex cursor-pointer items-center justify-center gap-1 rounded-lg bg-blue-500 px-4 py-2 font-semibold text-white shadow-sm transition-all duration-300 hover:bg-blue-600 focus:outline-none">
                        <i class="ri-edit-line text-xl"></i>
                        Edit
                    </a>
                @endcan
            @endif
        </div>

        <form x-cloak
            @submit.prevent="saveTelaahStaf()">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                {{-- input kepada_yth start --}}
                <x-input-form-modal id="kepada_yth"
                    label="Kepada Yth"
                    name="kepada_yth"
                    placeholder="Pejabat Sekretaris Daerah ..."
                    x-model="form.kepada_yth"
                    x-bind:disabled="mode === 'show'"
                    isShowMode="mode !== 'show'"
                    :mode="$mode"
                    x-ref="kepada_ythInput" />
                {{-- input kepada_yth end --}}

                {{-- input dari start --}}
                <x-input-form-modal id="dari"
                    label="Dari"
                    name="dari"
                    placeholder="Kepala Dinas Komunikasi dan Informatika ..."
                    x-model="form.dari"
                    x-bind:disabled="mode === 'show'"
                    isShowMode="mode !== 'show'"
                    :mode="$mode" />
                {{-- input dari end --}}

                {{-- input nomor_telaahan start --}}
                <x-input-form-modal id="nomor_telaahan"
                    label="Nomor Telaahan"
                    name="nomor_telaahan"
                    placeholder="Masukkan Nomor Telaahan"
                    x-model="form.nomor_telaahan"
                    x-bind:disabled="mode === 'show'"
                    isShowMode="mode !== 'show'"
                    :mode="$mode" />
                {{-- input nomor_telaahan end --}}

                {{-- input tanggal_telaahan start --}}
                <x-input-form-modal id="tanggal_telaahan"
                    label="Tanggal Telaahan"
                    name="tanggal_telaahan"
                    type="text"
                    placeholder="Pilih tanggal telaahan"
                    x-model="form.tanggal_telaahan"
                    x-bind:disabled="mode === 'show'"
                    isShowMode="mode !== 'show'"
                    :mode="$mode"
                    />

                {{-- input tanggal_telaahan end --}}

                {{-- input perihal_kegiatan start --}}
                <x-input-form-modal divClass="lg:col-span-2"
                    id="perihal_kegiatan"
                    label="Perihal Kegiatan"
                    name="perihal_kegiatan"
                    placeholder="Permohonan Melaksanakan Kegiatan ..."
                    x-model="form.perihal_kegiatan"
                    x-bind:disabled="mode === 'show'"
                    isShowMode="mode !== 'show'"
                    :mode="$mode" />
                {{-- input perihal_kegiatan end --}}

                {{-- input tempat_pelaksanaan start --}}
                <x-input-form-modal id="tempat_pelaksanaan"
                    label="Tempat Pelaksanaan"
                    name="tempat_pelaksanaan"
                    placeholder="Masukkan Tempat Pelaksanaan"
                    x-model="form.tempat_pelaksanaan"
                    x-bind:disabled="mode === 'show'"
                    isShowMode="mode !== 'show'"
                    :mode="$mode" />
                {{-- input tempat_pelaksanaan end --}}

                {{-- input tanggal_mulai start --}}
                <x-input-form-modal id="tanggal_mulai"
                    label="Tanggal Mulai"
                    name="tanggal_mulai"
                    type="text"
                    placeholder="Pilih tanggal mulai kegiatan"
                    x-model="form.tanggal_mulai"
                    x-bind:disabled="mode === 'show'"
                    isShowMode="mode !== 'show'"
                    :mode="$mode" />
                {{-- input tanggal_mulai end --}}

                {{-- input tanggal_selesai start --}}
                <x-input-form-modal id="tanggal_selesai"
                    label="Tanggal Selesai"
                    name="tanggal_selesai"
                    type="text"
                    placeholder="Pilih tanggal selesai kegiatan"
                    x-model="form.tanggal_selesai"
                    x-bind:disabled="mode === 'show'"
                    isShowMode="mode !== 'show'"
                    :mode="$mode" />
                {{-- input tanggal_selesai end --}}

                {{-- input dasar_telaahan start --}}
                <x-input-form-modal divClass="lg:col-span-3"
                    id="dasar_telaahan"
                    label="Dasar Telaahan"
                    name="dasar_telaahan"
                    placeholder="Dalam rangka upaya ..."
                    x-model="form.dasar_telaahan"
                    x-bind:disabled="mode === 'show'"
                    isShowMode="mode !== 'show'"
                    :mode="$mode" />
                {{-- input dasar_telaahan end --}}

                {{-- input isi_telaahan start --}}
                <x-base-input-form-modal divClass="lg:col-span-3"
                    idLabel="isi_telaahan"
                    label="Isi Telaahan"
                    nameError="isi_telaahan"
                    isShowMode="mode !== 'show'">
                    <textarea x-model="form.isi_telaahan"
                        id="isi_telaahan"
                        autocomplete="off"></textarea>
                </x-base-input-form-modal>
                {{-- input isi_telaahan end --}}

                {{-- input pilih pegawai start --}}
                <x-base-input-form-modal divClass="lg:col-span-3"
                    idLabel="pegawais"
                    :label="$mode === 'show' ? 'Pegawai yang ditugaskan' : 'Pilih Pegawai yang ditugaskan'"
                    nameError="pegawais"
                    isShowMode="mode !== 'show'">
                    <select id="pegawais"
                        name="pegawais[]"
                        multiple>
                        {{-- options akan diisi dengan choices js --}}
                    </select>
                </x-base-input-form-modal>
                {{-- input pilih pegawai end --}}
            </div>

            <div x-cloak
                class="mt-6 flex justify-end gap-2 border-t pt-4"
                x-show="mode !== 'show'">
                <button type="submit"
                    x-bind:disabled="loading"
                    :class="{
                        'cursor-not-allowed bg-black/30 text-neutral-300': loading,
                        'bg-black text-neutral-100 hover:opacity-75 cursor-pointer':
                            !loading
                    }"
                    class="whitespace-nowrap rounded-sm border px-4 py-2 text-center text-sm font-medium tracking-wide transition-colors duration-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0">
                    <span x-show="!loading && mode === 'create'">Simpan</span>
                    <span x-show="!loading && mode === 'edit'">Update</span>
                    <span x-show="loading"
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
        </form>
    </div>

    {{-- tampil hanya ketika mode = detail, untuk keterangan dari kedua penyetuju telaah staf --}}
    @if ($mode === 'show')
        <x-status-telaah-staf-detail :data="$data" />
    @endif

    @if ($mode === 'show')
        {{-- iframe tampil pdf telaah staf --}}
        <iframe class="relative rounded-md border-0"
            src="{{ route('telaah-staf.cetak', $surat->id) }}"
            width="100%"
            height="600px"></iframe>
    @endif
</x-layouts.app-layout>
