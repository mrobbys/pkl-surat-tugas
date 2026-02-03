function loginManager() {
  return {
    loading: false,
    showPassword: false,

    email: '',
    password: '',

    // email & password dibawah ini hanya untuk testing
    // email: 'superadin@gmail.com',
    // password: 'Pasword',

    // handle keyboard = / autofocus ke email
    handleAutoFocusEmail(e) {
      if (e.ctrlKey || e.metaKey || e.altKey) return;
      if (e.key !== '/') return;

      const tag = e.target?.tagName?.toLowerCase();
      const isEditable = e.target?.isContentEditable;
      if (tag === 'input' || isEditable) return;

      e.preventDefault();
      this.$nextTick(() => {
        const input = this.$refs?.emailInput;
        if (input) {
          input.focus();
        }
      });
    },

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