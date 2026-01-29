@php
    $config = [
        'indexUrl' => route('activity-logs.index'),
    ];
@endphp

<x-layouts.app-layout :title="'Activity Logs'">

    {{-- push style start --}}
    @push('styles')
        <style>
            #activity-log-table>tbody>tr {
                height: 65px;
            }
        </style>
    @endpush
    {{-- push style end --}}

    <div class="relative mb-20 mt-10 overflow-hidden rounded-xl bg-white px-4 shadow-xl"
        x-data="activityManager(@js($config))"
        x-init="fetchActivities()">
        <div class="w-full">

            {{-- table --}}
            <x-main-table :tableId="'activity-log-table'">
                <x-slot name="thead">
                    <x-main-th-table>No</x-main-th-table>
                    <x-main-th-table>User</x-main-th-table>
                    <x-main-th-table>Deskripsi</x-main-th-table>
                    <x-main-th-table>Tanggal</x-main-th-table>
                    <x-main-th-table>IP Address</x-main-th-table>
                    <x-main-th-table>Log Name</x-main-th-table>
                </x-slot>
            </x-main-table>

        </div>
    </div>


</x-layouts.app-layout>
