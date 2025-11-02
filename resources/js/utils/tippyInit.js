export const initTippy = () => {
  tippy('[data-tippy-content]', {
    placement: 'left-start',
    arrow: true,
    theme: 'light',
    allowHTML: true,
    trigger: 'mouseenter',
    hideOnClick: true,
    touch: false,
  });
};