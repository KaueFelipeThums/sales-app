<?php
namespace SalesAppApi\Shared\Exceptions;

use Exception;

class EnvironmentAwareException extends Exception
{
    private $env;

    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
        $this->env = $_ENV['APP_ENV'] ?? 'local';
    }

    public function getUserMessage(): string
    {
        if ($this->env === 'local') {
            return $this->getMessage();
        }
        return 'Erro no servidor';
    }
}
