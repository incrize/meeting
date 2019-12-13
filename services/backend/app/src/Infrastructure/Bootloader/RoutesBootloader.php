<?php

declare(strict_types=1);

namespace Meeting\Infrastructure\Bootloader;

use Meeting\Infrastructure\Auth\AuthMiddleware;
use Meeting\Infrastructure\Controller\AuthController;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Router\Route;
use Spiral\Router\RouterInterface;
use Spiral\Router\Target\Action;
use Spiral\Router\Target\Controller;
use Spiral\Router\Target\Namespaced;

class RoutesBootloader extends Bootloader
{
    /**
     * @param RouterInterface $router
     */
    public function boot(RouterInterface $router)
    {
        $route = new Route(
            '/auth',
            new Action(AuthController::class, 'index')
        );

        $router->setRoute('auth', $route);

        $route = new Route(
            '/[<controller>[/<action>]]',
            new Namespaced('Meeting\\Infrastructure\\Controller')
        );
        //$route = $route->withMiddleware(AuthMiddleware::class);

        $router->setDefault($route);
    }
}