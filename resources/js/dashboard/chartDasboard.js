import { sanitizeString } from "../utils/sanitizeString.js";

// konstanta untuk warna chart
const CHART_COLORS = {
  primary: '#4F46E5',
  status: ['#818CF8', '#FB923C', '#F87171', '#22D3EE'],
  golongan: ['#A78BFA', '#F472B6', '#60A5FA', '#FBBF24'],
}

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

// tooltip config default
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

// show loading
function showLoading(canvasId) {
  const loadingEl = document.getElementById(`${canvasId}-loading`);
  const canvasEl = document.getElementById(canvasId);

  if (loadingEl) loadingEl.classList.remove('hidden');
  if (canvasEl) canvasEl.classList.add('hidden');
}

// hide loading
function hideLoading(canvasId) {
  const loadingEl = document.getElementById(`${canvasId}-loading`);
  const canvasEl = document.getElementById(canvasId);

  if (loadingEl) loadingEl.classList.add('hidden');
  if (canvasEl) canvasEl.classList.remove('hidden');
}

// generate legend label
function generateLegendLabels(chart, prefix = '') {
  return chart.data.labels.map((label, i) => {
    const dataset = chart.data.datasets[0];
    const value = dataset.data[i];
    const sanitizedLabel = sanitizeString(label)
    const displayLabel = prefix ? `${prefix} ${sanitizedLabel}` : sanitizedLabel;

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

// generate tooltip label dengan persentase
function generateTooltipLabel(context) {
  const total = context.dataset.data.reduce((a, b) => a + b, 0);
  const value = context.parsed;
  const percentage = total > 0 ? ((value / total) * 100).toFixed(0) : 0;
  const sanitizedLabel = sanitizeString(context.label)
  return `${sanitizedLabel}: ${value} (${percentage}%)`;
}

// handle fetch error
function handleFetchError(error, canvasId, defaultMessage) {
  console.error(`Error fetching ${canvasId}:`, error);
  hideLoading(canvasId);
  
  const ctx = document.getElementById(canvasId);
  if (ctx) {
    handleEmptyChart(ctx, [0], defaultMessage);
  }
}

// chart intensitas surat tugas
export const intensityStatistics = {
  chart: null,

  // mapping nama bulan untuk title di tooltip
  fullMonthNames: [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ],

  init: async function () {
    const canvasId = 'intensityChart';
    showLoading(canvasId);

    try {
      const res = await fetch('/dashboard/intensity-statistics');
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      const data = await res.json();
      this.renderChart(data);
    } catch (error) {
      handleFetchError(error, canvasId, 'Gagal memuat data intensitas');
    }
  },

  renderChart: function (data) {
    const ctx = document.getElementById("intensityChart");

    if (!ctx) {
      console.error('Canvas element with id "intensityChart" not found.');
      return;
    }

    if (this.chart) this.chart.destroy();
    hideLoading("intensityChart");

    if (handleEmptyChart(ctx, data.data, `Data intensitas tahun ${data.year} masih kosong`)) return;

    this.chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'Jumlah Surat Terbit',
          data: data.data,
          backgroundColor: CHART_COLORS.primary,
          borderRadius: 10,
          barPercentage: 0.5,
        }],
      },
      options: {
        ...defaultChartConfig,
        plugins: {
          legend: {
            display: false,
          },
          title: {
            display: true,
            text: `Statistik Intensitas Surat Tugas Tahun ${data.year}`,
            font: {
              size: 16,
              weight: 'bold'
            }
          },
          tooltip: {
            callbacks: {
              title: (context) => this.fullMonthNames[context[0].dataIndex],
              label: (context) => `${context.parsed.y} Surat`
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.05)',
            },
            title: {
              display: true,
              text: 'Jumlah Surat',
              font: {
                size: 12,
                weight: 'bold'
              }
            },
            ticks: {
              stepSize: 20,
              font: {
                size: 11
              },
              callback: function (value) {
                return value;
              }
            }
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              font: {
                size: 11
              },
              color: "#6B7280",
              autoSkip: false,
              maxRotation: 45,
              minRotation: 0,
            },
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

// chart statistik status pengajuan surat
export const statusStatistics = {
  chart: null,

  init: async function () {
    const canvasId = 'statusChart';
    showLoading(canvasId);

    try {
      const res = await fetch('/dashboard/status-statistics');
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      const data = await res.json();
      this.renderChart(data);
    } catch (error) {
      handleFetchError(error, canvasId, 'Gagal memuat data status pengajuan');
    }
  },

  renderChart: function (data) {
    const ctx = document.getElementById("statusChart");

    if (!ctx) {
      console.error('Canvas element with id "statusChart" not found.');
      return;
    }

    if (this.chart) this.chart.destroy();
    hideLoading("statusChart");

    if (handleEmptyChart(ctx, data.data, `Data statistik tahun ${data.year} masih kosong`)) return;

    const colors = ['#818CF8', '#FB923C', '#F87171', '#22D3EE']

    this.chart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: data.labels,
        datasets: [{
          data: data.data,
          backgroundColor: CHART_COLORS.status,
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
          title: {
            display: true,
            text: `Statistik Status Pengajuan Surat Tahun ${data.year}`,
            font: {
              size: 16,
              weight: 'bold'
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
    const canvasId = 'golonganChart';
    showLoading(canvasId);

    try {
      const res = await fetch('/dashboard/golongan-statistics');
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      const data = await res.json();
      this.renderChart(data);
    } catch (error) {
      handleFetchError(error, canvasId, 'Gagal memuat data golongan');
    }
  },

  renderChart: function (data) {
    const ctx = document.getElementById("golonganChart");

    if (!ctx) {
      console.error('Canvas element with id "golonganChart" not found.');
      return;
    }

    if (this.chart) this.chart.destroy();
    hideLoading("golonganChart");

    if (handleEmptyChart(ctx, data.data, `Data proporsi tahun ${data.year} masih kosong`)) return;

    // golongan label
    const formattedLabels = data.labels.map(label => `Golongan ${sanitizeString(label)}`);

    this.chart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: formattedLabels,
        datasets: [{
          data: data.data,
          backgroundColor: CHART_COLORS.golongan,
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
          title: {
            display: true,
            text: `Proporsi Penugasan Per Golongan Tahun ${data.year}`,
            font: {
              size: 16,
              weight: 'bold'
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
        <p class="mt-2 text-gray-500">${sanitizeString(message)}</p>
      </div>
    `;
    ctx.parentElement.appendChild(emptyMessage);

    return true;
  }

  ctx.style.display = 'block';
  return false;
}