import { useCallback, useTransition } from 'react';
import { useForm } from 'react-hook-form';
import { StyleSheet, View } from 'react-native';
import type { Login as LoginType } from './login-types';
import { ContainerScrollView } from '@/components/layout/container';
import { KeyboardAvoidingContent } from '@/core/components/ui/keyboard-avoid-content';
import { Text } from '@/core/components/ui/text';
import { toast } from '@/core/components/ui/toast';
import { Button } from '@/core/components/ui-presets/button';
import { FormField } from '@/core/components/ui-presets/form-field';
import { InputPassword } from '@/core/components/ui-presets/input-password';
import { InputText } from '@/core/components/ui-presets/input-text';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import validator from '@/functions/validators';
import { useAuth } from '@/hooks/use-auth';
import { loginRequest } from '@/services/api/auth';

const rules = {
  email: { required: 'O email é obrigatório', validate: (value: string) => validator.email(value) },
  password: { required: 'A senha é obrigatória' },
};

const loginStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    container: {
      gap: sizes.gap['4xl'],
      paddingHorizontal: sizes.padding.xl,
    },
    description: {
      color: colors.mutedForeground,
      fontSize: sizes.fontSize.sm,
      textAlign: 'left',
    },
    descriptionContent: {
      alignItems: 'flex-start',
      gap: sizes.gap.xl,
    },
    formContent: {
      flex: 1,
      gap: sizes.gap.xl,
    },
    label: {
      color: colors.mutedForeground,
      fontSize: sizes.fontSize.xs,
      textAlign: 'center',
    },
    logo: {
      height: 120,
      resizeMode: 'contain',
      width: 200,
    },
    title: {
      color: colors.foreground,
      fontSize: sizes.fontSize['2xl'],
      fontWeight: sizes.fontWeight.bold,
      textAlign: 'left',
    },
  });

const Login = () => {
  const styles = useStyles(loginStyles);
  const { createSession } = useAuth();
  const [loading, startTransition] = useTransition();
  const form = useForm<LoginType>({
    defaultValues: {
      email: '',
      password: '',
    },
  });

  const onSubmit = useCallback(
    (data: LoginType) => {
      startTransition(async () => {
        const response = await loginRequest(data);
        if (response.success) {
          createSession(
            {
              user_id: response.data.user.id,
              user_email: response.data.user.email,
              user_name: response.data.user.name,
            },
            response.data.access_token,
          );
          form.reset();
        } else {
          toast.error({ title: 'Ops houve algum erro!', description: response.error?.message });
        }
      });
    },
    [createSession, form],
  );

  return (
    <KeyboardAvoidingContent>
      <ContainerScrollView contentContainerStyle={styles.container} keyboardShouldPersistTaps="handled">
        <View style={styles.descriptionContent}>
          <Text style={styles.title}>Bem vindo!</Text>
          <Text style={styles.description}>Informe seu E-mail para acessar o aplicativo de vendas.</Text>
        </View>

        <View style={styles.formContent}>
          <FormField
            control={form.control}
            label="Login"
            name="email"
            rules={rules.email}
            render={({ field }) => (
              <InputText
                onChangeText={field.onChange}
                value={field.value}
                placeholder="Informe seu email..."
                disabled={loading}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Senha"
            name="password"
            rules={rules.password}
            render={({ field }) => (
              <InputPassword
                onChangeText={field.onChange}
                value={field.value}
                placeholder="Informe sua senha..."
                disabled={loading}
              />
            )}
          />

          <Button iconPlacement="right" icon="Send" onPress={form.handleSubmit(onSubmit)} loading={loading}>
            Entrar
          </Button>
        </View>

        <Text style={styles.label}>Aplicativo desenvolvido por Kaue Thums</Text>
      </ContainerScrollView>
    </KeyboardAvoidingContent>
  );
};

export default Login;
