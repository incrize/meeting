<?php
declare(strict_types=1);

namespace Meeting\Infrastructure\Auth;


use Spiral\Filters\Filter;

class AuthRequest extends Filter
{
    public const SCHEMA = [
        'username' => 'data:username',
        'password' => 'data:password'
    ];

    public const VALIDATES = [
        'username' => ['notEmpty'],
        'password' => ['notEmpty']
    ];
}