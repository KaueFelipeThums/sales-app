<?php
namespace SalesAppApi\Interface\Http\Controllers;

use Exception;
use SalesAppApi\Shared\Request;
use SalesAppApi\Shared\Response;
use SalesAppApi\UseCases\Sale\CreateSale;
use SalesAppApi\UseCases\Sale\DeleteSale;
use SalesAppApi\UseCases\Sale\GetAllSales;
use SalesAppApi\UseCases\Sale\GetSaleById;
use SalesAppApi\UseCases\Sale\UpdateSale;

class SaleController
{
    public function __construct(
        private GetAllSales $getAllSales,
        private GetSaleById $getSaleById,
        private CreateSale $createSale,
        private UpdateSale $updateSale,
        private DeleteSale $deleteSale
    )
    {
    }

    public function getAllSales(Request $request): mixed
    {
        $validatedData = $request->validate([
            'search' => 'nullable',
            'page' => 'required|integer',
            'page_count' => 'required|integer',
            'customer_id' => 'nullable|integer',
            'product_id' => 'nullable|integer',
            'status' => 'nullable|string'
        ]);
        
        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->getAllSales->execute([
                'search' => !empty($validatedData['search']) ? $validatedData['search'] : '',
                'page' => $validatedData['page'],
                'page_count' => $validatedData['page_count'],
                'customer_id' => !empty($validatedData['customer_id']) ? $validatedData['customer_id'] : null,
                'product_id' => !empty($validatedData['product_id']) ? $validatedData['product_id'] : null,
                'status' => !empty($validatedData['status']) ? $validatedData['status'] : null
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

    public function getSaleById(Request $request, $params): mixed
    {
        try {
            $response = $this->getSaleById->execute($params['id']);
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

    public function createSale(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'payment_method_id' => 'required|integer',
            'product_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'quantity' => 'required|integer',
            'total_value' => 'required|numeric:14,2',
            'base_value' => 'required|numeric:14,2'
        ]);

        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->createSale->execute([
                'payment_method_id' => $validatedData['payment_method_id'],
                'product_id' => $validatedData['product_id'],
                'customer_id' => $validatedData['customer_id'],
                'quantity' => $validatedData['quantity'],
                'total_value' => $validatedData['total_value'],
                'base_value' => $validatedData['base_value']
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

    public function updateSale(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'payment_method_id' => 'required|integer',
            'product_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'quantity' => 'required|integer',
            'total_value' => 'required|numeric:14,2',
            'base_value' => 'required|numeric:14,2'
        ]);

        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->updateSale->execute([
                'id' => $validatedData['id'],
                'payment_method_id' => $validatedData['payment_method_id'],
                'product_id' => $validatedData['product_id'],
                'customer_id' => $validatedData['customer_id'],
                'quantity' => $validatedData['quantity'],
                'total_value' => $validatedData['total_value'],
                'base_value' => $validatedData['base_value']
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

    public function deleteSale(Request $request): mixed
    {   
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);
        
        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        try {
            $response = $this->deleteSale->execute($validatedData['id']);
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