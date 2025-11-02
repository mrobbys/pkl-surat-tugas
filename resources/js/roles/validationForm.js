export const validationForm = {
  // cek apakah form valid
  isFormValid() {
    return (
      this.form.name &&
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

  // fungsi untuk membandingkan dua array
  arraysEqual(a, b) {
    if (a.length !== b.length) return false;
    const sortedA = [...a].sort();
    const sortedB = [...b].sort();
    return sortedA.every((val, idx) => val == sortedB[idx]);
  },
}