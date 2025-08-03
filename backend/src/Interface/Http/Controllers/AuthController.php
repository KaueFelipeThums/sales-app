<?php
namespace SalesAppApi\Interface\Http\Controllers;

use Exception;
use SalesAppApi\Shared\Request;
use SalesAppApi\Shared\Response;
use SalesAppApi\UseCases\Auth\Login;
use SalesAppApi\UseCases\Auth\RefreshToken;
use SalesAppApi\UseCases\Auth\UpdateUserPassword;

class AuthController
{
    public function __construct(
        private Login $login,
        private RefreshToken $refresh,
        private UpdateUserPassword $updateUserPassword
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
            $errorCode = $e->getCode();
            $httpStatus = ($errorCode >= 100 && $errorCode <= 599) ? $errorCode : 500;

            return Response::json([
                'message' => $e->getMessage(),
                'code' => $httpStatus,
            ], $httpStatus);
        }
    }

    public function updateUserPassword(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'password' => 'required'
        ]);
        
        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->updateUserPassword->execute([
                'password' => $validatedData['password']
            ]);

            return Response::json($response, 200);
        } catch(Exception $e){
            $errorCode = $e->getCode();
            $httpStatus = ($errorCode >= 100 && $errorCode <= 599) ? $errorCode : 500;

            return Response::json([
                'message' => $e->getMessage(),
                'code' => $httpStatus,
            ], $httpStatus);
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
            $errorCode = $e->getCode();
            $httpStatus = ($errorCode >= 100 && $errorCode <= 599) ? $errorCode : 500;

            return Response::json([
                'message' => $e->getMessage(),
                'code' => $httpStatus,
            ], $httpStatus);
        }
    }
}