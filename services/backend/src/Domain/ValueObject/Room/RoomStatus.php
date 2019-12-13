<?php

namespace Meeting\Domain\ValueObject\Room;

use Meeting\Domain\Exception\Room\RoomStatusInvalidException;

class RoomStatus
{
    const STATUS_OPEN = 'open';

    const STATUS_CLOSED = 'closed';

    protected $status;

    public function __construct($status)
    {
        if (!in_array($status, [self::STATUS_OPEN, self::STATUS_CLOSED], true)) {
            throw new RoomStatusInvalidException('Status invalid');
        }

        $this->status = $status;
    }

    public function isOpen()
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public static function createOpenStatus()
    {
        return new self(self::STATUS_OPEN);
    }

    public static function createClosedStatus()
    {
        return new self(self::STATUS_CLOSED);
    }

    public function isEqual(RoomStatus $status)
    {
        return $this->status === $status->toString();
    }

    public function toString()
    {
        return $this->status;
    }
}
