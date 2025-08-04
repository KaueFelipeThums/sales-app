/**
 * Validação de valores numéricos
 *
 * @param {string | number} value - Valor a ser validado
 * @param {number} [min] - Valor mínimo para validação
 * @param {number} [max] - Valor máximo para validação
 *
 * @returns {true | string} - Retorna true ou uma mensagem de erro
 */
const numberValidator = (value?: string | number | null | undefined, min?: number, max?: number): true | string => {
  if (!value) return true;

  const valueString = String(value).replace(',', '.');
  const valueNumber = Number(valueString);

  if (isNaN(valueNumber)) {
    return 'Valor inválido!';
  }

  if (min !== undefined && valueNumber < min) {
    return `O valor precisa ser maior que ${min}!`;
  }

  if (max !== undefined && valueNumber > max) {
    return `O valor precisa ser menor que ${max}!`;
  }

  return true;
};

export { numberValidator };
