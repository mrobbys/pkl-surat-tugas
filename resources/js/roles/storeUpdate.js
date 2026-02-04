export const storeUpdateMethods = {
  async saveRole() {

    // validasi form sebelum submit
    if (!this.isFormValid()) {
      Swal.fire({
        icon: 'warning',
        title: 'Perhatian',
        text: 'Mohon lengkapi semua field yang wajib diisi.',
      });
      return;
    }

    this.loading = true;

    const url = this.editingId
      ? this.config.updateUrl.replace('__ID__', this.editingId)
      : this.config.storeUrl;

    try {
      const payload = {...this.form };

      if (this.editingId) {
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
        await Swal.fire({
          icon: data.icon || 'success',
          title: data.title || 'Berhasil',
          text: data.text || 'Data berhasil disimpan',
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
              'Terjadi kesalahan saat submit form.',
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
        text: 'Terjadi kesalahan saat submit form.',
        timer: 5000,
      });
    } finally {
      this.loading = false;
    }
  },
}