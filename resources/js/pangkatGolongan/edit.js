import { focusIfNotTouch } from '../utils/touchDeviceDetection.js'

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
      this.originalForm = { ...data.data };
      this.form = { ...this.originalForm };
      this.showModal = true;

      this.$nextTick(() => {
        // Cek apakah bukan touch device
        focusIfNotTouch(this.$refs.pangkatInput);

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