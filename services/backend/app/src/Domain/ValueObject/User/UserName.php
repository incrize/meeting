<?php


namespace Meeting\Domain\ValueObject\User;

use Meeting\Domain\Exception\User\UserNameInvalidException;

class UserName
{
    protected $name;

    public function __construct($name)
    {
        if (empty($name)) {
            throw new UserNameInvalidException('Name must be specified.');
        }

        $this->name = $name;
    }
}