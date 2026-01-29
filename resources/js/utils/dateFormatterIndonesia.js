export const bulanIndonesia = [
  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

export const formatTanggalIndonesia = (dateString) => {
  if (!dateString) return '';
  const [year, month, day] = dateString.split('-');
  return `${parseInt(day)} ${bulanIndonesia[parseInt(month) - 1]} ${year}`;
};

export function formatTanggalWaktuIndonesia(dateString) {
  if (!dateString) return '-';

  const date = new Date(dateString);
  const tanggal = date.getDate();
  const bulanNama = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ];
  const bulan = bulanNama[date.getMonth()];
  const tahun = date.getFullYear();

  const jam = String(date.getHours()).padStart(2, '0');
  const menit = String(date.getMinutes()).padStart(2, '0');
  const detik = String(date.getSeconds()).padStart(2, '0');

  return `${tanggal} ${bulan} ${tahun}, ${jam}:${menit}:${detik}`;
}