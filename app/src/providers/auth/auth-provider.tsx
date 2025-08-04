import { createContext, ReactElement, ReactNode, useCallback, useEffect, useState } from 'react';
import { Session } from './auth-provider-types';
import { storage } from '@/core/utils/local-storage';
import { getUserRequest } from '@/services/api/auth';

type AuthProviderState = {
  isAuthenticated: boolean;
  isLoadingSession: boolean;
  session: Session | null;
  createSession: (user: Session, authToken: string) => Promise<void>;
  createSymbolicSession: (user: Session) => Promise<void>;
  fetchUser: () => Promise<void>;
  endSession: () => Promise<void>;
  updateSession: (newSession: Partial<Session>) => Promise<void>;
};

const AuthContext = createContext<AuthProviderState>({
  isAuthenticated: false,
  isLoadingSession: false,
  session: null,
  createSymbolicSession: async () => {},
  createSession: async () => {},
  endSession: async () => {},
  fetchUser: async () => {},
  updateSession: async () => {},
});

const AuthProvider = ({ children }: { children: ReactNode }): ReactElement => {
  const [session, setSession] = useState<Session | null>(null);
  const [loadingSession, setLoadingSession] = useState<boolean>(true);
  const isAuthenticated: boolean = !!session;

  const initializeSession = useCallback(async () => {
    setLoadingSession(true);
    const sessionData: Session = await storage.get('sales-app-user');
    if (sessionData) setSession(sessionData);
    setLoadingSession(false);
  }, []);

  useEffect(() => {
    initializeSession();
  }, [initializeSession]);

  const createSymbolicSession = useCallback(async (newSession: Session) => {
    await storage.set('sales-app-user', newSession);
    setSession(newSession);
  }, []);

  const createSession = useCallback(async (newSession: Session, authToken: string) => {
    await storage.set('sales-app-user', newSession);
    await storage.set('sales-app-auth-token', authToken);
    setSession(newSession);
  }, []);

  const endSession = useCallback(async () => {
    await storage.remove('sales-app-user');
    await storage.remove('sales-app-auth-token');
    setSession(null);
  }, []);

  const updateSession = useCallback(
    async (newSession: Partial<Session>) => {
      const updatedSession = { ...session, ...newSession };
      await storage.set('sales-app-user', updatedSession);
      setSession(updatedSession as Session);
    },
    [session],
  );

  const fetchUser = useCallback(async () => {
    if (session?.user_id === null) return;

    const response = await getUserRequest();
    if (response.success) {
      if (response.data.is_active === 0) {
        endSession();
        return;
      }

      await storage.set('sales-app-user', {
        user_id: response.data.id,
        user_email: response.data.email,
        user_name: response.data.name,
      });

      setSession({
        user_id: response.data.id,
        user_email: response.data.email,
        user_name: response.data.name,
      });
    } else if (response.error.code === 401) {
      endSession();
    }
  }, [endSession, session?.user_id]);

  return (
    <AuthContext.Provider
      value={{
        isAuthenticated,
        isLoadingSession: loadingSession,
        session,
        createSession,
        createSymbolicSession,
        endSession,
        updateSession,
        fetchUser,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export type { AuthProviderState };
export { AuthContext };
export default AuthProvider;
