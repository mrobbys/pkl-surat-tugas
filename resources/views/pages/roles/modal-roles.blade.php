<x-form-modal show="showModal"
    title="modalTitle"
    size="max-w-5xl"
    onClose="closeModal()"
    onSubmit="saveRole()"
    onKeydownEnter="(editingId && isFormChanged()) || !editingId ? saveRole() : null"
    loading="loading"
    showSubmit="!isShowMode"
    submitDisabled="loading || (editingId ? !isFormChanged() : false) || !isFormValid()">
    {{-- input nama role start --}}
    <x-input-form-modal isShowMode="!isShowMode"
        id="name"
        label="Nama Role"
        name="name"
        placeholder="kabid, kabag-kepegawaian, kadis"
        x-model="form.name"
        x-ref="nameInput"
        x-bind:disabled="isShowMode" />

    {{-- input permissions start --}}
    <div x-show="!isShowMode"
        class="flex w-full flex-col gap-1 text-neutral-600 lg:max-h-80">
        <label class="w-fit pl-0.5 text-sm font-bold">
            Permissions <span class="text-red-500">*</span>
        </label>
        <hr class="text-neutral-300" />
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            <template x-for="(groupPermissions, groupName) in permissions"
                :key="groupName">
                <div class="mt-2">
                    <div class="mb-1 text-sm font-bold"
                        x-text="groupName"></div>
                    <template x-for="permission in groupPermissions"
                        :key="permission.id">
                        <div class="flex items-center gap-2">
                            <input type="checkbox"
                                :id="'permission_' + permission.id"
                                :value="permission.id"
                                x-model="form.permissions"
                                class="rounded border-neutral-300" />
                            <label :for="'permission_' + permission.id"
                                class="text-sm text-neutral-600"
                                x-text="permission.name"></label>
                        </div>
                    </template>
                </div>
            </template>
        </div>
        <small class="text-red-500"
            x-show="errors.permissions"
            x-text="errors.permissions"></small>
    </div>
    {{-- input permissions end --}}

    {{-- Detail Permissions ketika mode detail --}}
    <div x-show="isShowMode"
        class="flex w-full flex-col gap-1 text-neutral-600">
        <label class="w-fit pl-0.5 text-sm font-bold">Permissions</label>
        <template x-if="permissionNames && permissionNames.length > 0">
            <ul class="list-inside list-disc">
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-4">
                    <template x-for="name in permissionNames"
                        :key="name">
                        <li x-text="name"></li>
                    </template>
                </div>
            </ul>
        </template>
        <template x-if="!permissionNames || permissionNames.length === 0">
            <p class="text-sm italic text-gray-500">Tidak ada permissions yang dipilih</p>
        </template>
    </div>
</x-form-modal>
