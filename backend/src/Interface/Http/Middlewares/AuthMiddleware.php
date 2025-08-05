<?php

namespace SalesAppApi\Interface\Http\Middlewares;

use Exception;
use SalesAppApi\Shared\Auth\Auth;
use SalesAppApi\Shared\Auth\JwtService;
use SalesAppApi\Shared\Request;
use SalesAppApi\Shared\Response;

class AuthMiddleware
{
    public function execute(Request $request, array $vars, array $params = []): void
    {
        $authHeader = $request->header('authorization');
        
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new Exception("Usuário não autorizado", 401);
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $decoded = JwtService::validateToken($token);

            if (!isset($decoded['id'])) {
                throw new Exception("Usuário não autorizado", 401);
            }

            Auth::setUser([
                'id'    => $decoded['id'],
                'name'  => $decoded['name'] ?? null,
            ]);
        } catch (Exception $e) {
            throw new Exception("Usuário não autorizado", 401);
        }
    }
}
