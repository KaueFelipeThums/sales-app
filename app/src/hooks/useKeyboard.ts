import { useEffect, useState } from 'react';
import { Keyboard, Platform } from 'react-native';

type ReturnType = {
  keyboardVisible: boolean;
};

const useKeyboard = (): ReturnType => {
  const [keyboardVisible, setKeyboardVisible] = useState(false);

  useEffect(() => {
    const onKeyboardShow = () => {
      setKeyboardVisible(true);
    };

    const onKeyboardHide = () => {
      setKeyboardVisible(false);
    };

    const showEvent = Platform.OS === 'ios' ? 'keyboardWillShow' : 'keyboardDidShow';
    const hideEvent = Platform.OS === 'ios' ? 'keyboardWillHide' : 'keyboardDidHide';

    const showListener = Keyboard.addListener(showEvent, onKeyboardShow);
    const hideListener = Keyboard.addListener(hideEvent, onKeyboardHide);

    return () => {
      showListener.remove();
      hideListener.remove();
    };
  }, []);

  return { keyboardVisible };
};

export { useKeyboard };
