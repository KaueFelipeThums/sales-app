<?php
namespace SalesAppApi\Interface\Http\Controllers;

use SalesAppApi\Shared\Response;

class UserController
{
    public function getUser(): mixed
    {
        return Response::json(['message' => 'Usuário logado'], 200);
    }
}