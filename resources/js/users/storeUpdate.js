export const storeUpdateMethods = {
  async saveUser() {
    // Validasi semua field sebelum submit
    const fields = ['nip', 'nama_lengkap', 'email', 'password', 'jabatan'];
    fields.forEach((f) => this.validateForm(f));
    if (!this.isFormValid()) return;

    this.loading = true;

    // menentukan url dan method apakah create atau update
    const isUpdate = !!this.editingId;
    const url = isUpdate
      ? this.config.updateUrl.replace('__ID__', this.editingId)
      : this.config.storeUrl;


    try {

      const payload = { ...this.form };

      // Untuk update, hanya kirim password jika diisi
      if (isUpdate) {
        if (!payload.password || payload.password.trim() === '') {
          delete payload.password; // Hapus password jika kosong
        }
        payload._method = 'PUT';
      }

      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': this.csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
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
        // console.log('User saved successfully:', data);
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
              'Terjadi kesalahan saat submit form.',
            timer: 5000,
          });
        }
        console.error('Error saving user:', data);
      }
    } catch (error) {
      console.error('Error saving user:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Terjadi kesalahan saat submit form.',
        timer: 5000,
      });
    } finally {
      this.loading = false;
    }
  },
}