<?php


namespace Meeting\Domain\ValueObject\Room;


use Meeting\Domain\Exception\DomainException;

class RoomName
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