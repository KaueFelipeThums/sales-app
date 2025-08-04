import { useNetInfo } from '@react-native-community/netinfo';
import { useEffect } from 'react';
import { usePopConfirm } from '@/core/components/ui-presets/popconfirm';

const useNetConnection = () => {
  const { isConnected } = useNetInfo();
  const { open } = usePopConfirm();

  useEffect(() => {
    if (isConnected === false) {
      open({
        hideCancelButton: true,
        title: 'Sem conexão',
        description: 'Você está sem conexão com a internet!',
        variant: 'default',
        confirmText: 'OK',
      });
    }
  }, [isConnected, open]);
};

export { useNetConnection };
