@php
    $config = [
    'canEdit' => Auth::user()->can('edit telaah staf'),
    'canDelete' => Auth::user()->can('delete telaah staf'),
    'canApproveTSLevel1' => Auth::user()->can('approve telaah staf level 1'),
    'canApproveTSLevel2' => Auth::user()->can('approve telaah staf level 2'),
    'canPDFTelaahStaf' => Auth::user()->can('pdf telaah staf'),
    'canPDFNotaDinas' => Auth::user()->can('pdf nota dinas'),
    'canPDFSuratTugas' => Auth::user()->can('pdf surat tugas'),
    'indexUrl' => route('surat.index'),
    'deleteUrl' => route('telaah-staf.destroy', ['surat' => '__ID__']),
    'approveTSLevel1Url' => route('telaah-staf.approve-satu', ['surat' => '__ID__']),
    'approveTSLevel2Url' => route('telaah-staf.approve-dua', ['surat' => '__ID__']),
];
@endphp

<x-layouts.app-layout :title="'Data Surat Tugas'">
    {{-- push style end --}}

    <div class="relative mb-20 mt-10 overflow-hidden rounded-xl bg-white p-4 shadow-xl"
        x-data="telaahStafManager(@js($config))"
        x-init="fetchTelaahStaf()">
        <div class="w-full">
            @can('create telaah staf')
                <div class="my-4 flex justify-end">
                    {{-- btn create --}}
                    <a href="{{ route('telaah-staf.create') }}"
                        class="flex cursor-pointer items-center justify-center gap-1 rounded-lg border border-neutral-300 bg-white px-4 py-2 font-semibold text-green-500 shadow-sm transition-all duration-300 hover:text-green-700 hover:shadow-md focus:outline-none">
                        <i class="ri-add-large-fill text-lg"></i>
                        Tambah Data
                    </a>
                </div>
            @endcan

            {{-- table --}}
            <x-main-table :tableId="'surat-table'">
                <x-slot name="thead">
                    <x-main-th-table>No</x-main-th-table>
                    <x-main-th-table>Nomor Telaahan</x-main-th-table>
                    <x-main-th-table>Tanggal Diajukan</x-main-th-table>
                    <x-main-th-table>Dibuat Oleh</x-main-th-table>
                    <x-main-th-table>Status</x-main-th-table>
                    <x-main-th-table style="text-align: center">Aksi</x-main-th-table>
                </x-slot>
            </x-main-table>
        </div>
        {{-- form modal approve --}}
        @include('pages.surat.form-approve-telaah-staf-modal')
    </div>
</x-layouts.app-layout>
