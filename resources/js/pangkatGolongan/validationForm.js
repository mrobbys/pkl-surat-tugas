export const validationForm = {
  // cek apakah form valid
  isFormValid() {
    return (
      this.form.pangkat.length && this.form.golongan && this.form.ruang
    );
  },

  // cek apakah form berubah (untuk edit)
  isFormChanged() {
    return (
      JSON.stringify(this.form) !== JSON.stringify(this.originalForm)
    );
  },
}