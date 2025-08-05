import React, { useEffect, useTransition } from 'react';
import { useForm } from 'react-hook-form';
import { StyleSheet, View } from 'react-native';
import type { UsersCreateUpdate, UsersForm as UsersFormType } from './users-types';
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
import { toast } from '@/core/components/ui/toast';
import { Button } from '@/core/components/ui-presets/button';
import { FormField } from '@/core/components/ui-presets/form-field';
import { InputPassword } from '@/core/components/ui-presets/input-password';
import { InputText } from '@/core/components/ui-presets/input-text';
import { Select } from '@/core/components/ui-presets/select';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import validator from '@/functions/validators';
import { useSync } from '@/providers/sync/sync-provider';

import { useUsersNavigation, useUsersRouteParams } from '@/routes/private-routes/stacks/users-stack-routes';
import { createUserRequest, updateUserRequest } from '@/services/api/users';

const rules = {
  name: { required: 'O nome é obrigatório!' },
  email: { required: 'O email é obrigatório!', validate: (value: string) => validator.email(value) },
  is_active: { required: 'O status é obrigatório!' },
};

const usersStyles = ({ sizes }: ThemeValue) =>
  StyleSheet.create({
    contentContainerList: {
      gap: sizes.gap.xl,
      paddingHorizontal: sizes.padding.xl,
      paddingVertical: sizes.padding.md,
    },
    layout: {
      flex: 1,
    },
  });

const UsersForm = () => {
  const styles = useStyles(usersStyles);
  const [loading, startTransition] = useTransition();
  const { params } = useUsersRouteParams<'UsersForm'>();
  const users = params.users;
  const { setSync } = useSync();
  const navigation = useUsersNavigation();
  const form = useForm<UsersFormType>({
    defaultValues: {
      id: '',
      email: '',
      name: '',
      is_active: '1',
    },
  });

  const createUsers = React.useCallback(
    (data: Omit<UsersCreateUpdate, 'id'>) => {
      startTransition(async () => {
        const response = await createUserRequest(data);

        if (response.success) {
          setSync('users');
          toast.success({ title: 'Registro inserido com sucesso!' });
          navigation.goBack();
          form.reset();
        } else {
          toast.error({ title: 'Ops, houve algum erro!', description: response.error?.message });
        }
      });
    },
    [navigation, setSync, form],
  );

  const updateUsers = React.useCallback(
    (data: UsersCreateUpdate) => {
      startTransition(async () => {
        const response = await updateUserRequest(data);
        if (response.success) {
          setSync('users');
          toast.success({ title: 'Registro alterado com sucesso!' });
          navigation.goBack();
          form.reset();
        } else {
          toast.error({ title: 'Ops, houve algum erro!', description: response.error?.message });
        }
      });
    },
    [navigation, setSync, form],
  );

  useEffect(() => {
    if (users) {
      form.setValue('id', users.id.toString());
      form.setValue('email', users.email);
      form.setValue('name', users.name);
      form.setValue('is_active', users.is_active.toString());
    }
  }, [users, form]);

  const onSubmit = React.useCallback(
    (formData: UsersFormType) => {
      const newFormData: Omit<UsersCreateUpdate, 'id'> = {
        email: formData.email,
        name: formData.name,
        password: formData.password,
        is_active: Number(formData.is_active),
      };

      if (formData.id) {
        updateUsers({
          ...newFormData,
          id: Number(formData.id),
        });
      } else {
        createUsers(newFormData);
      }
    },
    [createUsers, updateUsers],
  );

  return (
    <View style={styles.layout}>
      <Header withInsets variant="ghost">
        <HeaderAdornment>
          <HeaderButton variant="outline" icon="ChevronLeft" onPress={() => navigation.goBack()} />
        </HeaderAdornment>
        <HeaderContent>
          <HeaderTitle align="center">{users ? 'Alterar' : 'Adicionar'} Usuário</HeaderTitle>
        </HeaderContent>
        <HeaderAdornment>
          <HeaderHiddenButton />
        </HeaderAdornment>
      </Header>
      <KeyboardAvoidingContent>
        <ContainerScrollView contentContainerStyle={styles.contentContainerList}>
          <FormField
            control={form.control}
            label="Nome"
            name="name"
            rules={rules.name}
            render={({ field }) => (
              <InputText
                placeholder="Digite o nome..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="E-mail"
            name="email"
            rules={rules.email}
            render={({ field }) => (
              <InputText
                placeholder="Digite o e-mail..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Senha"
            name="password"
            render={({ field }) => (
              <InputPassword
                placeholder="Digite a senha..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Status"
            name="is_active"
            rules={rules.is_active}
            render={({ field }) => (
              <Select
                placeholder="Selecione o status..."
                showSearch={false}
                options={[
                  { label: 'Ativo', value: '1' },
                  { label: 'Inativo', value: '0' },
                ]}
                disabled={loading}
                value={field.value}
                onValueChange={field.onChange}
              />
            )}
          />

          <Button icon="Send" iconPlacement="right" loading={loading} onPress={form.handleSubmit(onSubmit)}>
            Salvar
          </Button>
        </ContainerScrollView>
      </KeyboardAvoidingContent>
    </View>
  );
};

export default UsersForm;
