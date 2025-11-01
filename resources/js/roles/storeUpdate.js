export const storeUpdateMethods = {
  async saveRole() {
    // Validasi semua field sebelum submit
    const fields = ['name', 'permissions'];
    fields.forEach((f) => this.validateForm(f));
    if (!this.isFormValid()) return;

    this.loading = true;

    // tentukan URL dan method apakah create atau update
    const url = this.editingId
      ? this.config.updateUrl.replace('__ID__', this.editingId)
      : this.config.storeUrl;
    const method = this.editingId ? 'PUT' : 'POST';

    try {
      const response = await fetch(url, {
        method: method,
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': this.csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(this.form),
      });

      const data = await response.json();

      // jika response ok
      if (response.ok) {
        this.dataTable.ajax.reload();
        this.resetForm();
        // tampilkan notif sukses sweetalert
        Swal.fire({
          icon: data.icon,
          title: data.title,
          text: data.text,
          timer: 5000,
        });
        this.closeModal();
      } else {
        // jika terjadi error validasi (422)
        if (response.status === 422) {
          // tampilkan error di form
          this.errors = data.errors || {};
        } else {
          // status error lainnya dengan sweetalert
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text:
              data.text ||
              data.message ||
              'Terjadi kesalahan saat menyimpan data.',
            timer: 5000,
          });
        }
        console.error('Error saving user:', data);
      }
    } catch (error) {
      console.error('Error saving role:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Terjadi kesalahan saat menyimpan data.',
        timer: 5000,
      });
    } finally {
      this.loading = false;
    }
  },
}