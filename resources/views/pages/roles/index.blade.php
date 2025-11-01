@php
    $config = [
        'canEdit' => Auth::user()->can('edit roles'),
        'canDelete' => Auth::user()->can('delete roles'),
        'indexUrl' => route('roles.index'),
        'storeUrl' => route('roles.store'),
        'permissionsUrl' => route('roles.create'),
        'showUrl' => route('roles.show', ['role' => '__ID__']),
        'editUrl' => route('roles.edit', ['role' => '__ID__']),
        'updateUrl' => route('roles.update', ['role' => '__ID__']),
        'deleteUrl' => route('roles.destroy', ['role' => '__ID__']),
    ];
@endphp

<x-layouts.app-layout :title="'Manajemen Role'">
    <div class="relative mb-20 mt-10 overflow-hidden rounded-xl bg-white px-4 shadow-xl"
        x-data="roleManager(@js($config))"
        x-init="fetchRoles()">
        <div class="w-full">
            @can('create roles')
                <div class="my-4 flex justify-end">
                    {{-- btn create --}}
                    <x-btn-create-table :click="'openCreateModal()'">
                        Tambah Role
                    </x-btn-create-table>
                </div>
            @endcan

            {{-- table --}}
            <x-main-table tableId="role-table">
                <x-slot name="thead">
                    <x-main-th-table>No</x-main-th-table>
                    <x-main-th-table>Nama Role</x-main-th-table>
                    {{-- tampilkan aksi jika user memiliki permissions --}}
                    @if (Auth::user()->can('edit roles') || Auth::user()->can('delete roles'))
                        <x-main-th-table style="text-align: center">Aksi</x-main-th-table>
                    @endif
                </x-slot>
            </x-main-table>
        </div>
        {{-- form modal --}}
        @include('pages.roles.modal-roles')
    </div>
</x-layouts.app-layout>
