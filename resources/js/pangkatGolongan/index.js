export const indexMethods = {
  fetchPangkatGolongans() {
    const self = this;

    // Inisialisasi DataTables
    this.dataTable = new DataTable('#pangkat-golongan-table', {
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
        data: 'pangkat',
        name: 'pangkat',
      },
      {
        data: 'golongan',
        name: 'golongan',
      },
      {
        data: 'ruang',
        name: 'ruang',
      },
      {
        data: 'id',
        orderable: false,
        searchable: false,
        // render action buttons
        render: function (data, type, row) {
          let buttons =
            '<div class="flex items-center justify-center gap-2">';

          // tampil tombol Edit (jika ada permission)
          if (self.config.canEdit) {
            buttons += `
                                                <button
                                                    type="button"
                                                    class="btn-edit peer p-2 cursor-pointer text-2xl text-yellow-500 focus:outline-0"
                                                    data-id="${row.id}"
                                                    data-tippy-content="<div style='text-align: center;'>Edit Pangkat Golongan:<br>${row.pangkat} - ${row.golongan}/${row.ruang}</div>"
                                                    >
                                                    <i class="ri-edit-2-line"></i>
                                                </button>
                                    `;
          }

          // tampil tombol Delete (jika ada permission)
          if (self.config.canDelete) {
            buttons += `
                                                <button
                                                    type="button"
                                                    class="btn-delete peer p-2 cursor-pointer text-2xl text-red-700 focus:outline-0"
                                                    data-id="${row.id}"
                                                    data-tippy-content="<div style='text-align: center;'>Delete Pangkat Golongan:<br>${row.pangkat} - ${row.golongan}/${row.ruang}</div>"
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
        });
      }
    });

    // event untuk tombol edit dan delete
    document.addEventListener('click', function (e) {
      // btn edit
      const editBtn = e.target.closest('.btn-edit');
      if (editBtn) {
        const id = editBtn.getAttribute('data-id');
        window.alpineComponent.editPangkatGolongan(id);
      }

      // btn delete
      const deleteBtn = e.target.closest('.btn-delete');
      if (deleteBtn) {
        const id = deleteBtn.getAttribute('data-id');
        window.alpineComponent.deletePangkatGolongan(id);
      }
    });

    // Simpan referensi 'this' (komponen Alpine) ke dalam variabel.
    window.alpineComponent = this;
  },
}