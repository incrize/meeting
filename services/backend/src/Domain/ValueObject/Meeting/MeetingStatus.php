<?php


namespace Meeting\Domain\ValueObject\Meeting;


use Meeting\Domain\Exception\DomainException;

class MeetingStatus
{
    const STATUS_ACTIVE = 'active';

    const STATUS_CANCELED = 'canceled';

    protected $status;

    public function __construct($status)
    {
        if (!in_array($status, [self::STATUS_ACTIVE, self::STATUS_CANCELED], true)) {
            throw new DomainException('Status invalid');
        }

        $this->status = $status;
    }

    public static function createActiveStatus()
    {
        return new self(self::STATUS_ACTIVE);
    }

    public static function createCanceledStatus()
    {
        return new self(self::STATUS_CANCELED);
    }

    public function isEqual(MeetingStatus $meeting_status)
    {
        return $this->status === $meeting_status->toString();
    }

    public function toString()
    {
        return $this->status;
    }
}