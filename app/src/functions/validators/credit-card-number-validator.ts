/**
 * Validação de número de cartão de crédito
 *
 * @param {string} value - Número do cartão a ser validado
 * @returns {true | string} - Retorna true se válido ou uma mensagem de erro
 */
const creditCardNumberValidator = (value?: string | null | undefined): true | string => {
  if (!value) return true;

  const cleanedCard = value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos

  // Validação do comprimento do número do cartão
  if (cleanedCard.length < 13 || cleanedCard.length > 20) {
    return 'Número de cartão incompleto!';
  }
  return true;
};

export { creditCardNumberValidator };
