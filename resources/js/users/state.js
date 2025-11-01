export const state = {
  dataTable: false,
  loading: false,
  showModal: false,
  isShowMode: false,
  modalTitle: '',
  textSubmit: '',
  pangkatGolongan: null,
  roles: null,
  editingId: null,

  // Choices.js instances
  rolesChoice: null,
  pangkatGolonganChoice: null,

  originalForm: {
    nip: '',
    nama_lengkap: '',
    email: '',
    password: '',
    roles: [],
    pangkat_golongan_id: '',
    jabatan: '',
  },
  form: {
    nip: '',
    nama_lengkap: '',
    email: '',
    password: '',
    roles: [],
    pangkat_golongan_id: '',
    jabatan: '',
  },
  showPassword: false,
  errors: {},
}