@php
    $config = [
        'canEdit' => Auth::user()->can('edit users'),
        'canDelete' => Auth::user()->can('delete users'),
        'indexUrl' => route('users.index'),
        'storeUrl' => route('users.store'),
        'createUrl' => route('users.create'),
        'showUrl' => route('users.show', ['user' => '__ID__']),
        'editUrl' => route('users.edit', ['user' => '__ID__']),
        'updateUrl' => route('users.update', ['user' => '__ID__']),
        'deleteUrl' => route('users.destroy', ['user' => '__ID__']),
];
@endphp

<x-layouts.app-layout :title="'Manajemen User'">
    {{-- push style start --}}
    @push("styles")
        <style>
            #user-table > tbody > tr > td:nth-child(2) {
                text-align: center;
            }
        </style>
    @endpush

    {{-- push style end --}}

    <div
        class="relative mt-10 mb-20 overflow-hidden rounded-xl bg-white p-4 shadow-xl"
        x-data="userManager(@js($config))"
        x-init="fetchUsers()"
    >
        <div class="w-full">
            @can("create users")
                <div class="my-4 flex justify-end">
                    {{-- btn create --}}
                    <x-btn-create-table :click="'openCreateModal()'">
                        Tambah User
                    </x-btn-create-table>
                </div>
            @endcan

            {{-- table --}}
            <x-main-table :tableId="'user-table'">
                <x-slot name="thead">
                    <x-main-th-table>No</x-main-th-table>
                    <x-main-th-table :style="'text-align: center'">NIP</x-main-th-table>
                    <x-main-th-table>Nama Lengkap</x-main-th-table>
                    <x-main-th-table>Email</x-main-th-table>
                    <x-main-th-table>Role</x-main-th-table>
                    {{-- tampilkan aksi jika user memiliki permissions --}}
                    @if (Auth::user()->can("edit users") || Auth::user()->can("delete users"))
                        <x-main-th-table style="text-align: center">Aksi</x-main-th-table>
                    @endif
                </x-slot>
            </x-main-table>
        </div>
        {{-- form modal --}}
        @include("pages.users.modal-users")
    </div>
</x-layouts.app-layout>
