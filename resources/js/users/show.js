export const showMethods = {
  async detailUser(id) {
    try {
      const response = await fetch(this.config.showUrl.replace('__ID__', id));
      const data = await response.json();

      const userData = data.data;

      this.modalTitle = `Detail User: ${userData.nama_lengkap}`;
      this.isShowMode = true;
      this.resetForm();
      this.form = {
        nip: userData.nip,
        nama_lengkap: userData.nama_lengkap,
        email: userData.email,
        password: '',
        roles: userData.roles.map((role) => role.id.toString()),
        pangkat_golongan_id: userData.pangkat_golongan_id.toString(),
        jabatan: userData.jabatan,
      };
      this.showModal = true;
      // Initialize choices setelah modal terbuka
      this.$nextTick(async () => {
        this.initChoices();
        // Fetch data setelah choices diinisialisasi
        await this.fetchPangkatGolonganAndRoles();

        setTimeout(() => {
          this.setChoicesSelectedValues();
        }, 100);
      });
    } catch (error) {
      console.error('Error fetching user details:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Terjadi kesalahan saat mengambil detail user.',
        timer: 5000,
      });
    }
  },
}