@props([
    'class' => null,
    'style' => null,
])

{{--
    Props:
    - class (opsional): tambahan class untuk elemen <th>.
    - style (opsional): tambahan style inline untuk elemen <th>.
--}}

<th scope="col" class="{{ $class ?? "" }} px-6 py-3 whitespace-nowrap" style="{{ $style ?? "" }}">
    {{ $slot }}
</th>
