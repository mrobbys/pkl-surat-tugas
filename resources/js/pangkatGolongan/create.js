export const createMethods = {
  openCreateModal() {
    this.showModal = true;
    this.modalTitle = 'Tambah Pangkat Golongan';
    this.textSubmit = 'Simpan';

    this.$nextTick(() => {
      this.$refs.pangkatInput.focus();

      setTimeout(() => {
        this.validateForm('pangkat');
        this.validateForm('golongan');
        this.validateForm('ruang');
      });
    });
  },
}