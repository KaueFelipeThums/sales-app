<?php
namespace SalesAppApi\Interface\Http\Controllers;

use Exception;
use SalesAppApi\Shared\Request;
use SalesAppApi\Shared\Response;
use SalesAppApi\UseCases\PaymentMethod\CreatePaymentMethod;
use SalesAppApi\UseCases\PaymentMethod\DeletePaymentMethod;
use SalesAppApi\UseCases\PaymentMethod\GetAllPaymentMethods;
use SalesAppApi\UseCases\PaymentMethod\GetPaymentMethodById;
use SalesAppApi\UseCases\PaymentMethod\UpdatePaymentMethod;

class PaymentMethodController
{
    public function __construct(
        private GetAllPaymentMethods $getAllPaymentMethods,
        private GetPaymentMethodById $getPaymentMethodById,
        private CreatePaymentMethod $createPaymentMethod,
        private UpdatePaymentMethod $updatePaymentMethod,
        private DeletePaymentMethod $deletePaymentMethod
    )
    {
    }

    public function getAllPaymentMethods(Request $request): mixed
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
            $response = $this->getAllPaymentMethods->execute([
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

    public function getAllActiverPaymentMethods(Request $request): mixed
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
            $response = $this->getAllPaymentMethods->execute([
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

    public function getPaymentMethodById(Request $request, $params): mixed
    {
        try {
            $response = $this->getPaymentMethodById->execute($params['id']);
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

    public function createPaymentMethod(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'name' => 'required',
            'installments' => 'required|integer',
            'is_active' => 'required|integer'
        ]);

        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->createPaymentMethod->execute([
                'name' => $validatedData['name'],
                'installments' => $validatedData['installments'],
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

    public function updatePaymentMethod(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'name' => 'required',
            'installments' => 'required|integer',
            'is_active' => 'required|integer'
        ]);

        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->updatePaymentMethod->execute([
                'id' => $validatedData['id'],
                'name' => $validatedData['name'],
                'installments' => $validatedData['installments'],
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

    public function deletePaymentMethod(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);
        
        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->deletePaymentMethod->execute($validatedData['id']);
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