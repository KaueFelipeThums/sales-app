import { parseToFloat, parseToInt, replaceToFloatValue, replaceToStringValue } from '@/functions/parsers';

describe('funções de utilitários numéricos', () => {
  describe('parseToInt', () => {
    it('deve converter string ou número para inteiro', () => {
      expect(parseToInt('10')).toBe(10);
      expect(parseToInt(20)).toBe(20);
    });

    it('deve retornar 0 se o valor não for número válido', () => {
      expect(parseToInt('abc')).toBe(0);
      expect(parseToInt('')).toBe(0);
      expect(parseToInt(NaN as any)).toBe(0);
    });
  });

  describe('parseToFloat', () => {
    it('deve converter string ou número para float', () => {
      expect(parseToFloat('10.5')).toBe(10.5);
      expect(parseToFloat(20.1)).toBe(20.1);
    });

    it('deve retornar 0 se o valor for NaN ou infinito', () => {
      expect(parseToFloat('abc')).toBe(0);
      expect(parseToFloat(Infinity as any)).toBe(0);
      expect(parseToFloat(-Infinity as any)).toBe(0);
    });
  });

  describe('replaceToFloatValue', () => {
    it('deve substituir vírgula por ponto e converter para float', () => {
      expect(replaceToFloatValue('10,5')).toBe(10.5);
      expect(replaceToFloatValue('1000,75')).toBe(1000.75);
      expect(replaceToFloatValue(15.25)).toBe(15.25);
    });

    it('deve retornar 0 para valores inválidos', () => {
      expect(replaceToFloatValue('abc')).toBe(0);
    });
  });

  describe('replaceToStringValue', () => {
    it('deve substituir ponto por vírgula', () => {
      expect(replaceToStringValue('10.5')).toBe('10,5');
      expect(replaceToStringValue(1000.75)).toBe('1000,75');
    });

    it('deve lidar corretamente com valores vazios ou nulos', () => {
      expect(replaceToStringValue('')).toBe('');
      expect(replaceToStringValue(null as any)).toBe('');
      expect(replaceToStringValue(undefined as any)).toBe('');
    });
  });
});
