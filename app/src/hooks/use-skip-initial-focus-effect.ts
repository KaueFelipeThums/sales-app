import { useFocusEffect } from '@react-navigation/native';
import { useRef, useCallback, EffectCallback } from 'react';

/**
 * Dispara o callback apenas nos focos *subsequentes* à montagem/foco inicial da tela.
 *
 * @param effect  Função que será executada quando a tela receber foco (exceto no primeiro)
 *                mesmíssimo tipo do React.useEffect: pode retornar void ou um cleanup.
 * @param deps    Lista de dependências (mesmo comportamento do useEffect)
 */
const useSkipInitialFocusEffect = (effect: EffectCallback): void => {
  const isFirstFocusRef = useRef(true);

  const wrappedEffect = useCallback(() => {
    if (isFirstFocusRef.current) {
      isFirstFocusRef.current = false;
      return;
    }

    const cleanup = effect();
    return () => {
      if (typeof cleanup === 'function') cleanup();
    };
  }, [effect]);

  useFocusEffect(wrappedEffect);
};

export { useSkipInitialFocusEffect };
