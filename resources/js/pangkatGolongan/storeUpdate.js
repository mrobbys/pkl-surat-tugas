export const storeUpdateMethods = {
  async savePangkatGolongan() {
    // valiadsi semua field sebelum submit
    const fields = ['pangkat', 'golongan', 'ruang'];
    fields.forEach((field) => this.validateForm(field));
    if (!this.isFormValid()) return;

    this.loading = true;

    // tentukan URL dan method apakah sedang mengedit atau membuat baru berdasarkan id
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
        // mengubah objek 'form' menjadi JSON string
        body: JSON.stringify(this.form),
      });

      const data = await response.json();

      // jika response ok
      if (response.ok) {
        // refresh datatable
        this.dataTable.ajax.reload();
        this.resetForm();
        // tampilkan notifikasi sukses
        Swal.fire({
          icon: data.icon || 'success',
          title: data.title || 'Berhasil',
          text: data.text || 'Data berhasil disimpan.',
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
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Terjadi kesalahan saat menyimpan data.',
      });
    } finally {
      this.loading = false;
    }
  },
}