export const createMethods = {
  async openCreateModal() {
    this.modalTitle = 'Tambah Role';
    this.textSubmit = 'Simpan';
    this.showModal = true;
    this.isShowMode = false;

    this.$nextTick(async () => {
      await this.fetchPermissions();

      // validasi form
      setTimeout(() => {
        this.$refs.nameInput.focus();

        this.validateForm('name');
        this.validateForm('permissions');
      });
    });
  },
}