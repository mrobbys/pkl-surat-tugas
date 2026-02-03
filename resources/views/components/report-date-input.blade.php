@props(['name', 'label', 'placeholder' => 'Pilih tanggal', 'class' => ''])

{{-- 
  Props:
  - name: digunakan untuk atribut for pada label dan atribut id & name pada input
  - label: teks label untuk input
  - placeholder: teks placeholder untuk input (default: 'Pilih tanggal')
  - class: kelas tambahan untuk input (default: '')
--}}

<div>
    <label for="{{ $name }}"
        class="mb-1.5 block text-sm font-medium text-zinc-600">
        {{ $label }}
    </label>
    <input type="text"
        id="{{ $name }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' =>
                $class .
                ' w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-zinc-900 outline-none ring-0 transition-transform duration-300 hover:scale-105',
        ]) }}>
    @error($name)
        <small class="mt-1 block text-sm text-red-500">{{ $message }}</small>
    @enderror
</div>
