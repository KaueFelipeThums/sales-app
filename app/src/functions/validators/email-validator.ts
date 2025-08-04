/**
 * Validação de e-mail
 *
 * @param {string} value - E-mail a ser validado
 * @returns {true | string} - Retorna true ou uma mensagem de erro
 */
const emailValidator = (value?: string | null | undefined): true | string => {
  if (!value) return true;

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (emailRegex.test(value)) {
    return true;
  }
  return 'E-mail inválido!';
};

export { emailValidator };
