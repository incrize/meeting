<?php


namespace Meeting\Domain\ValueObject\Room;

use Meeting\Domain\Exception\Room\RoomUidInvalidException;

class RoomUid
{
    protected $uid;

    public function __construct($uid)
    {
        if (empty($uid)) {
            throw new RoomUidInvalidException('ID must be specified.');
        }

        $this->uid = $uid;
    }

    public function toString()
    {
        return $this->uid;
    }

    public function isEqual(RoomUid $uid)
    {
        return $this->toString() === $uid->toString();
    }
}