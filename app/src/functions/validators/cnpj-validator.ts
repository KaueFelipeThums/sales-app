const validateDigit = (arr: number[], position: number): boolean => {
  let weights: number[];
  let arrayItems: number;
  let sum = 0;

  if (position === 1) {
    weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    arrayItems = 12;
  } else {
    weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    arrayItems = 13;
  }

  for (let i = 0; i < arrayItems; i += 1) {
    const calc = weights[i] * arr[i];
    sum += calc;
  }

  const division = Math.floor(sum % 11);
  let verifyingDigit = 0;

  if (division >= 2) {
    verifyingDigit = 11 - division;
  }

  if (arr[arrayItems] !== verifyingDigit) {
    return false;
  }

  return true;
};

/**
 * Validação de CNPJ
 *
 * @param {string} value - CNPJ a ser validado
 * @returns {true | string} - Retorna true se válido ou uma mensagem de erro
 */
const cnpjValidator = (value?: string | null): true | string => {
  if (!value) return true;

  const cnpj = value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
  if (typeof cnpj !== 'string' && typeof cnpj !== 'number') {
    return 'CNPJ inválido!';
  }

  let filteredCNPJ = String(cnpj);
  filteredCNPJ = filteredCNPJ.replace(/\.|-|\//g, '');

  if (filteredCNPJ.length !== 14) {
    return 'CNPJ inválido!';
  }

  const arrCNPJ: number[] = Array.from(filteredCNPJ, Number);

  const repeatedNumbers: boolean = arrCNPJ.every((num, _, arr) => num === arr[0]);
  if (repeatedNumbers) {
    return 'CNPJ inválido!';
  }

  const firstDigit = validateDigit(arrCNPJ, 1);
  const secondDigit = validateDigit(arrCNPJ, 2);
  if (!firstDigit || !secondDigit) {
    return 'CNPJ inválido!';
  }

  return true;
};

export { cnpjValidator };
