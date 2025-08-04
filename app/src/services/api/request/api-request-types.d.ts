type ApiError = {
  /** Código do erro retornado pela API. */
  code: number;

  /** Mensagem descritiva do erro principal. */
  message: string;
};

type ErrorResponse = {
  /** Indica que a resposta não foi bem-sucedida. */
  success: false;

  /** Objeto contendo informações detalhadas sobre o erro. */
  error: ApiError;
};

type SuccessResponse<T> = {
  /** Indica que a resposta foi bem-sucedida. */
  success: true;

  /** Dados retornados pela API no caso de sucesso. */
  data: T;
};

type ApiResponse<T> = ErrorResponse | SuccessResponse<T>;

export { ApiResponse, ErrorResponse, SuccessResponse, ApiError, ApiErrorCause };
