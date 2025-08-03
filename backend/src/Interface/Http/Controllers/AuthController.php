<?php
namespace SalesAppApi\Interface\Http\Controllers;

use Exception;
use SalesAppApi\Shared\Request;
use SalesAppApi\Shared\Response;
use SalesAppApi\UseCases\Auth\Login;
use SalesAppApi\UseCases\Auth\RefreshToken;

class AuthController
{
    public function __construct(
        private Login $login,
        private RefreshToken $refresh
    )
    {
    }

    public function login(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        
        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->login->execute([
                'email' => $validatedData['email'],
                'password' => $validatedData['password']
            ]);

            return Response::json($response, 200);
        } catch(Exception $e){
            return Response::json(['errors' => $e->getMessage()], 422);
        }
    }

    public function refresh(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'refresh_token' => 'required',
        ]);
        
        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->refresh->execute([
                'refresh_token' => $validatedData['refresh_token'],
            ]);

            return Response::json($response, 200);
        } catch(Exception $e){
            return Response::json(['errors' => $e->getMessage()], 422);
        }
    }
}