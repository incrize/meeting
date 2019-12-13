<?php
declare(strict_types=1);

namespace Meeting\Infrastructure\Auth;

use Spiral\Security\ActorInterface;

class Actor implements ActorInterface
{
    protected $roles;

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}