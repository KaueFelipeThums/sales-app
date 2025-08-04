import { isValid, isBefore, isAfter, parse, format } from 'date-fns';

/**
 * Parse genérico para datetime, date, e time
 *
 * @param {string | Date} value - Valor a ser convertido
 * @returns {Date | undefined} - Retorna um objeto Date ou undefined
 */
const parseDateTime = (value: string | Date): Date | undefined => {
  if (typeof value === 'string') {
    // Tentar diferentes formatos para strings
    const formats = [
      'dd/MM/yyyy',
      'MM-dd-yyyy',
      'yyyy-MM-dd',
      'HH',
      'HH:mm',
      'HH:mm:ss',
      'dd/MM/yyyy HH',
      'dd/MM/yyyy HH:mm',
      'MM-dd-yyyy HH:mm:ss',
    ];
    for (const format of formats) {
      const parsed = parse(value, format, new Date());
      if (isValid(parsed)) return parsed;
    }
    return undefined;
  }
  if (!isValid(value)) {
    return undefined;
  }
  return value;
};

/**
 * Validação genérica para datetime, time, e date
 *
 * @param {Date | string} value - Valor a ser validado
 * @param {Date | string} [min] - Valor mínimo permitido
 * @param {Date | string} [max] - Valor máximo permitido
 * @param {string} [formatType] - Valor a ser formatado ('datetime' | 'date' | 'time') = 'datetime'
 *
 * @returns {true | string} - Retorna true ou uma mensagem de erro
 */
const dateTimeValidator = (
  value: Date | null | undefined | string,
  formatType: 'datetime' | 'date' | 'time' = 'datetime',
  min?: Date | string,
  max?: Date | string,
): true | string => {
  if (!value) return true; // Permitir valores nulos ou indefinidos

  let parsedValue: Date | undefined;

  // Tentar converter o valor para um objeto Date
  try {
    parsedValue = parseDateTime(value);
  } catch {
    return 'Valor inválido!';
  }

  // Validar se o valor é válido
  if (!parsedValue || !isValid(parsedValue)) {
    return 'Valor inválido!';
  }

  // Processar min e max, se forem strings
  let minValue: Date | undefined;
  let maxValue: Date | undefined;

  try {
    if (min) minValue = parseDateTime(min);
    if (max) maxValue = parseDateTime(max);
  } catch {
    return 'Min ou Max possui formato inválido!';
  }

  // Verificar se o valor é menor que o mínimo permitido
  if (minValue && isBefore(parsedValue, minValue)) {
    if (formatType === 'date') {
      return `O valor deve ser maior ou igual a ${format(minValue, 'dd/MM/yyyy')}.`;
    } else if (formatType === 'time') {
      return `O valor deve ser maior ou igual a ${format(minValue, 'HH:mm:ss')}.`;
    }
    return `O valor deve ser maior ou igual a ${format(minValue, 'dd/MM/yyyy HH:mm:ss')}.`;
  }

  // Verificar se o valor é maior que o máximo permitido
  if (maxValue && isAfter(parsedValue, maxValue)) {
    if (formatType === 'date') {
      return `O valor deve ser menor ou igual a ${format(maxValue, 'dd/MM/yyyy')}.`;
    } else if (formatType === 'time') {
      return `O valor deve ser menor ou igual a ${format(maxValue, 'HH:mm:ss')}.`;
    }
    return `O valor deve ser menor ou igual a ${format(maxValue, 'dd/MM/yyyy HH:mm:ss')}.`;
  }

  return true;
};

export { dateTimeValidator };
