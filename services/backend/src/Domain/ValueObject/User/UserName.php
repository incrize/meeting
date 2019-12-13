<?php


namespace Meeting\Domain\ValueObject\User;


use Meeting\Domain\Exception\DomainException;

class UserName
{
    protected $name;

    public function __construct($name)
    {
        if (empty($name)) {
            throw new DomainException('Name must be specified.');
        }

        $this->name = $name;
    }
}