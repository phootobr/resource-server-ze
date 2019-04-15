<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Repositories\AccessTokenRepository;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CheckClientCredentials implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $accessTokenRepository = new AccessTokenRepository();
        $publicKeyPath = realpath(__DIR__ . '/../../../../data/oauth-public.key');

        $server = new \League\OAuth2\Server\ResourceServer(
            $accessTokenRepository,
            $publicKeyPath
        );

        try {
            $request = $server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $e) {
            throw new \Exception($e->getMessage(), $e->getHttpStatusCode());
        }

        return $handler->handle($request);
    }
}
