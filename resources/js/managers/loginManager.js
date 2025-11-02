function loginManager() {
  return {
    loading: false,
    showPassword: false,
    email: '',
    password: '',

    // email & password dibawah ini hanya untuk testing
    // email: 'superadmin@gmail.com',
    // password: 'Password1.',

    // cek apakah form valid
    isFormValid() {
      // regex email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      
      return (
        this.email &&
        this.email.match(emailRegex) &&
        this.password
      );
    },
  };
}

window.loginManager = loginManager;