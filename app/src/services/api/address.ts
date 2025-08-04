import apiRequest from './request/api-request';
import { ApiResponse } from './request/api-request-types';

type Address = {
  cep: string | null;
  logradouro: string | null;
  complemento: string | null;
  unidade: string | null;
  bairro: string | null;
  localidade: string | null;
  uf: string | null;
  estado: string | null;
  regiao: string | null;
  ibge: string | null;
  gia: string | null;
  ddd: string | null;
  siafi: string | null;
};

const getAddressRequest = async (cep: string): Promise<ApiResponse<Address>> => {
  const response = await apiRequest<Address>({
    method: 'GET',
    url: `v1/address/get/${cep}`,
  });
  return response;
};

export { getAddressRequest };
