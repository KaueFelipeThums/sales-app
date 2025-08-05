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
            'customers_id' => 'nullable|integer'
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
                'customers_id' => !empty($validatedData['customers_id']) ? $validatedData['customers_id'] : null
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
            'payment_methods_id' => 'required|integer',
            'customers_id' => 'required|integer',
            'products' => 'required|array'
        ]);

        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        if(count($validatedData['products']) == 0){
            return Response::json(['errors' => 'Nenhum produto informado'], 422);
        }

        try {
            $response = $this->createSale->execute([
                'payment_methods_id' => $validatedData['payment_methods_id'],
                'customers_id' => $validatedData['customers_id'],
                'products' => $validatedData['products']
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
            'payment_methods_id' => 'required|integer',
            'customers_id' => 'required|integer',
            'products' => 'required|array'
        ]);

        $errors = $request->getErrors();
        if(count($errors) > 0){
            return Response::json(['errors' => $errors[0]], 422);
        }

        if(count($validatedData['products']) == 0){
            return Response::json(['errors' => 'Nenhum produto informado'], 422);
        }

        try {
            $response = $this->updateSale->execute([
                'id' => $validatedData['id'],
                'payment_methods_id' => $validatedData['payment_methods_id'],
                'customers_id' => $validatedData['customers_id'],
                'products' => $validatedData['products']
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