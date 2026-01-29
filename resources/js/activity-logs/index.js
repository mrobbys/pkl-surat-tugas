import { formatTanggalWaktuIndonesia } from '../utils/dateFormatterIndonesia.js'

export const indexMethods = {
  fetchActivities() {
    this.dataTable = new DataTable("#activity-log-table", {
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
        searchable: false
      },
      {
        data: 'causer',
        name: 'causer.email',
        render: function (data, type, row) {
          if (data && data.email) {
            return data.email
          } else if (row.properties && row.properties.email) {
            return `<span>${row.properties.email}</span>`
          } else {
            return `<span class="text-gray-400 italic">Null</span>`
          }
        }
      },
      {
        data: 'description',
        name: 'description'
      },
      {
        data: 'created_at',
        name: 'created_at',
        render: function (data, type, row) {
          return formatTanggalWaktuIndonesia(data);
        }
      },
      {
        data: 'properties',
        name: 'properties.ip_address',
        render: function (data, type, row) {
          return data ? data.ip_address : `<span class="text-gray-400">-</span>`;
        }
      },
      {
        data: 'log_name',
        name: 'log_name'
      }
      ],
    });

    window.alpineComponent = this;
  },
}
