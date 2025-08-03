<?php

namespace SalesAppApi\UseCases\Auth;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GetAddressByCep
{
    private Client $http;

    public function __construct()
    { 
        $this->http = new Client([
            'base_uri' => $_ENV['VIACEP_BASE_URL'] . '/',
            'timeout'  => 5.0, 
        ]);
    }

    /**
     * Execute
     *
     * @param array $data ['cep' => string]
     * @return array|null
     */
    public function execute(array $data): ?array
    {
        if (strlen($data['cep']) !== 8) {
            return null;
        }

        try {
            $response = $this->http->get($data['cep'] . '/json/');
            $statusCode = $response->getStatusCode();

            if ($statusCode !== 200) {
                return null;
            }

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (!empty($responseData['erro'])) {
                return null;
            }

            return [
                'cep'         => $responseData['cep'] ?? null,
                'logradouro'  => $responseData['logradouro'] ?? null,
                'complemento' => $responseData['complemento'] ?? null,
                'unidade'     => $responseData['unidade'] ?? null,
                'bairro'      => $responseData['bairro'] ?? null,
                'localidade'  => $responseData['localidade'] ?? null,
                'uf'          => $responseData['uf'] ?? null,
                'estado'      => $responseData['estado'] ?? null,
                'regiao'      => $responseData['regiao'] ?? null,
                'ibge'        => $responseData['ibge'] ?? null,
                'gia'         => $responseData['gia'] ?? null,
                'ddd'         => $responseData['ddd'] ?? null,
                'siafi'       => $responseData['siafi'] ?? null,
            ];
        } catch (GuzzleException | Exception $e) {
            return null;
        }
    }
}
