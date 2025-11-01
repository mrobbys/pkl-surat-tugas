<x-form-modal show="showApproveModal"
    title="modalTitle"
    size="max-w-5xl"
    onClose="closeModal()"
    onKeydownEnter="submitApprove()"
    onSubmit="submitApprove()"
    loading="loading">
    {{-- input status start --}}
    <x-base-input-form-modal
        idLabel="status"
        label="Status"
        nameError="status">
        <select x-ref="status"
            id="status"
            class="w-full rounded-sm border border-neutral-300 bg-neutral-50 px-2 py-3 text-sm"
            name="status"
            x-model="approveForm.status">
            <option value=""
                disabled
                selected>Pilih status</option>
            <option value="disetujui">Disetujui</option>
            <option value="ditolak">Ditolak</option>
            <option value="revisi">Revisi</option>
        </select>
    </x-base-input-form-modal>
    {{-- input status end --}}

    {{-- input catatan start --}}
    <x-base-input-form-modal
        idLabel="catatan"
        label="Catatan"
        nameError="catatan"
        :required="false">
        <textarea id="catatan"
            class="h-32 w-full rounded-sm border border-neutral-300 bg-neutral-50 px-2 py-3 text-sm"
            name="catatan"
            placeholder="Masukkan catatan (opsional)"
            autocomplete="off"
            x-model="approveForm.catatan"></textarea>
    </x-base-input-form-modal>
    {{-- input catatan end --}}
</x-form-modal>
