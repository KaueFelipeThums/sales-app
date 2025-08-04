import { useContext } from 'react';
import { AuthProviderState, AuthContext } from '@/providers/auth/auth-provider';

/**
 * Hook para acessar o contexto de autenticação.
 *
 * @returns {AuthProviderState}
 * @throws {Error}
 */
const useAuth = (): AuthProviderState => {
  const context = useContext(AuthContext);
  if (context === undefined) throw new Error('useAuth must be used within a AuthProvider');
  return context;
};

export { useAuth };
