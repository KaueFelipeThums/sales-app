/**
 * Converte um valor numérico ou string em um número inteiro.
 *
 * @param {number|string} value
 * @returns {number}
 */
const parseToInt = (value: number | string): number => {
  const parsedValue = parseInt(value.toString());
  return isNaN(parsedValue) ? 0 : parsedValue;
};

/**
 * Converte um valor numérico ou string em um número de ponto flutuante.
 *
 * @param {number|string} value
 * @returns {number}
 */
const parseToFloat = (value: number | string): number => {
  const parsedValue = parseFloat(value?.toString());
  return isNaN(parsedValue) || !isFinite(parsedValue) ? 0 : parsedValue;
};

/**
 * Converte um valor numérico em float, substituindo o separador de milhares por ponto.
 *
 * @param {number|string} value
 * @returns {number}
 */
const replaceToFloatValue = (value: number | string): number => {
  value = parseToFloat(value?.toString()?.replace(',', '.'));
  return parseToFloat(value);
};

/**
 * Converte um valor numérico em string e substitui o separador decimal por vírgula.
 *
 * @param {number|string} value
 * @returns {string}
 */
const replaceToStringValue = (value: number | string): string => {
  return value?.toString()?.replace('.', ',');
};

export { parseToInt, parseToFloat, replaceToFloatValue, replaceToStringValue };
