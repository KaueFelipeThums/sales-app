type ApiErrorCause<TFields = string> = {
  /** Campo específico relacionado ao erro. */
  field: TFields;

  /** Mensagem descritiva sobre o problema no campo. */
  message: string;
};

type ApiError<TFields = string> = {
  /** Código do erro retornado pela API. */
  code: number;

  /** Mensagem descritiva do erro principal. */
  message: string;

  /** Detalhe técnico ou identificador do erro. */
  error: string;

  /** Lista de causas adicionais relacionadas ao erro. */
  causes?: ApiErrorCause<TFields>[];
};

type ErrorResponse<TFields = string> = {
  /** Indica que a resposta não foi bem-sucedida. */
  success: false;

  /** Objeto contendo informações detalhadas sobre o erro. */
  error: ApiError<TFields>;
};

type SuccessResponse<T> = {
  /** Indica que a resposta foi bem-sucedida. */
  success: true;

  /** Dados retornados pela API no caso de sucesso. */
  data: T;
};

type ApiResponse<T, TFields = string> = ErrorResponse<TFields> | SuccessResponse<T>;

export { ApiResponse, ErrorResponse, SuccessResponse, ApiError, ApiErrorCause };
