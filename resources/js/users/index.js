export const indexMethods = {
  fetchUsers() {
    const self = this;
    
    // inisialisasi datatables
    this.dataTable = new DataTable('#user-table', {
      layout: {
        bottomEnd: {
          paging: {
            firstLast: false,
          },
        },
      },
      scrollX: true,
      ajax: this.config.indexUrl,
      columnDefs: [{
        className: 'whitespace-nowrap',
        targets: '_all'
      }],
      columns: [{
        data: null,
        render: (data, type, row, meta) => meta.row + 1,
        searchable: false,
      },
      {
        data: 'nip',
        name: 'nip',
        render: function (data, type, row) {
          // untuk nip hanya tampil 4 digit terakhir
          if (!data) return '';
          const last4 = data.slice(-4);
          const masked = '*'.repeat(data.length - 4) + last4;
          return masked;
        },
      },
      {
        data: 'nama_lengkap',
        name: 'nama_lengkap',
      },
      {
        data: 'email',
        name: 'email',
        render: function (data, type, row) {
          if (!data) return '';
          const [user, domain] = data.split('@');
          if (user.length <= 2) {
            return user[0] + '***@' + domain;
          }
          return user.slice(0, 2) + '***@' + domain;
        },
      },
      {
        data: 'roles',
        name: 'roles',
        render: function (data, type, row) {
          // tampilkan nama role, pisahkan dengan koma
          return data.map((role) => role.name).join(', ');
        },
      },
      {
        data: 'id',
        orderable: false,
        searchable: false,
        // render action buttons
        render: function (data, type, row) {
          let buttons =
            '<div class="flex items-center justify-center gap-2">';

          // tombol detail
          buttons += `
                                        <button
                                            type="button"
                                            class="btn-detail p-2 cursor-pointer text-2xl text-blue-500 focus:outline-0"
                                            data-id="${row.id}"
                                            data-tippy-content="<div style='text-align: center;'>Detail User:<br>${row.nama_lengkap}</div>"
                                        >
                                            <i class="ri-zoom-in-fill"></i>
                                        </button>
                                `;

          // tampil tombol edit (jika ada permission)
          if (self.config.canEdit) {
            buttons += `
                                        <button
                                            type="button"
                                            class="btn-edit peer p-2 cursor-pointer text-2xl text-yellow-500 focus:outline-0"
                                            data-id="${row.id}"
                                            data-tippy-content="<div style='text-align: center;'>Edit User:<br>${row.nama_lengkap}</div>"
                                        >
                                            <i class="ri-edit-2-line"></i>
                                        </button>
                                    `;
          }

          // tampil tombol delete (jika ada permission)
          if (self.config.canDelete) {
            buttons += `
                                        <button
                                            type="button"
                                            class="btn-delete peer p-2 cursor-pointer text-2xl text-red-700 focus:outline-0"
                                            data-id="${row.id}"
                                            data-tippy-content="<div style='text-align: center;'>Delete User:<br>${row.nama_lengkap}</div>"
                                        >
                                            <i class="ri-delete-bin-6-fill"></i>
                                        </button>
                                    `;
          }

          buttons += '</div>';
          return buttons;
        },
      },
      ],
      // Callback setelah DataTable selesai draw
      drawCallback: function () {
        // Initialize Tippy.js untuk semua button
        tippy('[data-tippy-content]', {
          placement: 'left-start',
          arrow: true,
          theme: 'light',
          allowHTML: true,
          trigger: 'mouseenter',
          hideOnClick: true,
          touch: false,
        });
      }
    });

    // event untuk action buttons
    document.addEventListener('click', function (e) {
      // btn detail
      const detailBtn = e.target.closest('.btn-detail');
      if (detailBtn) {
        const id = detailBtn.getAttribute('data-id');
        window.alpineComponent.detailUser(id);
      }

      // btn edit
      const editBtn = e.target.closest('.btn-edit');
      if (editBtn) {
        const id = editBtn.getAttribute('data-id');
        window.alpineComponent.editUser(id);
      }

      // btn delete
      const deleteBtn = e.target.closest('.btn-delete');
      if (deleteBtn) {
        const id = deleteBtn.getAttribute('data-id');
        window.alpineComponent.deleteUser(id);
      }
    });

    // Simpan referensi 'this' (komponen Alpine) ke dalam variabel.
    window.alpineComponent = this;
  },
}