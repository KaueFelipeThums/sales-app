import { useCallback, useState, useTransition } from 'react';
import { useForm } from 'react-hook-form';
import { StyleSheet, View } from 'react-native';
import type { UpdatePassword as UpdatePasswordType } from './update-password-types';
import { ContainerScrollView } from '@/components/layout/container';
import {
  Header,
  HeaderAdornment,
  HeaderButton,
  HeaderContent,
  HeaderHiddenButton,
  HeaderTitle,
} from '@/components/layout/header';
import { KeyboardAvoidingContent } from '@/core/components/ui/keyboard-avoid-content';
import { Progress } from '@/core/components/ui/progress';
import { Text } from '@/core/components/ui/text';
import { toast } from '@/core/components/ui/toast';
import { Button } from '@/core/components/ui-presets/button';
import { FormField } from '@/core/components/ui-presets/form-field';
import { InputPassword } from '@/core/components/ui-presets/input-password';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import { checkPasswordStrength } from '@/functions/check-password-strenght';
import { useProfileNavigation } from '@/routes/private-routes/stacks/profile-stack-routes';
import { updatePasswordRequest } from '@/services/api/auth';

const rules = {
  password: { required: 'O senha atual é obrigatória!' },
  new_password: { required: 'A nova senha é obrigatória!' },
  new_password_confirmation: { required: 'A confirmação de senha é obrigatória!' },
};

const updatePasswordStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
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
      gap: sizes.gap.xl,
    },
    layout: {
      flex: 1,
    },
    list: {
      gap: sizes.gap['4xl'],
      paddingHorizontal: sizes.padding.xl,
    },
    passwordInfoLabel: {
      color: colors.mutedForeground,
      fontSize: sizes.fontSize.xs,
      textAlign: 'left',
    },
    passwordInfoTitle: {
      color: colors.mutedForeground,
      fontSize: sizes.fontSize.xs,
      fontWeight: sizes.fontWeight.semibold,
      textAlign: 'left',
    },
    title: {
      color: colors.foreground,
      fontSize: sizes.fontSize['2xl'],
      fontWeight: sizes.fontWeight.bold,
      textAlign: 'left',
    },
  });

const UpdatePassword = () => {
  const styles = useStyles(updatePasswordStyles);
  const [loading, startTransition] = useTransition();
  const navigation = useProfileNavigation();
  const [passwordStrength, setPasswordStrength] = useState<number>(0);
  const form = useForm<UpdatePasswordType>({
    defaultValues: {
      password: '',
      new_password: '',
      new_password_confirmation: '',
    },
  });

  const updatePassword = useCallback(
    (data: Omit<UpdatePasswordType, 'new_password_confirmation'>) => {
      startTransition(async () => {
        const response = await updatePasswordRequest(data);
        if (response.success) {
          toast.success({ title: 'Senha alterada com sucesso!' });
          navigation.goBack();
          form.reset();
        } else {
          toast.error({ title: 'Ops houve algum erro!', description: response.error?.message });
        }
      });
    },
    [navigation, form],
  );

  const onSubmit = useCallback(
    (formData: UpdatePasswordType) => {
      if (formData.new_password !== formData.new_password_confirmation) {
        form.setError('new_password_confirmation', { message: 'As senhas devem ser iguais!' });
        return;
      }

      updatePassword({
        password: formData.password,
        new_password: formData.new_password,
      });
    },
    [updatePassword, form],
  );

  return (
    <View style={styles.layout}>
      <Header withInsets variant="ghost">
        <HeaderAdornment>
          <HeaderButton variant="outline" icon="ChevronLeft" onPress={() => navigation.goBack()} />
        </HeaderAdornment>
        <HeaderContent>
          <HeaderTitle align="center">Alterar Senha</HeaderTitle>
        </HeaderContent>
        <HeaderAdornment>
          <HeaderHiddenButton />
        </HeaderAdornment>
      </Header>

      <KeyboardAvoidingContent>
        <ContainerScrollView contentContainerStyle={styles.list} keyboardShouldPersistTaps="handled">
          <View style={styles.formContent}>
            <FormField
              control={form.control}
              label="Senha Atual"
              name="password"
              rules={rules.password}
              render={({ field }) => (
                <InputPassword
                  value={field.value}
                  onChangeText={field.onChange}
                  placeholder="Informe a senha atual..."
                  disabled={loading}
                />
              )}
            />
            <FormField
              control={form.control}
              label="Nova Senha"
              name="new_password"
              rules={rules.new_password}
              render={({ field }) => (
                <InputPassword
                  value={field.value}
                  onChangeText={(input) => {
                    field.onChange(input);
                    setPasswordStrength(checkPasswordStrength(input));
                  }}
                  placeholder="Informe a nova senha..."
                  disabled={loading}
                />
              )}
            />
            <FormField
              control={form.control}
              label="Confirmação de Senha"
              name="new_password_confirmation"
              rules={rules.new_password_confirmation}
              render={({ field }) => (
                <InputPassword
                  onChangeText={field.onChange}
                  value={field.value}
                  placeholder="Informe a confirmação de senha..."
                  disabled={loading}
                />
              )}
            />

            <Progress value={passwordStrength} />
            <View>
              <Text style={styles.passwordInfoTitle}>Sua senha deve conter:</Text>
              <Text style={styles.passwordInfoLabel}>· 1 letra maiúscula</Text>
              <Text style={styles.passwordInfoLabel}>· 1 caractere especial</Text>
              <Text style={styles.passwordInfoLabel}>· 1 letra minúscula</Text>
              <Text style={styles.passwordInfoLabel}>· 1 número</Text>
            </View>
            <Button iconPlacement="right" icon="Send" onPress={form.handleSubmit(onSubmit)} loading={loading}>
              Enviar
            </Button>
          </View>
        </ContainerScrollView>
      </KeyboardAvoidingContent>
    </View>
  );
};

export default UpdatePassword;
