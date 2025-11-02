export const maskNIP = (nip) => {
  if (!nip) return '';
  const last4 = nip.slice(-4);
  const masked = '*'.repeat(nip.length - 4) + last4;
  return masked;
};

export const maskEmail = (email) => {
  if (!email) return '';
  const [user, domain] = email.split('@');
  if (user.length <= 2) {
    return user[0] + '***@' + domain;
  }
  return user.slice(0, 2) + '***@' + domain;
};