export const deleteMethods = {
  async deletePangkatGolongan(id) {
    // cari data pangkat golongan berdasarkan id
    let pangkatGolongan = this.dataTable
      .data()
      .toArray()
      .find((p) => p.id == id);
    // console.log(`Hapus pangkat golongan dengan ID: ${id}`, pangkatGolongan);

    // konfirmasi sebelum menghapus
    const result = await Swal.fire({
      title: 'Apakah Anda yakin?',
      text: `Ingin menghapus data pangkat: "${pangkatGolongan.pangkat}"?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal',
    });

    // jika pengguna mengkonfirmasi
    if (result.isConfirmed) {
      try {
        const payload = {
          _method: 'DELETE',
        };

        const response = await fetch(this.config.deleteUrl.replace('__ID__', id), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify(payload),
        });
        const data = await response.json();
        if (response.ok) {
          Swal.fire({
            icon: data.icon,
            title: data.title,
            text: data.text,
            timer: 5000,
          });
          this.dataTable.ajax.reload();
        } else {
          Swal.fire({
            icon: data.icon,
            title: data.title,
            text: data.text,
          });
        }
      } catch (error) {
        console.error('Error', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Terjadi kesalahan saat menghapus data.',
        });
      }
    }
  },
}