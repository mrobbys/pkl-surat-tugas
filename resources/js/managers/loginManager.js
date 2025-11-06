function loginManager() {
  return {
    loading: false,
    showPassword: false,

    email: '',
    password: '',

    // email & password dibawah ini hanya untuk testing
    // email: 'superadin@gmail.com',
    // password: 'Pasword',

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