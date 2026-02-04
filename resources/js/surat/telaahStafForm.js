import { focusIfNotTouch } from '../utils/touchDeviceDetection.js'

export const telaahStafFormMethods = {
  init() {
    this.$nextTick(() => {
      this.initChoices();
      this.initCKEditor();
      this.initFlatpickr();

      // Cek apakah bukan touch device, focus input pertama jika bukan show mode
      if (this.mode !== 'show' && this.$refs.kepada_ythInput) {
        focusIfNotTouch(this.$refs.kepada_ythInput);
      }
    });
  },

  initFlatpickr() {
    // config flatpickr
    const config = {
      dateFormat: "Y-m-d",
      altInput: true,
      altFormat: "j F Y",
      allowInput: false,
    };

    const tanggalTelaahan = document.getElementById('tanggal_telaahan');
    if (tanggalTelaahan && this.mode !== 'show') {
      this.tanggalTelaahanPicker = flatpickr(tanggalTelaahan, {
        ...config,
        defaultDate: this.form.tanggal_telaahan || null,
        onChange: (selectedDates, dateStr) => {
          this.form.tanggal_telaahan = dateStr;
        }
      });
    }

    const tanggalMulai = document.getElementById('tanggal_mulai');
    if (tanggalMulai && this.mode !== 'show') {
      this.tanggalMulaiPicker = flatpickr(tanggalMulai, {
        ...config,
        defaultDate: this.form.tanggal_mulai || null,
        onChange: (selectedDates, dateStr) => {
          this.form.tanggal_mulai = dateStr;

          if (this.tanggalSelesaiPicker) {
            this.tanggalSelesaiPicker.set('minDate', dateStr);
          }
        }
      });
    }

    const tanggalSelesai = document.getElementById('tanggal_selesai');
    if (tanggalSelesai && this.mode !== 'show') {
      this.tanggalSelesaiPicker = flatpickr(tanggalSelesai, {
        ...config,
        defaultDate: this.form.tanggal_selesai || null,
        minDate: this.form.tanggal_mulai || null,
        onChange: (selectedDates, dateStr) => {
          this.form.tanggal_selesai = dateStr;

          if (this.tanggalMulaiPicker) {
            this.tanggalMulaiPicker.set('maxDate', dateStr);
          }
        }
      });
    }
  },

  initCKEditor() {
    if (typeof CKEDITOR === 'undefined') return;

    const editorIds = ['isi_telaahan'];

    editorIds.forEach(editorId => {
      // Cek apakah editor sudah ada
      if (CKEDITOR.instances[editorId]) {
        CKEDITOR.instances[editorId].destroy(true);
      }
    });

    CKEDITOR.replace('isi_telaahan', {
      // Toolbar sederhana: Bold, Italic, List, Link, Source
      toolbar: [
        ['Bold', 'Italic'], // Format teks dasar
        ['NumberedList', 'BulletedList'], // List bernomor dan bullet
        ['Link', 'Unlink'], // Link dan unlink
        ['Source'], // View source HTML
      ],

      // Plugin yang dihapus untuk tampilan lebih clean
      removePlugins: 'elementspath,about,exportpdf',

      // Pengaturan tampilan editor
      height: 250, // Tinggi editor 250px
      resize_enabled: false, // Disable resize handle
      filebrowserBrowseUrl: false, // Disable file browser
      filebrowserUploadUrl: false, // Disable file upload
      disableNativeSpellChecker: false, // Enable spell checker browser
      format_tags: 'p;h1;h2;h3', // Format yang tersedia
    });

    // set CKEditor content jika edit/show
    if (this.mode !== 'create' && this.form.isi_telaahan) {
      CKEDITOR.instances.isi_telaahan.setData(this.form.isi_telaahan);
    }

    // disable CKEditor jika mode show
    if (this.mode === 'show') {
      CKEDITOR.instances.isi_telaahan.config.readOnly = true;
    }
  },

  initChoices() {
    if (this.pegawaisChoices) {
      return;
    }

    const el = document.getElementById('pegawais');
    if (el) {
      this.pegawaisChoices = new Choices(el, {
        removeItemButton: this.mode !== 'show',
        searchResultLimit: 10,
        placeholderValue: this.mode === 'show' ? '' : 'Pilih Pegawai',
        noResultsText: 'Tidak ada hasil',
        noChoicesText: 'Tidak ada pilihan',
        itemSelectText: 'Klik untuk memilih',
        searchPlaceholderValue: this.mode === 'show' ? '' :
          'Cari pegawai...',
        searchEnabled: this.mode !== 'show',
      });

      const pegawaisOptions = this.pegawaisData.map((p) => ({
        value: p.id.toString(),
        label: `${p.nip} - ${p.nama_lengkap}`,
      }));

      this.pegawaisChoices.setChoices(
        pegawaisOptions,
        'value',
        'label',
        false,
      );

      // set selected values jika edit/show
      if (this.mode !== 'create' && this.form.pegawais.length > 0) {
        this.pegawaisChoices.setChoiceByValue(
          this.form.pegawais.map(id => id.toString())
        );
      }

      // disable jika mode show
      if (this.mode === 'show') {
        this.pegawaisChoices.disable();
      }

      el.addEventListener('change', () => {
        this.form.pegawais = this.pegawaisChoices.getValue(true);
      });
    }
  },

  async saveTelaahStaf() {
    // prevent submit di mode show
    if (this.mode === 'show') return;

    this.loading = true;
    this.errors = {};

    // Get CKEditor data
    if (CKEDITOR.instances.isi_telaahan) {
      this.form.isi_telaahan = CKEDITOR.instances.isi_telaahan.getData();
    }

    const url = this.mode === 'create'
      ? this.config.storeUrl
      : this.config.updateUrl.replace('__ID__', this.editingId);
      
    try {
      const payload = {...this.form };
      if(this.editingId){
        payload._method = 'PUT';
      }
      
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': this.csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      if (response.ok) {
        await Swal.fire({
          icon: data.icon,
          title: data.title,
          text: data.text,
          timer: 5000,
          timerProgressBar: true,
          showConfirmButton: true,
          confirmButtonText: 'OK',
          allowOutsideClick: false,
          allowEscapeKey: false,
        }).then(() => {
          // redirect user setelah klik OK atau timer habis
          window.location.href = this.config.indexUrl;
        });
      } else {
        if (response.status === 422) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal submit form, cek kembali data yang Anda masukkan.',
            timer: 5000,
          });
          this.errors = data.errors || {};
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.text ||
              data.message ||
              'Terjadi kesalahan saat submit telaah staf.',
            timer: 5000,
          });
        }
      }
    } catch (error) {
      console.error('Error saving data:', error);
      Swal.fire('Error', 'Terjadi kesalahan saat submit telaah staf.',
        'error');
    } finally {
      this.loading = false;
    }
  },
}