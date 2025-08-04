/**
 * Validação de celular
 *
 * @param {string} value - Celular a ser validado
 * @returns {true | string} - Retorna true ou uma mensagem de erro
 */
const phoneValidator = (value?: string | null | undefined): true | string => {
  if (!value) return true;

  const cleanedPhone = value.replace(/\D/g, '');
  const phoneRegex = /^[0-9]{10,11}$/;
  return phoneRegex.test(cleanedPhone) ? true : 'Celular inválido!';
};

export { phoneValidator };
