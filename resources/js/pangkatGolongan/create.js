import { focusIfNotTouch } from '../utils/touchDeviceDetection.js'

export const createMethods = {
  openCreateModal() {
    this.showModal = true;
    this.modalTitle = 'Tambah Pangkat Golongan';
    this.textSubmit = 'Simpan';

    this.$nextTick(() => {
      // Cek apakah bukan touch device
      focusIfNotTouch(this.$refs.pangkatInput);
    });
  },
}