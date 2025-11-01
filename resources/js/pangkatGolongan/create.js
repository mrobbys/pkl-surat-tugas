export const createMethods = {
  openCreateModal() {
    this.showModal = true;
    this.modalTitle = 'Tambah Pangkat Golongan';
    this.textSubmit = 'Simpan';

    this.$nextTick(() => {
      // Cek apakah bukan touch device
      const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
      if (!isTouchDevice) {
        this.$refs.pangkatInput.focus();
      }

      setTimeout(() => {
        this.validateForm('pangkat');
        this.validateForm('golongan');
        this.validateForm('ruang');
      });
    });
  },
}