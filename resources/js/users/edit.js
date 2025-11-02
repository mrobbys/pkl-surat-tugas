import { focusIfNotTouch } from '../utils/touchDeviceDetection.js'

export const editMethods = {
  async editUser(id) {
    try {
      const response = await fetch(this.config.editUrl.replace('__ID__', id));
      const data = await response.json();

      const userData = data.data;

      this.modalTitle = `Edit User: ${userData.nama_lengkap}`;
      this.textSubmit = 'Update';
      this.isShowMode = false;
      this.editingId = userData.id;
      this.resetForm();
      this.originalForm = {
        nip: userData.nip,
        nama_lengkap: userData.nama_lengkap,
        email: userData.email,
        password: '',
        roles: userData.roles.map((role) => role.id.toString()),
        pangkat_golongan_id: userData.pangkat_golongan_id.toString(),
        jabatan: userData.jabatan,
      };
      this.form = { ...this.originalForm };
      this.showModal = true;

      // Initialize choices setelah modal terbuka
      this.$nextTick(async () => {
        this.initChoices();
        // Fetch data setelah choices diinisialisasi
        await this.fetchPangkatGolonganAndRoles();

        // Cek apakah bukan touch device
        focusIfNotTouch(this.$refs.nipInput);

        setTimeout(() => {
          this.setChoicesSelectedValues();
        }, 100);
      });
    } catch (error) {
      console.error('Error fetching user details for edit:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Terjadi kesalahan saat mengambil data user untuk diedit.',
        timer: 5000,
      });
    }
  },
}