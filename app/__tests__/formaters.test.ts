import formaters from '@/functions/formaters';

const { money, cpf, phone, cep, cnpj, letters, numbers } = formaters;

describe('funções de formatação', () => {
  describe('maskMoney', () => {
    it('deve formatar número como moeda brasileira', () => {
      expect(money(1234.56)).toBe('1.234,56');
      expect(money('1234,56')).toBe('1.234,56');
      expect(money('1000000')).toBe('1.000.000,00');
    });
    it('deve retornar string vazia para entrada inválida', () => {
      expect(money('abc')).toBe('');
      expect(money('')).toBe('');
      expect(money(null as any)).toBe('');
    });
    it('deve respeitar o parâmetro digits', () => {
      expect(money(1234.5678, 3)).toBe('1.234,568');
    });
  });

  describe('maskCpf', () => {
    it('deve formatar CPF corretamente', () => {
      expect(cpf('12345678901')).toBe('123.456.789-01');
      expect(cpf('123.456.789-01')).toBe('123.456.789-01');
      expect(cpf('123456789')).toBe('123.456.789-');
    });
  });

  describe('maskPhone', () => {
    it('deve formatar telefone fixo com 10 dígitos corretamente', () => {
      expect(phone('1123456789')).toBe('(11) 2345-6789');
    });
    it('deve formatar celular com 11 dígitos corretamente', () => {
      expect(phone('11987654321')).toBe('(11) 98765-4321');
    });
    it('deve retornar string vazia para entrada inválida', () => {
      expect(phone('')).toBe('');
      expect(phone(null as any)).toBe('');
    });
  });

  describe('maskCep', () => {
    it('deve formatar CEP corretamente', () => {
      expect(cep('12345678')).toBe('12345-678');
      expect(cep('12345-678')).toBe('12345-678');
    });
  });

  describe('maskCNPJ', () => {
    it('deve formatar CNPJ corretamente', () => {
      expect(cnpj('12345678000195')).toBe('12.345.678/0001-95');
      expect(cnpj('12.345.678/0001-95')).toBe('12.345.678/0001-95');
    });
  });

  describe('maskLetters', () => {
    it('deve remover números e caracteres especiais', () => {
      expect(letters('abc123!@#def')).toBe('abcdef');
      expect(letters('!@#$%^&*()_+')).toBe('');
    });
  });

  describe('maskNumbers', () => {
    it('deve manter apenas números', () => {
      expect(numbers('abc123def456')).toBe('123456');
      expect(numbers('!@#123$%^')).toBe('123');
      expect(numbers('sem números')).toBe('');
    });
  });
});
