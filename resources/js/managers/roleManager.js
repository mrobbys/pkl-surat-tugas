import { state } from '../roles/state.js';
import { indexMethods } from '../roles/index.js';
import { showMethods } from '../roles/show.js';
import { createMethods } from '../roles/create.js';
import { validationForm } from '../roles/validationForm.js';
import { editMethods } from '../roles/edit.js';
import { storeUpdateMethods } from '../roles/storeUpdate.js';
import { deleteMethods } from '../roles/delete.js';

function roleManager(config) {
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
    ...editMethods,
    ...storeUpdateMethods,
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
        name: '',
        permissions: [],
      };
      this.clearErrors();
    },

    // clear errors
    clearErrors() {
      this.errors = {};
    },

    // ambil data permissions
    async fetchPermissions() {
      try {
        const response = await fetch(this.config.permissionsUrl);
        const data = await response.json();
        this.permissions = data.data;
      } catch (error) {
        console.error('Error fetching permissions:', error);
      }
    },
  }
}

window.roleManager = roleManager;