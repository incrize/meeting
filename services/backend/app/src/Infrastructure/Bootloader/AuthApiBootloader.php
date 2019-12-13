<?php


namespace Meeting\Infrastructure\Bootloader;

use Meeting\Infrastructure\Auth\AuthActorProvider;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\Auth\AuthBootloader;

class AuthApiBootloader extends Bootloader
{
    public function boot(AuthBootloader $auth)
    {
        $auth->addActorProvider(AuthActorProvider::class);
    }
}