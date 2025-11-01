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
      const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
      if (!isTouchDevice) {
        this.$refs.nipInput.focus();
      }

      // Validasi form
      setTimeout(() => {
        this.validateForm('nip');
        this.validateForm('nama_lengkap');
        this.validateForm('email');
        this.validateForm('password');
        this.validateForm('jabatan');
      });
    });
  },
}