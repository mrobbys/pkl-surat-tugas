export const approveTelaahStaf = {
  // open modal approve satu
  approveTelaahStafSatu(id) {
    let telaahStaf = this.dataTable
      .data()
      .toArray()
      .find((ts) => ts.id == id);
    // console.log(`Approve telaah staf: ${id}`, telaahStaf);

    this.approveId = id;
    this.approveLevel = 1;
    this.modalTitle = `Setujui Telaah Staf Nomor: ${telaahStaf.nomor_telaahan}`;
    this.textSubmit = 'Simpan';
    this.resetApproveForm();
    this.showApproveModal = true;

    this.$nextTick(() => {
      // Cek apakah bukan touch device
      const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
      if (!isTouchDevice) {
        this.$refs.status.focus();
      }
    });
  },

  // open modal approve dua
  approveTelaahStafDua(id) {
    let telaahStaf = this.dataTable
      .data()
      .toArray()
      .find((ts) => ts.id == id);
    // console.log(`Approve dua telaah staf: ${id}`, telaahStaf);

    this.approveId = id;
    this.approveLevel = 2;
    this.modalTitle = `Setujui Telaah Staf Nomor: ${telaahStaf.nomor_telaahan}`;
    this.textSubmit = 'Simpan';
    this.resetApproveForm();
    this.showApproveModal = true;

    this.$nextTick(() => {
      this.$refs.status.focus();
    });
  },
}