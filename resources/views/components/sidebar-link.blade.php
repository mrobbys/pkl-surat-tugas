@props([
    "href" => "#",
    "icon" => "ri-home-2-line",
    "colorIcon" => "text-blue-500",
])

{{--
    Props:
    - href: url link halaman yang dituju.
    - icon: icon untuk sidebar link menggunakan remix icon.
    - colorIcon: class warna untuk icon.
    - slot: isi text dari link.
--}}

<a
    href="{{ $href }}"
    :class="window.location.pathname.startsWith('{{ parse_url($href, PHP_URL_PATH) }}') ? 'bg-[#ecf3ff]' : ''"
    class="flex items-center gap-2 rounded-sm px-2 py-1.5 text-sm underline-offset-2 hover:bg-[#ecf3ff] focus:outline-hidden focus-visible:underline"
>
    <i class="{{ $icon }} {{ $colorIcon }} text-2xl"></i>
    <span
        :class="window.location.pathname.startsWith('{{ parse_url($href, PHP_URL_PATH) }}') ? 'font-bold' : 'font-medium'"
    >
        {{ $slot }}
    </span>
</a>
