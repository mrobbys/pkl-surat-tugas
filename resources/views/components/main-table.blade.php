@props(['tableId', 'thead' => null, 'tbody' => null])

{{--
    Props:
    - tableId: id table untuk inisialisasi datatables.
    - thead : elemen thead dari table (th).
    - tbody : elemen tbody dari table (tr, td). opsional karena tampil data ditbody menggunakan datatables.
--}}

<table class="stripe hover w-full text-sm text-neutral-600"
    id="{{ $tableId }}">
    <thead class="bg-[#edf2f9] text-sm text-neutral-900">
        <tr>
            {{ $thead ?? '' }}
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-500">
        {{ $tbody ?? '' }}
    </tbody>
</table>
