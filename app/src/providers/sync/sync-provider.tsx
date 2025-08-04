import React, { createContext, useCallback, useContext, useRef } from 'react';

type SyncContextType = {
  getShouldSync: (key: string) => boolean;
  setSync: (key: string) => void;
  clearSync: (key: string) => void;
};

const SyncContext = createContext<SyncContextType | null>(null);

export const SyncProvider = ({ children }: { children: React.ReactNode }) => {
  const syncFlags = useRef<Record<string, boolean>>({});

  const getShouldSync = useCallback((key: string) => {
    return syncFlags.current[key] ?? false;
  }, []);

  const setSync = useCallback((key: string) => {
    syncFlags.current[key] = true;
  }, []);

  const clearSync = useCallback((key: string) => {
    syncFlags.current[key] = false;
  }, []);

  return (
    <SyncContext.Provider
      value={{
        getShouldSync,
        setSync,
        clearSync,
      }}
    >
      {children}
    </SyncContext.Provider>
  );
};

export const useSync = () => {
  const ctx = useContext(SyncContext);
  if (!ctx) throw new Error('useSync must be used inside a SyncProvider');
  return ctx;
};
