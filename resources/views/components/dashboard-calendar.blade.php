@props(['calendarId', 'customClass' => ''])

{{-- 
    Props:
    - calendarId: id untuk elemen kalendar (ada 2 calendar-desktop dan calendar-mobile)
    - customClass: kelas tambahan untuk styling kustom
--}}

<div class="relative rounded-xl bg-white p-4 shadow-lg {{ $customClass }}">
    <h2 class="mb-4 text-xl font-semibold text-gray-800">Kalender Surat Tugas</h2>
    <div id="{{ $calendarId }}"></div>
</div>
