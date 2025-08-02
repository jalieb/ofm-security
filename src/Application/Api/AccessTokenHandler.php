<?php

declare(strict_types=1);

namespace Ofm\Security\Application\Api;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Ofm\Security\Domain\Model\User;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(private string $authServiceJwksUrl)
    {
    }

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        try {
            $jwksFile = file_get_contents($this->authServiceJwksUrl);

            if ($jwksFile === false) {
                throw new AuthenticationException('Could not get JWKS file from auth server.');
            }

            $payload = JWT::decode($accessToken, JWK::parseKeySet(json_decode($jwksFile, true)));

            // TODO: Validate token (expiration time etc.)

            $userId = $payload->userid;
            $clubId = $payload->clubid;
            $roles = $payload->roles;

            return new UserBadge($userId, function () use ($userId, $clubId, $roles) {
                return new User($userId, $clubId, $roles);
            });
        } catch (\Throwable $exception) {
            throw new AuthenticationException($exception->getMessage());
        }
    }
}
