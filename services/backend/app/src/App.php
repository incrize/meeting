<?php

declare(strict_types=1);

namespace Meeting;

use Meeting\Infrastructure\Bootloader;
use Spiral\Bootloader as Framework;
use Spiral\DotEnv\Bootloader as DotEnv;
use Spiral\Monolog\Bootloader as Monolog;
use Spiral\Nyholm\Bootloader as Nyholm;
use Spiral\Framework\Kernel;
use Spiral\Bootloader\Security;

class App extends Kernel
{
    /*
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected const LOAD = [
        // Environment configuration
        DotEnv\DotenvBootloader::class,
        Monolog\MonologBootloader::class,

        // Core Services
        Framework\SnapshotsBootloader::class,
        Framework\Security\EncrypterBootloader::class,

        // Security and validation
        Framework\Security\EncrypterBootloader::class,
        Framework\Security\ValidationBootloader::class,
        Framework\Security\FiltersBootloader::class,
        Framework\Security\GuardBootloader::class,
        Security\GuardBootloader::class,
        Framework\Auth\HttpAuthBootloader::class,
        Framework\Auth\TokenStorage\CycleTokensBootloader::class,
        Framework\Auth\SecurityActorBootloader::class,

        // HTTP extensions
        Nyholm\NyholmBootloader::class,
        Framework\Http\RouterBootloader::class,
        Framework\Http\ErrorHandlerBootloader::class,

        // Databases
        Framework\Database\DatabaseBootloader::class,
        Framework\Database\MigrationsBootloader::class,

        // ORM
        Framework\Cycle\CycleBootloader::class,
        Framework\Cycle\ProxiesBootloader::class,
        Framework\Cycle\AnnotatedBootloader::class,

        // Dispatchers
        Framework\Jobs\JobsBootloader::class,

        // Framework commands
        Framework\CommandBootloader::class,

        // Debugging
        Framework\DebugBootloader::class,
        Framework\Debug\LogCollectorBootloader::class,
        Framework\Debug\HttpCollectorBootloader::class
    ];

    /**
     * Application specific services and extensions.
     */
    protected const APP = [
        Bootloader\RoutesBootloader::class,
        Bootloader\LoggingBootloader::class
    ];
}
