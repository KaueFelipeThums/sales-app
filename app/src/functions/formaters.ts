/**
 * Formata um valor numérico ou string como um valor monetário seguindo a cultura pt-BR.
 *
 * @param {number|string} value
 * @param {number} [digits=2]
 * @returns {string}
 */
const maskMoney = (value: number | string, digits: number = 2): string => {
  const parsedValue = parseFloat(value?.toString()?.replace(',', '.'));
  if (isNaN(parsedValue)) return '';

  const fixedValue = parsedValue.toFixed(digits);

  const [integerPartRaw, decimalPart] = fixedValue.split('.');
  const integerPart = integerPartRaw.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

  const formattedValue = `${integerPart},${decimalPart}`;
  return formattedValue;
};

/**
 * Formata um CPF em formato padrão.
 *
 * @param {number|string} value
 * @returns {string}
 */
const maskCpf = (value: number | string): string => {
  const str = value?.toString().replace(/\D/g, '') || '';
  if (str.length <= 9) {
    // Se tiver menos que 11 dígitos, tenta formatar até onde dá, adicionando o traço no final
    return str
      .replace(/(\d{3})(\d)/, '$1.$2')
      .replace(/(\d{3})(\d)/, '$1.$2')
      .replace(/(\d{3})?$/, '$1-');
  }
  return str
    .replace(/(\d{3})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d{1,2})/, '$1-$2')
    .replace(/(-\d{2})\d+?$/, '$1');
};

/**
 * Formata um número de telefone brasileiro em formato padrão.
 *
 * @param {number|string} value
 * @returns {string}
 */
const maskPhone = (value: number | string): string => {
  const numeric = value?.toString().replace(/\D/g, '');

  if (!numeric) return '';

  let result = numeric.replace(/(\d{2})(\d)/, '($1) $2');

  if (numeric.length >= 11) {
    // Celular com 9 dígitos
    result = result.replace(/(\d{5})(\d)/, '$1-$2');
  } else {
    // Fixo com 8 dígitos
    result = result.replace(/(\d{4})(\d)/, '$1-$2');
  }

  return result.replace(/(-\d{4})(\d+?)$/, '$1'); // Remover sobra, se tiver
};

/**
 * Formata um CNPJ em formato padrão.
 *
 * @param {number|string} value
 * @returns {string}
 */
const maskCNPJ = (value: number | string): string => {
  return value
    ?.toString()
    ?.replace(/\D/g, '') // Remove tudo que não for número
    ?.replace(/^(\d{2})(\d)/, '$1.$2')
    ?.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
    ?.replace(/\.(\d{3})(\d)/, '.$1/$2')
    ?.replace(/(\d{4})(\d)/, '$1-$2');
};

/**
 * Formata um CEP brasileiro em formato padrão.
 *
 * @param {number|string} value
 * @returns {string}
 */
const maskCep = (value: number | string): string => {
  return value
    ?.toString()
    ?.replace(/\D/g, '')
    .replace(/^(\d{5})(\d{3})+?$/, '$1-$2');
};

/**
 * Remove os caracteres numéricos e especiais de uma string.
 *
 * @param {number|string} value
 * @returns {string}
 */
const maskLetters = (value: number | string): string => {
  return value?.toString()?.replace(/[0-9!@#¨$%^&*)(+=._-]+/g, '');
};

/**
 * Remove os caracteres não numéricos de uma string.
 *
 * @param {number|string} value
 * @returns {string}
 */
const maskNumbers = (value: number | string): string => {
  return value?.toString()?.replace(/[^0-9]/g, '');
};

const formaters = {
  money: maskMoney,
  cpf: maskCpf,
  phone: maskPhone,
  cep: maskCep,
  cnpj: maskCNPJ,
  letters: maskLetters,
  numbers: maskNumbers,
};

export default formaters;
