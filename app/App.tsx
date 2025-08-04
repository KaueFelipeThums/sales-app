import React from 'react';
import { PortalProvider } from '@/core/components/primitves/portal/portal-provider';
import { SafeAreaProvider } from '@/core/components/ui/safe-area-content';
import { Toaster } from '@/core/components/ui/toast';
import PopConfirmProvider from '@/core/components/ui-presets/popconfirm';
import { ThemeProvider } from '@/core/theme/theme-provider/theme-provider';
import AuthProvider from '@/providers/auth/auth-provider';
import { SyncProvider } from '@/providers/sync/sync-provider';
import Routes from '@/routes/routes';

function App(): React.JSX.Element {
  return (
    <AuthProvider>
      <SyncProvider>
        <ThemeProvider>
          <SafeAreaProvider>
            <PortalProvider>
              <PopConfirmProvider>
                <Toaster>
                  <Routes />
                </Toaster>
              </PopConfirmProvider>
            </PortalProvider>
          </SafeAreaProvider>
        </ThemeProvider>
      </SyncProvider>
    </AuthProvider>
  );
}

export default App;
