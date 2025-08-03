<?php
namespace SalesAppApi\Interface\Http\Controllers;

use Exception;
use SalesAppApi\Shared\Request;
use SalesAppApi\Shared\Response;
use SalesAppApi\UseCases\User\CreateUser;
use SalesAppApi\UseCases\User\DeleteUser;
use SalesAppApi\UseCases\User\GetAllUsers;
use SalesAppApi\UseCases\User\GetUserById;
use SalesAppApi\UseCases\User\UpdateUser;

class UserController
{
    public function __construct(
        private GetAllUsers $getAllUsers,
        private GetUserById $getUserById,
        private CreateUser $createUser,
        private UpdateUser $updateUser,
        private DeleteUser $deleteUser
    )
    {
    }

    public function getAllUsers(Request $request): mixed
    {
        $validatedData = $request->validate([
            'search' => 'nullable',
            'page' => 'required|integer',
            'page_count' => 'required|integer'
        ]);
        
        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->getAllUsers->execute([
                'search' => !empty($validatedData['search']) ? $validatedData['search'] : '',
                'page' => $validatedData['page'],
                'page_count' => $validatedData['page_count']
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

    public function getUserById(Request $request, $params): mixed
    {
        try {
            $response = $this->getUserById->execute($params['id']);
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

    public function createUser(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'is_active' => 'required|integer'
        ]);

        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->createUser->execute([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'is_active' => $validatedData['is_active']
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

    public function updateUser(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'is_active' => 'required|integer'
        ]);

        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->updateUser->execute([
                'id' => $validatedData['id'],
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'is_active' => $validatedData['is_active']
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

    public function deleteUser(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);
        
        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->deleteUser->execute($validatedData['id']);
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