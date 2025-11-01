export const validationForm = {
  validateForm(field) {
    let isValid = true;

    // validasi NIP
    if (field === 'nip') {
      // regex hanya angka
      const nipRegex = /^\d*$/;
      const nip = this.form.nip;

      if (nip.length > 0) {
        if (!nipRegex.test(nip)) {
          this.errors.nip = 'NIP hanya boleh berisi angka';
          isValid = false;
        } else if (nip.length !== 18) {
          this.errors.nip = 'NIP harus 18 digit';
          isValid = false;
        } else {
          delete this.errors.nip;
        }
      } else {
        delete this.errors.nip;
      }
    }

    // validasi nama lengkap
    if (field === 'nama_lengkap') {
      const namaLengkap = this.form.nama_lengkap;
      const namaRegex = /^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ\s.,'"-]*[A-Za-zÀ-ÿ.,]$/u;

      if (namaLengkap.length > 0) {
        if (!namaRegex.test(namaLengkap)) {
          this.errors.nama_lengkap =
            'Nama lengkap hanya boleh berisi huruf, spasi, titik, dan koma';
          isValid = false;
        } else if (namaLengkap.length < 3) {
          this.errors.nama_lengkap = 'Nama lengkap minimal 3 karakter';
          isValid = false;
        } else {
          // hapus error jika valid
          delete this.errors.nama_lengkap;
        }
      } else {
        // hapus error jika valid
        delete this.errors.nama_lengkap;
      }
    }

    // validasi email
    if (field === 'email') {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const email = this.form.email;

      if (email.length > 0) {
        if (!emailRegex.test(email)) {
          this.errors.email = 'Email tidak valid';
          isValid = false;
        } else {
          // hapus error jika valid
          delete this.errors.email;
        }
      } else {
        // hapus error jika valid
        delete this.errors.email;
      }
    }

    // validasi password
    if (field === 'password') {
      const password = this.form.password;
      let passwordErrors = [];

      if (password.length > 0) {
        if (password.length < 8) {
          passwordErrors.push('Password minimal 8 karakter.');
        }
        if (!/[A-Z]/.test(password)) {
          passwordErrors.push('Password harus mengandung huruf besar.');
        }
        if (!/[a-z]/.test(password)) {
          passwordErrors.push('Password harus mengandung huruf kecil.');
        }
        if (!/[0-9]/.test(password)) {
          passwordErrors.push('Password harus mengandung angka.');
        }
        if (!/[^A-Za-z0-9]/.test(password)) {
          passwordErrors.push('Password harus mengandung simbol.');
        }
        if (passwordErrors.length > 0) {
          this.errors.password = passwordErrors;
        } else {
          delete this.errors.password;
        }
      } else {
        delete this.errors.password;
      }
    }

    // validasi jabatan
    if (field === 'jabatan') {
      const jabatan = this.form.jabatan;
      const jabatanRegex = /^[a-zA-Z\s]+$/;

      if (jabatan.length > 0) {
        if (!jabatanRegex.test(jabatan)) {
          this.errors.jabatan = 'Jabatan hanya boleh berisi huruf dan spasi';
          isValid = false;
        } else if (jabatan.length < 3) {
          this.errors.jabatan = 'Jabatan minimal 3 karakter';
          isValid = false;
        } else {
          // hapus error jika valid
          delete this.errors.jabatan;
        }
      } else {
        // hapus error jika valid
        delete this.errors.jabatan;
      }
    }

    return isValid;
  },

  // cek apakah form valid
  isFormValid() {
    // editing id
    const isEditing = this.editingId;

    // regex password huruf besar & kecil
    const pwdRegex1 = /(?=.*[a-z])(?=.*[A-Z])/;
    // regex password angka
    const pwdRegex2 = /(?=.*\d)/;
    // regex password simbol
    const pwdRegex3 = /(?=.*[^A-Za-z0-9])/;

    let passwordValid = false;
    if (isEditing) {
      // Edit: password boleh kosong, jika diisi harus valid
      passwordValid =
        this.form.password.length === 0 ||
        (this.form.password.length >= 8 &&
          pwdRegex1.test(this.form.password) &&
          pwdRegex2.test(this.form.password) &&
          pwdRegex3.test(this.form.password));
    } else {
      // Create: password wajib diisi dan valid
      passwordValid =
        this.form.password.length >= 8 &&
        pwdRegex1.test(this.form.password) &&
        pwdRegex2.test(this.form.password) &&
        pwdRegex3.test(this.form.password);
    }

    // regex nip hanya angka
    const nipRegex = /^\d*$/;
    // regex nama lengkap hanya huruf, spasi, titik, koma
    const namaRegex = /^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ\s.,'"-]*[A-Za-zÀ-ÿ.,]$/u;
    // regex email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    // regex jabatan hanya huruf dan spasi
    const jabatanRegex = /^[a-zA-Z\s]+$/;

    // semua field terisi
    return (
      this.form.nip &&
      this.form.nip.length === 18 &&
      this.form.nip.match(nipRegex) &&
      this.form.nama_lengkap &&
      this.form.nama_lengkap.length >= 3 &&
      this.form.nama_lengkap.match(namaRegex) &&
      this.form.email &&
      this.form.email.match(emailRegex) &&
      passwordValid &&
      this.form.roles.length > 0 &&
      this.form.pangkat_golongan_id &&
      this.form.jabatan &&
      this.form.jabatan.length >= 3 &&
      this.form.jabatan.match(jabatanRegex)
    );
  },

  // cek apakah form berubah (untuk edit)
  isFormChanged() {
    return JSON.stringify(this.form) !== JSON.stringify(this.originalForm);
  },
}