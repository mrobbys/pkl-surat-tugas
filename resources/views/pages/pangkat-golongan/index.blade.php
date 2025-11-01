@php
    $config = [
        'canEdit' => Auth::user()->can('edit pangkat golongan'),
        'canDelete' => Auth::user()->can('delete pangkat golongan'),
        'indexUrl' => route('pangkat-golongan.index'),
        'storeUrl' => route('pangkat-golongan.store'),
        'editUrl' => route('pangkat-golongan.edit', ['pangkat_golongan' => '__ID__']),
        'updateUrl' => route('pangkat-golongan.update', ['pangkat_golongan' => '__ID__']),
        'deleteUrl' => route('pangkat-golongan.destroy', ['pangkat_golongan' => '__ID__']),
    ];
@endphp

<x-layouts.app-layout :title="'Pangkat Golongan'">
    <div
        class="relative mt-10 mb-20 overflow-hidden rounded-xl bg-white p-4 shadow-xl"
        x-data="pangkatGolonganManager(@js($config))"
        x-init="fetchPangkatGolongans()"
    >
        <div class="w-full">
            @can("create pangkat golongan")
                <div class="my-4 flex justify-end">
                    {{-- btn create --}}
                    <x-btn-create-table :click="'openCreateModal()'">
                        Tambah Data
                    </x-btn-create-table>
                </div>
            @endcan

            {{-- table --}}
            <x-main-table :tableId="'pangkat-golongan-table'">
                <x-slot name="thead">
                    <x-main-th-table>No</x-main-th-table>
                    <x-main-th-table>Pangkat</x-main-th-table>
                    <x-main-th-table>Golongan</x-main-th-table>
                    <x-main-th-table>Ruang</x-main-th-table>
                    {{-- tampilkan aksi jika user memiliki permissions --}}
                    @if (Auth::user()->can("edit pangkat golongan") || Auth::user()->can("delete pangkat golongan"))
                        <x-main-th-table style="text-align: center">Aksi</x-main-th-table>
                    @endif
                </x-slot>
            </x-main-table>
        </div>
        {{-- form modal --}}
        @include("pages.pangkat-golongan.modal-pangkat-golongan")
    </div>
</x-layouts.app-layout>
