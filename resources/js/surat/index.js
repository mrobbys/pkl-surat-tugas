import { getStatusConfig } from '../utils/statusTelaahStafIndex.js'
import { formatTanggalIndonesia } from '../utils/dateFormatterIndonesia.js'
import { initTippy } from '../utils/tippyInit.js'

export const indexMethods = {
  fetchTelaahStaf() {
    const self = this;

    // inisialisasi datatables
    this.dataTable = new DataTable('#surat-table', {
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
        render: (data, type, row, meta) => meta.row + 1
      },
      {
        data: 'nomor_telaahan',
        name: 'nomor_telaahan'
      },
      {
        data: 'tanggal_telaahan',
        name: 'tanggal_telaahan',
        render: function (data, type, row) {
          return formatTanggalIndonesia(data);
        },
      },
      {
        data: 'pembuat.nama_lengkap',
        name: 'pembuat.nama_lengkap'
      },
      {
        data: 'status',
        name: 'status',
        render: (data) => {
          const config = getStatusConfig(data);
          return `
              <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold whitespace-nowrap ${config.class}">
                <i class="${config.icon}"></i>
                <span>${config.label}</span>
              </span>
            `;
        }
      },
      {
        data: 'id',
        orderable: false,
        searchable: false,
        // render action buttons
        render: (data, type, row) => {
          let actions =
            `<div class="flex items-center justify-center gap-2">`;

          //  btn detail
          actions += `
                                                <a
                                                    href="/surat/telaah-staf/${data}"
                                                    class="p-2 cursor-pointer text-2xl text-blue-500 focus:outline-0"
                                                    data-id="${row.id}"
                                                    data-tippy-content="<div style='text-align: center;'>Detail Telaah Staf:<br>${row.nomor_telaahan}</div>"
                                                    >
                                                        <i class="ri-zoom-in-fill"></i>
                                                </a>
                                                `;

          // btn edit
          if (
            self.config.canEdit &&
            row.status !== 'disetujui_kadis' &&
            row.status !== 'ditolak_kabid' &&
            row.status !== 'ditolak_kadis'
          ) {
            actions += `
                                                <a
                                                    href="/surat/telaah-staf/${data}/edit"
                                                    class="p-2 cursor-pointer text-2xl text-yellow-500 focus:outline-0"
                                                    data-id="${row.id}"
                                                    data-tippy-content="<div style='text-align: center;'>Edit Telaah Staf:<br>${row.nomor_telaahan}</div>"
                                                    >
                                                        <i class="ri-edit-2-line"></i>
                                                </a>
                                    `;
          }

          // btn delete
          if (
            self.config.canDelete &&
            row.status !== 'disetujui_kadis' &&
            row.status !== 'ditolak_kabid' &&
            row.status !== 'ditolak_kadis'
          ) {
            actions += `
                                                <button
                                                    type="button"
                                                    class="btn-delete peer p-2 cursor-pointer text-2xl text-red-700 focus:outline-0"
                                                    data-id="${row.id}"
                                                    data-tippy-content="<div style='text-align: center;'>Delete Telaah Staf:<br>${row.nomor_telaahan}</div>"
                                                >
                                                    <i class="ri-delete-bin-6-fill"></i>
                                                </button>
                                    `;
          }

          // btn pdf telaah staf
          if (self.config.canPDFTelaahStaf) {
            actions += `
                                                <a
                                                    href="/surat/${data}/cetak-telaah-staf"
                                                    target="_blank"
                                                    class="peer p-2 cursor-pointer text-2xl text-rose-600 focus:outline-0"
                                                    data-id="${row.id}"
                                                    data-tippy-content="<div style='text-align: center;'>PDF Telaah Staf:<br>${row.nomor_telaahan}</div>"
                                                    >
                                                        <i class="ri-file-text-line"></i>
                                                </a>
                                    `;
          }

          // btn approve satu telaah staf
          if (
            self.config.canApproveTSLevel1 &&
            (row.status === 'diajukan' || row.status ===
              'revisi_kabid')
          ) {
            actions += `
                                                <button
                                                    type="button"
                                                    class="btn-approve-satu peer p-2 cursor-pointer text-2xl text-emerald-600 focus:outline-0"
                                                    data-id="${row.id}"
                                                    data-tippy-content="<div style='text-align: center;'>Tanggapi Telaah Staf:<br>${row.nomor_telaahan}</div>"
                                                >
                                                    <i class="ri-file-edit-line"></i>
                                                </button>
                                    `;
          }

          // btn approve dua telaah staf
          if (
            self.config.canApproveTSLevel2 &&
            (row.status === 'disetujui_kabid' || row
              .status === 'revisi_kadis')
          ) {
            actions += `
                                                <button
                                                    type="button"
                                                    class="btn-approve-dua peer p-2 cursor-pointer text-2xl text-indigo-600 focus:outline-0"
                                                    data-id="${row.id}"
                                                    data-tippy-content="<div style='text-align: center;'>Tanggapi Telaah Staf:<br>${row.nomor_telaahan}</div>"
                                                >
                                                    <i class="ri-file-edit-line"></i>
                                                </button>
                                    `;
          }

          // btn pdf nota dinas
          if (
            self.config.canPDFNotaDinas &&
            row.status === 'disetujui_kadis'
          ) {
            actions += `
                                                <a
                                                    href="/surat/${data}/cetak-nota-dinas"
                                                    target="_blank"
                                                    class="peer p-2 cursor-pointer text-2xl text-amber-600 focus:outline-0"
                                                    data-id="${row.id}"
                                                    data-tippy-content="<div style='text-align: center;'>PDF Nota Dinas:<br>${row.nomor_nota_dinas}</div>"
                                                    >
                                                        <i class="ri-file-pdf-line"></i>
                                                </a>
                                        `;
          }

          // btn pdf surat tugas
          if (
            self.config.canPDFSuratTugas &&
            row.status === 'disetujui_kadis'
          ) {
            actions += `
                                                <a
                                                    href="/surat/${data}/cetak-surat-tugas"
                                                    target="_blank"
                                                    class="peer p-2 cursor-pointer text-2xl text-orange-600 focus:outline-0"
                                                    data-id="${row.id}"
                                                    data-tippy-content="<div style='text-align: center;'>PDF Surat Tugas:<br>${row.nomor_surat_tugas}</div>"
                                                    >
                                                        <i class="ri-file-pdf-2-line"></i>
                                                </a>
                                    `;
          }

          actions += `</div>`;
          return actions;
        },
      },
      ],
      // Callback setelah DataTable selesai draw
      drawCallback: function () {
        initTippy()
      }
    });

    // event untuk action buttons
    document.addEventListener('click', function (e) {
      // btn delete
      const deleteBtn = e.target.closest('.btn-delete');
      if (deleteBtn) {
        const id = deleteBtn.getAttribute('data-id');
        window.alpineComponent.deleteTelaahStaf(id);
      }

      // btn approve satu
      const approveSatuBtn = e.target.closest('.btn-approve-satu');
      if (approveSatuBtn) {
        const id = approveSatuBtn.getAttribute('data-id');
        window.alpineComponent.approveTelaahStafSatu(id);
      }

      // btn approve dua
      const approveDuaBtn = e.target.closest('.btn-approve-dua');
      if (approveDuaBtn) {
        const id = approveDuaBtn.getAttribute('data-id');
        window.alpineComponent.approveTelaahStafDua(id);
      }
    });

    // Simpan referensi 'this' (komponen Alpine) ke dalam variabel.
    window.alpineComponent = this;
  },
}