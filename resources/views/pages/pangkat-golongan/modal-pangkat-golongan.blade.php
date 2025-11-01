<x-form-modal
    show="showModal"
    title="modalTitle"
    size="max-w-5xl"
    onClose="closeModal()"
    onSubmit="savePangkatGolongan()"
    onKeydownEnter="(editingId && isFormChanged()) || ! editingId ? savePangkatGolongan() : null"
    loading="loading"
    submitDisabled="loading || (editingId ? ! isFormChanged() : false) || ! isFormValid()"
>
    {{-- input pangkat start --}}
    <x-input-form-modal
        id="pangkat"
        label="Pangkat"
        name="pangkat"
        placeholder="Juru Muda, Pengatur Muda, Pembina"
        x-model="form.pangkat"
        x-ref="pangkatInput"
        @input="validateForm('pangkat')"
        maxlength="100"
    />

    {{-- input pangkat end --}}

    {{-- input golongan start --}}
    <x-input-form-modal
        id="golongan"
        label="Golongan"
        name="golongan"
        placeholder="I, II, III, IV"
        x-model="form.golongan"
        @input="validateForm('golongan')"
        pattern="^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$"
        x-on:input="form.golongan = form.golongan.replace(/[^ivxlcdmIVXLCDM]/g, '')"
    />
    {{-- input golongan end --}}

    {{-- input ruang start --}}
    <x-input-form-modal
        id="ruang"
        label="Ruang"
        name="ruang"
        placeholder="a, b, c, d"
        x-model="form.ruang"
        @input="validateForm('ruang')"
        x-on:input="form.ruang = form.ruang.replace(/[^a-z]/g, '')"
        maxlength="1"
    />
    {{-- input ruang end --}}
</x-form-modal>
