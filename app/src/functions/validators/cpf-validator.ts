/**
 * CPF Validation
 *
 * @param {string} value - CPF to be validated
 * @returns {true | string} - Returns true if valid or an error message
 */
const cpfValidator = (value?: string | null | undefined): true | string => {
  if (!value) return true;

  const cleanedCpf = value.replace(/\D/g, '');

  if (cleanedCpf.length !== 11) {
    return 'CPF inválido!';
  }

  if (
    [
      '00000000000',
      '11111111111',
      '22222222222',
      '33333333333',
      '44444444444',
      '55555555555',
      '66666666666',
      '77777777777',
      '88888888888',
      '99999999999',
    ].indexOf(cleanedCpf) !== -1
  ) {
    return 'CPF inválido!';
  }

  let sum = 0;
  let remainder;

  // Calculate the first verification digit
  for (let i = 1; i <= 9; i++) {
    sum += parseInt(cleanedCpf.substring(i - 1, i)) * (11 - i);
  }

  remainder = (sum * 10) % 11;
  if (remainder === 10 || remainder === 11) remainder = 0;

  if (remainder !== parseInt(cleanedCpf.substring(9, 10))) {
    return 'CPF inválido!';
  }

  sum = 0;

  // Calculate the second verification digit
  for (let i = 1; i <= 10; i++) {
    sum += parseInt(cleanedCpf.substring(i - 1, i)) * (12 - i);
  }

  remainder = (sum * 10) % 11;
  if (remainder === 10 || remainder === 11) remainder = 0;

  if (remainder !== parseInt(cleanedCpf.substring(10, 11))) {
    return 'CPF inválido!';
  }

  return true;
};

export { cpfValidator };
