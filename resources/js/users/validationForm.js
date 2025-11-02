export const validationForm = {
  // cek apakah form valid
  isFormValid() {
    // editing id
    const isEditing = this.editingId;

    let passwordValid = false;
    if (isEditing) {
      // Edit: password boleh kosong ATAU terisi
      passwordValid =
        this.form.password.length === 0 || this.form.password.length > 0;
    } else {
      // Create: password wajib diisi
      passwordValid =
        this.form.password && this.form.password.length > 0;
    }

    // regex email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // semua field terisi
    return (
      this.form.nip &&
      this.form.nama_lengkap &&
      this.form.email &&
      this.form.email.match(emailRegex) &&
      passwordValid &&
      this.form.roles.length > 0 &&
      this.form.pangkat_golongan_id &&
      this.form.jabatan
    );
  },

  // cek apakah form berubah (untuk edit)
  isFormChanged() {
    return JSON.stringify(this.form) !== JSON.stringify(this.originalForm);
  },
}