import { state } from '../users/state.js';
import { indexMethods } from '../users/index.js';
import { showMethods } from '../users/show.js';
import { createMethods } from '../users/create.js';
import { validationForm } from '../users/validationForm.js';
import { storeUpdateMethods } from '../users/storeUpdate.js';
import { editMethods } from '../users/edit.js';
import { deleteMethods } from '../users/delete.js';

function userManager(config) {
  return {
    // state awal
    ...state,

    // crsf token
    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

    // Simpan config dari Blade agar bisa diakses sebagai `this.config`
    config: config,

    // Gabungkan semua method
    ...indexMethods,
    ...showMethods,
    ...createMethods,
    ...validationForm,
    ...storeUpdateMethods,
    ...editMethods,
    ...deleteMethods,

    // ambil data pangkat golongan dan roles
    async fetchPangkatGolonganAndRoles() {
      try {
        const response = await fetch(this.config.createUrl);
        const data = await response.json();
        this.pangkatGolongan = data.data.pangkat_golongan;
        this.roles = data.data.all_roles;

        // Update choices setelah data tersedia
        this.updateChoicesOptions();
      } catch (error) {
        console.error('Error fetching pangkat golongan and roles:', error);
      }
    },

    initChoices() {
      const isDetailMode = this.isShowMode;

      // inisialisasi Choices.js untuk input roles multiple
      const rolesElement = document.getElementById('roles-select');
      if (rolesElement && !this.rolesChoice) {
        this.rolesChoice = new Choices(rolesElement, {
          removeItemButton: !isDetailMode,
          searchResultLimit: 5,
          placeholderValue: isDetailMode ? '' : 'Pilih Role',
          noResultsText: 'Tidak ada hasil',
          noChoicesText: 'Tidak ada pilihan',
          itemSelectText: 'Klik untuk memilih',
          shouldSort: false,
          searchEnabled: !isDetailMode,
          searchPlaceholderValue: isDetailMode ? '' : 'Cari role...',
          classNames: {
            containerOuter: ['choices'],
            containerInner: ['choices__inner'],
            input: ['choices__input'],
            button: ['choices__button'],
            listItems: ['choices__list--multiple'],
          },
        });

        // Event listener hanya jika bukan detail mode
        if (!isDetailMode) {
          rolesElement.addEventListener('change', (event) => {
            const selectedValues = this.rolesChoice.getValue();
            this.form.roles = Array.isArray(selectedValues)
              ? selectedValues.map((item) => item.value)
              : [];
          });
        }
      }

      // inisialisasi Choices.js untuk input pangkat golongan
      const pangkatGolonganElement =
        document.getElementById('pangkatGolongan-select');
      if (pangkatGolonganElement && !this.pangkatGolonganChoice) {
        this.pangkatGolonganChoice = new Choices(pangkatGolonganElement, {
          removeItemButton: !isDetailMode,
          searchResultLimit: 5,
          searchEnabled: !isDetailMode,
          searchPlaceholderValue: isDetailMode ? '' : 'Cari pangkat golongan...',
          placeholderValue: isDetailMode ? '' : 'Pilih Pangkat Golongan',
          noChoicesText: 'Tidak ada pilihan',
          noResultsText: 'Tidak ada hasil',
          shouldSort: false,
          classNames: {
            containerOuter: ['choices', 'custom-choices-container'],
          },
        });

        // Event listener hanya jika bukan detail mode
        if (!isDetailMode) {
          pangkatGolonganElement.addEventListener('change', (event) => {
            const selectedValue = this.pangkatGolonganChoice.getValue();
            this.form.pangkat_golongan_id = selectedValue ? selectedValue.value : '';
          });
        }
      }
    },

    // function update choice options
    updateChoicesOptions() {
      // Update roles options
      if (this.rolesChoice && this.roles) {
        // bersihkan opsi yang ada
        this.rolesChoice.clearStore();

        // tambahkan opsi baru
        const rolesOptions = this.roles.map((role) => ({
          value: role.id.toString(),
          label: role.name,
          selected: this.form.roles.includes(role.id.toString()),
        }));

        this.rolesChoice.setChoices(rolesOptions, 'value', 'label', false);
      }

      // Update pangkat golongan options
      if (this.pangkatGolonganChoice && this.pangkatGolongan) {
        // Clear existing options
        this.pangkatGolonganChoice.clearStore();

        // Add new options
        const pangkatGolonganOptions = this.pangkatGolongan.map((pg) => ({
          value: pg.id.toString(),
          label: `${pg.pangkat} - ${pg.golongan}/${pg.ruang}`,
          selected: this.form.pangkat_golongan_id === pg.id.toString(),
        }));

        this.pangkatGolonganChoice.setChoices(
          pangkatGolonganOptions,
          'value',
          'label',
          false,
        );
      }
    },

    setChoicesSelectedValues() {
      // atur selected roles
      if (this.rolesChoice && this.form.roles.length > 0) {
        this.rolesChoice.removeActiveItems();
        this.form.roles.forEach((roleId) => {
          this.rolesChoice.setChoiceByValue(roleId);
        });
      }

      // atur selected pangkat golongan
      if (this.pangkatGolonganChoice && this.form.pangkat_golongan_id) {
        this.pangkatGolonganChoice.removeActiveItems();
        this.pangkatGolonganChoice.setChoiceByValue(
          this.form.pangkat_golongan_id.toString(),
        );
      }
    },

    // // debug selected roles
    // logSelectedRoles() {
    //     console.log('Selected roles:', this.form.roles);
    // },

    // // debug selected pangkat golongan
    // logSelectedPangkatGolongan() {
    //     console.log('Selected pangkat golongan ID:', this.form.pangkat_golongan_id);
    // },

    // close modal
    closeModal() {
      this.showModal = false;
      this.editingId = null;
      this.resetForm();

      if (this.rolesChoice) {
        this.rolesChoice.destroy();
        this.rolesChoice = null;
      }
      if (this.pangkatGolonganChoice) {
        this.pangkatGolonganChoice.destroy();
        this.pangkatGolonganChoice = null;
      }
    },

    // reset form
    resetForm() {
      this.form = {
        nip: '',
        nama_lengkap: '',
        email: '',
        password: '',
        roles: [],
        pangkat_golongan_id: '',
        jabatan: '',
      };
      this.clearErrors();

      // Reset choices values
      if (this.rolesChoice) {
        this.rolesChoice.removeActiveItems();
      }
      if (this.pangkatGolonganChoice) {
        this.pangkatGolonganChoice.removeActiveItems();
      }
    },

    // clear errors
    clearErrors() {
      this.errors = {};
    },
  }
}

window.userManager = userManager;