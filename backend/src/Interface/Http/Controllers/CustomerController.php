<?php
namespace SalesAppApi\Interface\Http\Controllers;

use Exception;
use SalesAppApi\Shared\Request;
use SalesAppApi\Shared\Response;
use SalesAppApi\UseCases\Customer\CreateCustomer;
use SalesAppApi\UseCases\Customer\DeleteCustomer;
use SalesAppApi\UseCases\Customer\GetAllCustomers;
use SalesAppApi\UseCases\Customer\GetCustomerById;
use SalesAppApi\UseCases\Customer\UpdateCustomer;

class CustomerController
{
    public function __construct(
        private GetAllCustomers $getAllCustomers,
        private GetCustomerById $getCustomerById,
        private CreateCustomer $createCustomer,
        private UpdateCustomer $updateCustomer,
        private DeleteCustomer $deleteCustomer
    )
    {
    }

    public function getAllCustomers(Request $request): mixed
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
            $response = $this->getAllCustomers->execute([
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

    public function getAllActiverCustomers(Request $request): mixed
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
            $response = $this->getAllCustomers->execute([
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

    public function getCustomerById(Request $request, $params): mixed
    {
        try {
            $response = $this->getCustomerById->execute($params['id']);
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

    public function createCustomer(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'name' => 'required',
            'cpf' => 'required',
            'email' => 'required',
            'zip_code' => 'nullable',
            'street' => 'nullable',
            'number' => 'nullable',
            'complement' => 'nullable',
            'neighborhood' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'is_active' => 'required|integer'
        ]);

        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->createCustomer->execute([
                'name' => $validatedData['name'],
                'cpf' => $validatedData['cpf'],
                'email' => !empty($validatedData['email']) ? $validatedData['email'] : null,
                'zip_code' => !empty($validatedData['zip_code']) ? $validatedData['zip_code'] : null,
                'street' => !empty($validatedData['street']) ? $validatedData['street'] : null,
                'number' => !empty($validatedData['number']) ? $validatedData['number'] : null,
                'complement' => !empty($validatedData['complement']) ? $validatedData['complement'] : null,
                'neighborhood' => !empty($validatedData['neighborhood']) ? $validatedData['neighborhood'] : null,
                'city' => !empty($validatedData['city']) ? $validatedData['city'] : null,
                'state' => !empty($validatedData['state']) ? $validatedData['state'] : null,
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

    public function updateCustomer(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'name' => 'required',
            'cpf' => 'required',
            'email' => 'required',
            'zip_code' => 'nullable',
            'street' => 'nullable',
            'number' => 'nullable',
            'complement' => 'nullable',
            'neighborhood' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'is_active' => 'required|integer'
        ]);

        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->updateCustomer->execute([
                'id' => $validatedData['id'],
                'name' => $validatedData['name'],
                'cpf' => $validatedData['cpf'],
                'email' => !empty($validatedData['email']) ? $validatedData['email'] : null,
                'zip_code' => !empty($validatedData['zip_code']) ? $validatedData['zip_code'] : null,
                'street' => !empty($validatedData['street']) ? $validatedData['street'] : null,
                'number' => !empty($validatedData['number']) ? $validatedData['number'] : null,
                'complement' => !empty($validatedData['complement']) ? $validatedData['complement'] : null,
                'neighborhood' => !empty($validatedData['neighborhood']) ? $validatedData['neighborhood'] : null,
                'city' => !empty($validatedData['city']) ? $validatedData['city'] : null,
                'state' => !empty($validatedData['state']) ? $validatedData['state'] : null,
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

    public function deleteCustomer(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);
        
        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->deleteCustomer->execute($validatedData['id']);
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