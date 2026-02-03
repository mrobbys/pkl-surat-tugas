import { initTippy } from '../utils/tippyInit.js'

export const indexMethods = {
  fetchRoles() {
    const self = this;

    if (this.dataTable) {
      this.dataTable.destroy();
    }

    // inisialisasi datatable
    this.dataTable = new DataTable('#role-table', {
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
        data: 'name',
        name: 'name',
        render: (data) => this.sanitizeString(data),
      },
      {
        data: 'id',
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          let buttons =
            '<div class="flex items-center justify-center">';

          // tombol detail (selalu ada)
          buttons += `
                                        <button
                                            type="button"
                                            class="btn-detail p-2 cursor-pointer text-2xl text-blue-500 focus:outline-0"
                                            data-id="${row.id}"
                                            data-tippy-content="Detail Role: ${row.name}"
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
                                                data-tippy-content="Edit Role: ${row.name}"
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
                                                data-tippy-content="Delete Role: ${row.name}"
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
        initTippy();
      }
    });

    // event untuk action buttons
    document.addEventListener('click', function (e) {
      // btn detail
      const detailBtn = e.target.closest('.btn-detail');
      if (detailBtn) {
        const id = detailBtn.getAttribute('data-id');
        window.alpineComponent.detailRole(id);
      }

      // btn edit
      const editBtn = e.target.closest('.btn-edit');
      if (editBtn) {
        const id = editBtn.getAttribute('data-id');
        window.alpineComponent.editRole(id);
      }

      // btn delete
      const deleteBtn = e.target.closest('.btn-delete');
      if (deleteBtn) {
        const id = deleteBtn.getAttribute('data-id');
        window.alpineComponent.deleteRole(id);
      }
    });

    // Simpan referensi 'this' (komponen Alpine) ke dalam variabel.
    window.alpineComponent = this;
  },
}