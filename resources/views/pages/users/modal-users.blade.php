<x-form-modal show="showModal"
    title="modalTitle"
    size="max-w-5xl"
    onClose='closeModal()'
    onSubmit="saveUser()"
    onKeydownEnter=" ! isShowMode && ! (editingId ? ! isFormChanged() : false) && isFormValid()
            ? saveUser()
            : null"
    loading="loading"
    showSubmit="!isShowMode"
    submitDisabled="loading || (editingId ? ! isFormChanged() : false) || ! isFormValid()">

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        {{-- input nip start --}}
        <x-input-form-modal id="nip"
            label="NIP"
            name="nip"
            type="tel"
            placeholder="Masukkan NIP"
            x-model="form.nip"
            x-ref="nipInput"
            @input="validateForm('nip')"
            x-on:input="form.nip = form.nip.replace(/[^0-9]/g, '').slice(0, 18)"
            isShowMode="!isShowMode"
            pattern="\d{18}"
            maxlength="18"
            minlength="18"
            inputmode="numeric"
            x-bind:disabled="isShowMode" />
        {{-- input nip end --}}

        {{-- input nama lengkap start --}}
        <x-input-form-modal id="nama_lengkap"
            label="Nama Lengkap"
            name="nama_lengkap"
            placeholder="Masukkan Nama Lengkap"
            x-model="form.nama_lengkap"
            @input="validateForm('nama_lengkap')"
            x-on:input="form.nama_lengkap = form.nama_lengkap.replace(/[^a-zA-Z\s.,]/g, '')"
            isShowMode="!isShowMode"
            x-bind:disabled="isShowMode" />
        {{-- input nama lengkap end --}}

        {{-- input email start --}}
        <x-input-form-modal id="email"
            label="Email"
            name="email"
            type="email"
            placeholder="Masukkan Email"
            x-model="form.email"
            @input="validateForm('email')"
            isShowMode="!isShowMode"
            x-bind:disabled="isShowMode" />
        {{-- input email end --}}

        {{-- input password start --}}
        <x-input-password-form-modal id="password"
            label="Password"
            name="password"
            placeholder="Masukkan Password"
            x-model="form.password"
            isShowMode="!isShowMode"
            x-bind:disabled="isShowMode"
            @input="validateForm('password')" />
        {{-- input password end --}}

        {{-- input roles start --}}
        <x-base-input-form-modal idLabel="roles-select"
            label="Roles"
            isShowMode="!isShowMode"
            nameError="roles">
            <select @input="validateForm('roles')"
                id="roles-select"
                name="roles[]"
                multiple
                class="w-full"
                :disabled="isShowMode">
                {{-- options akan diisi dengan choices js --}}
            </select>
        </x-base-input-form-modal>
        {{-- input roles end --}}

        {{-- input pangkat golongan start --}}
        <x-base-input-form-modal idLabel="pangkatGolongan-select"
            label="Pangkat Golongan/Ruang"
            isShowMode="!isShowMode"
            nameError="pangkat_golongan_id">
            <select @input="validateForm('pangkat_golongan_id')"
                id="pangkatGolongan-select"
                name="pangkat_golongan_id"
                class="w-full"
                :disabled="isShowMode">
            </select>
        </x-base-input-form-modal>
        {{-- input pangkat golongan end --}}

        <x-input-form-modal id="jabatan"
            label="Jabatan"
            name="jabatan"
            placeholder="Masukkan Jabatan"
            x-model="form.jabatan"
            @input="validateForm('jabatan')"
            isShowMode="!isShowMode"
            x-bind:disabled="isShowMode" />
    </div>
</x-form-modal>
