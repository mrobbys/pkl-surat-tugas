export const indexMethods = {
  fetchActivities() {
    const self = this;

    if(this.dataTable){
      this.dataTable.destroy();
    }

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
            return self.sanitizeString(data.email)
          } else if (row.properties && row.properties.email) {
            return `<span>${self.sanitizeString(row.properties.email)}</span>`
          } else {
            return `<span class="text-gray-400 italic">System</span>`
          }
        }
      },
      {
        data: 'description',
        name: 'description',
        render: (data) => self.sanitizeString(data)
      },
      {
        data: 'created_at',
        name: 'created_at',
        render: (data) => self.formatTanggalWaktuIndonesia(data),
      },
      {
        data: 'properties',
        name: 'properties.ip_address',
        render: function (data, type, row) {
          return data ? self.sanitizeString(data.ip_address) : `<span class="text-gray-400">-</span>`;
        }
      },
      {
        data: 'log_name',
        name: 'log_name',
        render: (data) => self.sanitizeString(data)
      }
      ],
    });

    window.alpineComponent = this;
  },

  
}
