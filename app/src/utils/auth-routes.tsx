import { LoadingSection } from '@/components/layout/loading-section';
import { useAuth } from '@/hooks/use-auth';

type AuthRoutesProps = {
  publicRoutesElement: React.ReactElement;
  privateRoutesElement: React.ReactElement;
};

const AuthRoutes = ({ publicRoutesElement, privateRoutesElement }: AuthRoutesProps) => {
  const { isAuthenticated, isLoadingSession } = useAuth();

  if (isLoadingSession) return <LoadingSection />;
  if (!isAuthenticated) return publicRoutesElement;
  return privateRoutesElement;
};

export default AuthRoutes;
