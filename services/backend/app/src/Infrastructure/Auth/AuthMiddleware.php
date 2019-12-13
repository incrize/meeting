<?php
declare(strict_types=1);

namespace Meeting\Infrastructure\Auth;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\Auth\Middleware\Firewall\AbstractFirewall;
use Spiral\Http\Exception\ClientException\UnauthorizedException;

class AuthMiddleware extends AbstractFirewall
{
    protected function denyAccess(Request $request, RequestHandlerInterface $handler): Response
    {
        throw new UnauthorizedException();
    }
}