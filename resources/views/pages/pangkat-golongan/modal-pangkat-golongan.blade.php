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
    />
    {{-- input golongan end --}}

    {{-- input ruang start --}}
    <x-input-form-modal
        id="ruang"
        label="Ruang"
        name="ruang"
        placeholder="a, b, c, d, e"
        x-model="form.ruang"
        maxlength="1"
    />
    {{-- input ruang end --}}
</x-form-modal>
