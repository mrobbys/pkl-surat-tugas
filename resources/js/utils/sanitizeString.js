export function sanitizeString(str) {
  const temp = document.createElement('div');
  temp.textContent = str;
  return temp.innerHTML;
}