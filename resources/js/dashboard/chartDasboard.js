// config default
const defaultChartConfig = {
  responsive: true,
  maintainAspectRatio: false,
  animation: {
    animateRotate: true,
    animateScale: true,
    duration: 800,
    easing: 'easeOutQuart'
  }
};

const defaultLegendConfig = {
  position: 'bottom',
  labels: {
    padding: 20,
    usePointStyle: true,
    boxWidth: 10,
    pointStyle: 'circle',
    font: {
      size: 12,
      family: "'Inter', sans-serif"
    }
  }
};

const defaultTooltipConfig = {
  backgroundColor: 'rgba(0, 0, 0, 0.8)',
  bodyFont: {
    size: 14,
    weight: 'bold'
  },
  padding: 12,
  cornerRadius: 8,
  callbacks: {
    title: () => null
  }
};

/**
 * Generate legend labels dengan nilai
 */
function generateLegendLabels(chart, prefix = '') {
  return chart.data.labels.map((label, i) => {
    const dataset = chart.data.datasets[0];
    const value = dataset.data[i];
    const displayLabel = prefix ? `${prefix} ${label}` : label;

    return {
      text: `${displayLabel}: ${value}`,
      fillStyle: dataset.backgroundColor[i],
      strokeStyle: dataset.backgroundColor[i],
      hidden: false,
      index: i,
      pointStyle: 'circle'
    };
  });
}

/**
 * Generate tooltip label dengan persentase
 */
function generateTooltipLabel(context) {
  const total = context.dataset.data.reduce((a, b) => a + b, 0);
  const value = context.parsed;
  const percentage = total > 0 ? ((value / total) * 100).toFixed(0) : 0;
  return `${context.label}: ${value} (${percentage}%)`;
}

// chart statistik status pengajuan surat
export const statusStatistics = {
  chart: null,

  init: async function () {
    try {
      const res = await fetch('/dashboard/status-statistics');
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      const data = await res.json();
      this.renderChart(data);
    } catch (error) {
      console.error('Error fetching status statistics data:', error);
    }
  },

  renderChart: function (data) {
    const ctx = document.getElementById("statusChart");

    if (!ctx) {
      console.error('Canvas element with id "statusChart" not found.');
      return;
    }

    if (this.chart) this.chart.destroy();

    if (handleEmptyChart(ctx, data.data, `Data statistik tahun ${data.year} masih kosong`)) return;

    const colors = ['#818CF8', '#FB923C', '#F87171', '#22D3EE']

    this.chart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: data.labels,
        datasets: [{
          data: data.data,
          backgroundColor: colors,
          borderWidth: 0,
          hoverOffset: 4,
        }],
      },
      options: {
        ...defaultChartConfig,
        cutout: '60%',
        plugins: {
          legend: {
            ...defaultLegendConfig,
            labels: {
              ...defaultLegendConfig.labels,
              generateLabels: (chart) => generateLegendLabels(chart)
            }
          },
          tooltip: {
            ...defaultTooltipConfig,
            callbacks: {
              ...defaultTooltipConfig.callbacks,
              label: generateTooltipLabel
            }
          }
        }
      },
    });

  },

  destroy: function () {
    if (this.chart) {
      this.chart.destroy();
      this.chart = null;
    }
  }
}

// chart proprosi penugasan pegawai berdasarkan golongan
export const employeeAssignmentByRank = {
  chart: null,

  init: async function () {
    try {
      const res = await fetch('/dashboard/golongan-statistics');
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      const data = await res.json();
      this.renderChart(data);
    } catch (error) {
      console.error('Error fetching golongan statistics data:', error);
    }
  },

  renderChart: function (data) {
    const ctx = document.getElementById("golonganChart");

    if (!ctx) {
      console.error('Canvas element with id "golonganChart" not found.');
      return;
    }

    if (this.chart) this.chart.destroy();

    if (handleEmptyChart(ctx, data.data, `Data proporsi tahun ${data.year} masih kosong`)) return;

    const colors = ['#A78BFA', '#F472B6', '#60A5FA', '#FBBF24'];

    // golongan label
    const formattedLabels = data.labels.map(label => `Golongan ${label}`);

    this.chart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: formattedLabels,
        datasets: [{
          data: data.data,
          backgroundColor: colors,
          borderWidth: 0,
          hoverOffset: 4,
        }],
      },
      options: {
        ...defaultChartConfig,
        plugins: {
          legend: {
            ...defaultLegendConfig,
            labels: {
              ...defaultLegendConfig.labels,
              generateLabels: (chart) => generateLegendLabels(chart)
            }
          },
          tooltip: {
            ...defaultTooltipConfig,
            callbacks: {
              ...defaultTooltipConfig.callbacks,
              label: generateTooltipLabel
            }
          }
        }
      }
    });
  },

  destroy: function () {
    if (this.chart) {
      this.chart.destroy();
      this.chart = null;
    }
  }
}

/**
 * Cek apakah data chart kosong dan tampilkan pesan jika kosong
 * @param {HTMLElement} ctx - Canvas element
 * @param {Array} dataArray - Array data chart
 * @param {string} message - Pesan yang ditampilkan jika kosong
 * @returns {boolean} - true jika kosong, false jika ada data
 */
function handleEmptyChart(ctx, dataArray, message = 'Data masih kosong') {
  const total = dataArray.reduce((a, b) => a + b, 0);

  // Hapus pesan sebelumnya
  const existingMessage = ctx.parentElement.querySelector('.empty-chart-message');
  if (existingMessage) existingMessage.remove();

  if (total === 0) {
    ctx.style.display = 'none';

    const emptyMessage = document.createElement('div');
    emptyMessage.className = 'empty-chart-message flex h-full items-center justify-center';
    emptyMessage.innerHTML = `
      <div class="text-center">
        <i class="ri-pie-chart-line text-5xl text-gray-300"></i>
        <p class="mt-2 text-gray-500">${message}</p>
      </div>
    `;
    ctx.parentElement.appendChild(emptyMessage);

    return true;
  }

  ctx.style.display = 'block';
  return false;
}