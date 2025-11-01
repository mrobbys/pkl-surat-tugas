export const showMethods = {
  async detailRole(id) {
    try {
      const response = await fetch(this.config.showUrl.replace('__ID__', id));
      const data = await response.json();

      this.modalTitle = `Detail Role : ${data.data.name}`;
      this.textSubmit = 'Tutup';
      this.isShowMode = true;
      this.form.name = data.data.name;
      this.permissionNames = data.data.permissions.map((p) => p.name);
      this.showModal = true;
    } catch (error) {
      console.error('Error fetching role data:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Terjadi kesalahan saat mengambil data role.',
        timer: 5000,
      });
    }
  },
}