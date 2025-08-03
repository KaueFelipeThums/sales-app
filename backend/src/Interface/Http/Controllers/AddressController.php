<?php
namespace SalesAppApi\Interface\Http\Controllers;

use Exception;
use SalesAppApi\Shared\Request;
use SalesAppApi\Shared\Response;
use SalesAppApi\UseCases\Auth\GetAddressByCep;

class AddressController
{
    public function __construct(
        private GetAddressByCep $getAddressByCep,
    )
    {
    }

    public function getAddressByCep(Request $request, $params): mixed
    {   
        $cep = preg_replace('/\D/', '', !empty($params['cep']) ? $params['cep'] : "");
        
        try {
            $response = $this->getAddressByCep->execute([
                'cep' => $cep,
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