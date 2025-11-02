export const bulanIndonesia = [
  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

export const formatTanggalIndonesia = (dateString) => {
  if (!dateString) return '';
  const [year, month, day] = dateString.split('-');
  return `${parseInt(day)} ${bulanIndonesia[parseInt(month) - 1]} ${year}`;
};