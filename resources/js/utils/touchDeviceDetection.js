export const isTouchDevice = () => {
  return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
};

export const focusIfNotTouch = (element) => {
  if (!isTouchDevice() && element) {
    element.focus();
  }
};