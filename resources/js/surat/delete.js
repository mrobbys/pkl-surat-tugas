export const deleteMethods = {
  async deleteTelaahStaf(id) {
    // cari data berdasarkan id
    let telaahStaf = this.dataTable
      .data()
      .toArray()
      .find((ts) => ts.id == id);

    // konfirmasi hapus
    const result = await Swal.fire({
      title: 'Apakah Anda yakin?',
      text: `Ingin menghapus Surat Telaah Staf dengan nomor: ${telaahStaf.nomor_telaahan}`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal',
    });

    // jika user konfirmasi hapus
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

        if (!response.ok) {
          throw new Error(data.text || 'Gagal menghapus surat.')
        }

        await Swal.fire({
          icon: data.icon,
          title: data.title,
          text: data.text,
          timer: 5000,
        });
        this.dataTable.ajax.reload();
      } catch (error) {
        console.error('Error deleting telaah staf:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Terjadi kesalahan saat menghapus data telaah staf.',
          timer: 5000,
        });
      }
    }
  },
}