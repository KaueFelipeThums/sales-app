<?php

namespace SalesAppApi\UseCases\Auth;

use Exception;
use SalesAppApi\Domain\RefreshToken;
use SalesAppApi\Domain\RefreshTokenRepositoryInterface;
use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Shared\Auth\JwtService;

class Login{

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RefreshTokenRepositoryInterface $refreshTokenRepository
    ){}

    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'email' => string,
     *      'password' => string
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $user = $this->userRepository->getActiveUserByEmail($data['email']);
        
        if(empty($user)) {
            throw new Exception("E-mail ou senha inválidos", 422);
        }

        if(!password_verify($data['password'], $user->getPassword())) {
            throw new Exception("E-mail ou senha inválidos", 422);
        }
        
        $accessToken = JwtService::generateToken([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail()
        ]);

        $refreshToken = bin2hex(random_bytes(64));
        $this->refreshTokenRepository->create(new RefreshToken(
            null,
            $user->getId(),
            $refreshToken,
            new DateTime(date('Y-m-d H:i:s', strtotime('+7 days'))),
            new DateTime(date('Y-m-d H:i:s'))
        ));

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()
            ]
        ];
    }
}
