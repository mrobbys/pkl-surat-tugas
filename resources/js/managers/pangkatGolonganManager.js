import { state } from '../pangkatGolongan/state.js';
import { indexMethods } from '../pangkatGolongan/index.js';
import { createMethods } from '../pangkatGolongan/create.js';
import { validationForm } from '../pangkatGolongan/validationForm.js';
import { storeUpdateMethods } from '../pangkatGolongan/storeUpdate.js';
import { editMethods } from '../pangkatGolongan/edit.js';
import { deleteMethods } from '../pangkatGolongan/delete.js';

function pangkatGolonganManager(config) {
  return {
    // state awal
    ...state,

    // crsf token
    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

    // Simpan config dari Blade agar bisa diakses sebagai `this.config`
    config: config,

    // Gabungkan semua method
    ...indexMethods,
    ...createMethods,
    ...validationForm,
    ...storeUpdateMethods,
    ...editMethods,
    ...deleteMethods,

    // close modal
    closeModal() {
      this.showModal = false;
      this.editingId = null;
      this.resetForm();
    },

    // reset form
    resetForm() {
      this.form = {
        pangkat: '',
        golongan: '',
        ruang: '',
      };
      this.clearErrors();
    },

    // clear errors
    clearErrors() {
      this.errors = {};
    },
  }
}

window.pangkatGolonganManager = pangkatGolonganManager;