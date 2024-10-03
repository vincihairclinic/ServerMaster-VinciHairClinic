<?php

namespace App\Http\Grant;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

class EmailGrant extends AbstractGrant
{
    public function __construct(UserRepositoryInterface $userRepository, RefreshTokenRepositoryInterface $refreshTokenRepository)
    {
        $this->setUserRepository($userRepository);
        $this->setRefreshTokenRepository($refreshTokenRepository);
        $this->refreshTokenTTL = new \DateInterval('P1M');
    }

    public function respondToAccessTokenRequest(ServerRequestInterface $request, ResponseTypeInterface $responseType, \DateInterval $accessTokenTTL)
    {
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request));
        $user = $this->validateUser($request);

        $scopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->id);

        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->id, $scopes);
        $refreshToken = $this->issueRefreshToken($accessToken);

        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);
        return $responseType;
    }

    protected function validateUser(ServerRequestInterface $request)
    {
        $email = $this->getRequestParameter('email', $request);
        $password = $this->getRequestParameter('password', $request);

        if(empty($email)) {
            throw OAuthServerException::invalidRequest('email');
        }else if(empty($password)) {
            throw OAuthServerException::invalidRequest('password');
        }

        $user = User::where('email', $email)->first();
        if (!isset($user->id) || (!Hash::check($password, $user->password) && $password != $user->password)) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));
            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }

    public function getIdentifier()
    {
        return 'email';
    }
}