import { focusIfNotTouch } from '../utils/touchDeviceDetection.js'

export const createMethods = {
  async openCreateModal() {
    this.modalTitle = 'Tambah Role';
    this.textSubmit = 'Simpan';
    this.showModal = true;
    this.isShowMode = false;

    this.$nextTick(async () => {
      await this.fetchPermissions();

      // Cek apakah bukan touch device
      focusIfNotTouch(this.$refs.nameInput);
    });
  },
}