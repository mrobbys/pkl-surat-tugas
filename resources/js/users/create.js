import { focusIfNotTouch } from '../utils/touchDeviceDetection.js'

export const createMethods = {
  async openCreateModal() {
    this.modalTitle = 'Tambah User Baru';
    this.textSubmit = 'Simpan';
    this.isShowMode = false;
    this.showModal = true;
    this.resetForm();

    // Initialize choices setelah modal terbuka
    this.$nextTick(async () => {
      this.initChoices();
      // Fetch data setelah choices diinisialisasi
      await this.fetchPangkatGolonganAndRoles();

      // Cek apakah bukan touch device
      focusIfNotTouch(this.$refs.nipInput);
    });
  },
}