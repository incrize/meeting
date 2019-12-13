<?php


namespace Meeting\Domain\ValueObject\Room;


use Meeting\Domain\Exception\Room\RoomNameInvalidException;

class RoomName
{
    protected $name;

    public function __construct($name)
    {
        if (empty($name)) {
            throw new RoomNameInvalidException('Name must be specified.');
        }

        $this->name = $name;
    }
}