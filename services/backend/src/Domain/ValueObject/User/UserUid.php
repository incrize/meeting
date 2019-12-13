<?php


namespace Meeting\Domain\ValueObject\User;

use Meeting\Domain\Exception\User\UserUidInvalidException;


class UserUid
{
    protected $uid;

    public function __construct($uid)
    {
        if (empty($uid)) {
            throw new UserUidInvalidException('ID must be specified.');
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