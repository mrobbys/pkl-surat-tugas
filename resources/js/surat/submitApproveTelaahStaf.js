export const submitApproveTelaahStaf = {
  // approve telaah staf
  async submitApprove() {
    this.loading = true;

    try {
      // tentukan URL berdasarkan level approve
      const url =
        this.approveLevel === 1
          ? this.config.approveTSLevel1Url.replace('__ID__', this.approveId)
          : this.config.approveTSLevel2Url.replace('__ID__', this.approveId);

      const response = await fetch(url, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': this.csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(this.approveForm),
      });

      const data = await response.json();

      if (response.ok) {
        this.dataTable.ajax.reload();
        this.resetApproveForm();
        Swal.fire({
          icon: data.icon,
          title: data.title,
          text: data.text,
          timer: 3000,
        });
        this.closeModal();
      } else {
        if (response.status === 422) {
          this.errors = data.errors || {};
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text:
              data.text ||
              data.message ||
              'Terjadi kesalahan saat menyetujui telaah staf.',
            timer: 5000,
          });
        }
      }
    } catch (error) {
      console.error('Error approve telaah staf:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Terjadi kesalahan saat menyetujui telaah staf.',
        timer: 5000,
      });
    } finally {
      this.loading = false;
    }
  },
}