export const validationForm = {
  validateForm(field) {
    let isValid = true;

    // validasi nama role
    if (field === 'name') {
      const nameRegex = /^[a-z0-9]+(-[a-z0-9]+)*$/;
      const name = this.form.name;

      if (name.length > 0) {
        if (name.length < 3) {
          this.errors.name = 'Nama role minimal 3 karakter.';
          isValid = false;
        } else if (!nameRegex.test(name)) {
          this.errors.name =
            'Nama role hanya boleh mengandung huruf kecil, angka, dan tanda hubung (-), serta tidak boleh diawali atau diakhiri dengan tanda hubung.';
          isValid = false;
        } else {
          delete this.errors.name;
        }
      } else {
        delete this.errors.name;
      }
    }

    return isValid;
  },

  // cek apakah form valid
  isFormValid() {
    return (
      this.form.name.length >= 3 &&
      this.form.name.match(/^[a-z0-9]+(-[a-z0-9]+)*$/) &&
      this.form.permissions.length > 0
    );
  },

  // cek apakah form berubah (untuk edit)
  isFormChanged() {
    return (
      this.form.name !== this.originalForm.name ||
      !this.arraysEqual(this.form.permissions, this.originalForm.permissions)
    );
  },

  arraysEqual(a, b) {
    if (a.length !== b.length) return false;
    const sortedA = [...a].sort();
    const sortedB = [...b].sort();
    return sortedA.every((val, idx) => val == sortedB[idx]);
  },
}