<?php

declare(strict_types=1);

use Spiral\Database\Driver;

$driver = null;

switch (env('DATABASE_DRIVER')) {
    case 'pgsql':
        $driver = Driver\Postgres\PostgresDriver::class;
        break;
    case 'mysql':
    default:
        $driver = Driver\MySQL\MySQLDriver::class;
        break;
}

return [
    'default'   => 'default',
    'databases' => [
        'default' => ['driver' => 'db'],
    ],
    'drivers'   => [
        'db' => [
            'driver'     => $driver,
            'connection' => env('DATABASE_DSN'),
            'username'   => env('DATABASE_USER'),
            'password'   => env('DATABASE_PASSWORD'),
        ],
    ]
];
