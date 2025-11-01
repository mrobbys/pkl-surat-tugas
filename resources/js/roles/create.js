export const createMethods = {
  async openCreateModal() {
    this.modalTitle = 'Tambah Role';
    this.textSubmit = 'Simpan';
    this.showModal = true;
    this.isShowMode = false;

    this.$nextTick(async () => {
      await this.fetchPermissions();

      // Cek apakah bukan touch device
      const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
      if (!isTouchDevice) {
        this.$refs.nameInput.focus();
      }

      // validasi form
      setTimeout(() => {
        this.validateForm('name');
        this.validateForm('permissions');
      });
    });
  },
}