function loginManager() {
  return {
    loading: false,
    email: '{{ old("email", "") }}',
    password: '',

    showPassword: false,
    errors: {},

    // validasi inputan form
    validateForm(field) {
      // validasi email
      if (field === 'email') {
        // regex untuk validasi format email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const email = this.email;

        // validasi format email jika ada isinya
        if (email.length > 0 && !emailRegex.test(email)) {
          this.errors.email = 'Format email tidak valid.';
        } else {
          delete this.errors.email;
        }
      }

      // validasi password
      if (field === 'password') {
        const password = this.password;
        // menyimpan pesan error password ke dalam array
        let passwordErrors = [];

        // validasi password ketika ada isinya
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
    },

    // cek apakah form valid
    isFormValid() {
      // regex email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      // regex password huruf besar & kecil
      const pwdRegex1 = /(?=.*[a-z])(?=.*[A-Z])/;
      // regex password angka
      const pwdRegex2 = /(?=.*\d)/;
      // regex password simbol
      const pwdRegex3 = /(?=.*[^A-Za-z0-9])/;

      // tidak ada error dan semua field terisi dengan benar
      return (
        this.email &&
        this.email.match(emailRegex) &&
        this.password &&
        this.password.length >= 8 &&
        this.password.match(pwdRegex1) &&
        this.password.match(pwdRegex2) &&
        this.password.match(pwdRegex3)
      );
    },
  };
}

window.loginManager = loginManager;