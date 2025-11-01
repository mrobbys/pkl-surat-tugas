import { state } from '../surat/state.js';
import { stateTelaahStafForm } from '../surat/stateTelaahStafForm.js';
import { indexMethods } from '../surat/index.js';
import { telaahStafFormMethods } from '../surat/telaahStafForm.js'
import { approveTelaahStaf } from '../surat/approveTelaahStaf.js';
import { submitApproveTelaahStaf } from '../surat/submitApproveTelaahStaf.js';
import { deleteMethods } from '../surat/delete.js';

export function telaahStafManager(config) {
  return {
    // state awal
    ...state,

    // csrf token
    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

    // Simpan config dari Blade agar bisa diakses sebagai `this.config`
    config: config,

    // Gabungkan semua method
    ...indexMethods,
    ...approveTelaahStaf,
    ...submitApproveTelaahStaf,
    ...deleteMethods,

    resetApproveForm() {
      this.approveForm = {
        status: '',
        catatan: '',
        level: this.approveLevel,
      };
      this.clearErrors();
    },

    // clear errors
    clearErrors() {
      this.errors = {};
    },

    // close modal
    closeModal() {
      this.showModal = false;
      this.showApproveModal = false;
      this.approveId = null;
      this.approveLevel = 1;
      this.resetApproveForm();
      this.isShowMode = false;
    },

  }
}

export function telaahStafForm(mode, surat, data, formConfig) {
  return {
    ...stateTelaahStafForm,
    mode: mode,
    suratId: surat?.id || null,

    form: {
      kepada_yth: data?.kepada_yth || '',
      dari: data?.dari || '',
      nomor_telaahan: data?.nomor_telaahan || '',
      tanggal_telaahan: data?.tanggal_telaahan || '',
      perihal_kegiatan: data?.perihal_kegiatan || '',
      tempat_pelaksanaan: data?.tempat_pelaksanaan || '',
      tanggal_mulai: data?.tanggal_mulai || '',
      tanggal_selesai: data?.tanggal_selesai || '',
      dasar_telaahan: data?.dasar_telaahan || '',
      isi_telaahan: data?.isi_telaahan || '',
      pegawais: data?.pegawais?.map(p => p.id) || [],
    },

    pegawaisData: formConfig.pegawais || [],

    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

    config: formConfig,

    ...telaahStafFormMethods
  }
}

window.telaahStafManager = telaahStafManager;
window.telaahStafForm = telaahStafForm;