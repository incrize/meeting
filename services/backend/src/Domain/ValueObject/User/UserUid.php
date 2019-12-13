<?php


namespace Meeting\Domain\ValueObject\User;


use Meeting\Domain\Exception\DomainException;

class UserUid
{
    protected $uid;

    public function __construct($uid)
    {
        if (empty($uid)) {
            throw new DomainException('ID must be specified.');
        }

        $this->uid = $uid;
    }

    public function toString()
    {
        return $this->uid;
    }

    public function isEqual(UserUid $uid)
    {
        return $this->toString() === $uid->toString();
    }
}