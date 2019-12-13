<?php


namespace Meeting\Domain\ValueObject\User;

use Meeting\Domain\Exception\User\UserStatusInvalidException;

class UserStatus
{
    const STATUS_ACTIVE = 'active';

    const STATUS_FIRED = 'fired';

    protected $status;

    public function __construct($status)
    {
        if (!in_array($status, [self::STATUS_ACTIVE, self::STATUS_FIRED], true)) {
            throw new UserStatusInvalidException('Status invalid');
        }

        $this->status = $status;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isFired()
    {
        return $this->status === self::STATUS_FIRED;
    }

    public static function createActiveStatus()
    {
        return new self(self::STATUS_ACTIVE);
    }

    public static function createFiredStatus()
    {
        return new self(self::STATUS_FIRED);
    }

    public function isEqual(UserStatus $status)
    {
        return $this->status === $status->toString();
    }

    public function toString()
    {
        return $this->status;
    }
}
