export const editMethods = {
  async editPangkatGolongan(id) {
    let pangkatGolongan = this.dataTable
      .data()
      .toArray()
      .find((p) => p.id == id);
    // console.log(`Edit pangkat golongan dengan ID: ${id}`, pangkatGolongan);

    try {
      const response = await fetch(
        this.config.editUrl.replace('__ID__', pangkatGolongan.id),
      );
      const data = await response.json();

      this.modalTitle = 'Edit Pangkat Golongan';
      this.textSubmit = 'Update';
      this.editingId = pangkatGolongan.id;
      this.originalForm = {
        pangkat: data.data.pangkat,
        golongan: data.data.golongan,
        ruang: data.data.ruang,
      };
      this.form.pangkat = data.data.pangkat;
      this.form.golongan = data.data.golongan;
      this.form.ruang = data.data.ruang;
      this.showModal = true;

      this.$nextTick(() => {
        // Cek apakah bukan touch device
        const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        if (!isTouchDevice) {
          this.$refs.pangkatInput.focus();
        }

      });
    } catch (error) {
      console.error('Error:', error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Terjadi kesalahan saat mengambil data.',
      });
    }
  },
}