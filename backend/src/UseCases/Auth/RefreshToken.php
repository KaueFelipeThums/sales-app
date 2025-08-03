<?php

namespace SalesAppApi\UseCases\Auth;

use Exception;
use SalesAppApi\Domain\RefreshToken as RefreshTokenModel;
use SalesAppApi\Domain\RefreshTokenRepositoryInterface;
use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Shared\Auth\Auth;
use SalesAppApi\Shared\Auth\JwtService;

class RefreshToken{

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RefreshTokenRepositoryInterface $refreshTokenRepository
    ){}

    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'refresh_token' => string,
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $refreshToken = $this->refreshTokenRepository->getRefreshTokenByToken($data['refresh_token']);

        if(empty($refreshToken)) {
            throw new Exception("Refresh token inválido", 422);
        }

        if(strtotime($refreshToken->getExpiresAt()->getDateTime()) < time()) {
            throw new Exception("Refresh token expirado", 422);
        }

        $user = $this->userRepository->getUserById(Auth::id());

        $accessToken = JwtService::generateToken([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail()
        ]);
        $newRefreshToken = bin2hex(random_bytes(64));
        
        $this->refreshTokenRepository->update(new RefreshTokenModel(
            $refreshToken->getId(),
            $refreshToken->getUsersId(),
            $newRefreshToken,
            new DateTime(date('Y-m-d H:i:s', strtotime('+7 days'))),
            new DateTime(date('Y-m-d H:i:s'))
        ));

        return [
            'access_token' => $accessToken,
            'refresh_token' => $newRefreshToken,
        ];
    }
}
