export const validationForm = {
  validateForm(field) {
    let isValid = true;

    // validasi inputan pangkat
    if (field === 'pangkat') {
      const pangkat = this.form.pangkat;

      if (pangkat.length > 0) {
        if (pangkat.length < 3) {
          this.errors.pangkat = 'Pangkat minimal 3 karakter.';
          isValid = false;
        } else if (pangkat.length > 100) {
          this.errors.pangkat = 'Pangkat maksimal 100 karakter.';
          isValid = false;
        } else {
          delete this.errors.pangkat;
        }
      } else {
        delete this.errors.pangkat;
      }
    }

    if (field === 'golongan') {
      const regexRomawi =
        /^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/i;
      const golongan = this.form.golongan;

      if (golongan.length > 0) {
        if (!regexRomawi.test(golongan)) {
          this.errors.golongan =
            'Golongan harus berupa angka romawi (I, II, III, IV, dst).';
          isValid = false;
        } else {
          delete this.errors.golongan;
        }
      } else {
        delete this.errors.golongan;
      }
    }

    if (field === 'ruang') {
      const regexHuruf = /^[a-z]+$/;
      const ruang = this.form.ruang;

      if (ruang.length > 0) {
        if (!regexHuruf.test(ruang)) {
          this.errors.ruang =
            'Ruang harus berupa huruf kecil (a, b, c, d, dst).';
          isValid = false;
        } else {
          delete this.errors.ruang;
        }
      } else {
        delete this.errors.ruang;
      }
    }

    return isValid;
  },

  // cek apakah form valid
  isFormValid() {
    return (
      this.form.pangkat.length >= 3 &&
      this.form.golongan.length >= 1 &&
      this.form.golongan.match(
        /^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/i,
      ) &&
      this.form.ruang.length === 1 &&
      this.form.ruang.match(/^[a-z]+$/)
    );
  },

  // cek apakah form berubah (untk edit)
  isFormChanged() {
    return (
      this.form.pangkat !== this.originalForm.pangkat ||
      this.form.golongan !== this.originalForm.golongan ||
      this.form.ruang !== this.originalForm.ruang
    );
  },
}