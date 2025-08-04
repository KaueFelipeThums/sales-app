import { cnpjValidator } from './cnpj-validator';
import { cpfValidator } from './cpf-validator';
import { creditCardNumberValidator } from './credit-card-number-validator';
import { dateTimeValidator } from './datetime-validator';
import { emailValidator } from './email-validator';
import { numberValidator } from './number-validator';
import { phoneValidator } from './phone-validator';

const validator = {
  email: emailValidator,
  phone: phoneValidator,
  dateTime: dateTimeValidator,
  creditCardNumber: creditCardNumberValidator,
  cpf: cpfValidator,
  cnpj: cnpjValidator,
  number: numberValidator,
};

export default validator;
