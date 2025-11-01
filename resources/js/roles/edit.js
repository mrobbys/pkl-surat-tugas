export const editMethods = {
  async editRole(id) {
    try {
      const response  = await fetch(this.config.editUrl.replace('__ID__', id) );
      const data = await response.json();

      this.modalTitle = `Edit Role : ${data.data.name}`;
      this.textSubmit = 'Update';
      this.isShowMode = false;
      this.editingId = data.data.id;
      this.originalForm = {
        name: data.data.name,
        permissions: data.data.permissions.map((p) => p.id),
      };
      this.form = {
        ...this.originalForm
      };
      this.showModal = true;

      this.$nextTick(async () => {
        await this.fetchPermissions();

        this.$refs.nameInput.focus();
      });
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