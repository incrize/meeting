<?php

namespace Meeting\Infrastructure\Persistence;

use Meeting\App\Exception\DuplicateRoomException;
use Meeting\App\Exception\RoomNotFoundException;
use Meeting\App\ValueObject\Room\RoomSearchSpecification;
use Meeting\Domain\Room;
use Meeting\Domain\ValueObject\Room\RoomStatus;
use Meeting\Domain\ValueObject\Room\RoomUid;
use Meeting\Room\RoomRepositoryInterface;

class RoomRepository implements RoomRepositoryInterface
{
    /**
     * @var \Meeting\Domain\Room[]
     */
    protected $rooms;

    /**
     * DummyRoomRepository constructor.
     *
     * @param \Meeting\Domain\Room[] $rooms
     */
    public function __construct(array $rooms)
    {
        $this->rooms = $rooms;
    }

    /** @inheritDoc */
    public function create(Room $room): Room
    {
        if ($this->findById($room->getUid())) {
            throw new DuplicateRoomException();
        }
        $this->rooms[] = $room;

        return $room;
    }

    /** @inheritDoc */
    public function delete(Room $room): Room
    {
        if (!$this->findById($room->getUid())) {
            throw new RoomNotFoundException();
        }

        $this->rooms = array_filter($this->rooms, function (Room $repoRoom) use ($room) {
            return !$repoRoom->getUid()->isEqual($room->getUid());
        });

        return $room;
    }

    /** @inheritDoc */
    public function update(Room $room): Room
    {
        if (!$this->findById($room->getUid())) {
            throw new RoomNotFoundException();
        }

        foreach ($this->rooms as $index => $repoRoom) {
            if ($repoRoom->getId() == $room->getUid()) {
                $this->rooms[$index] = $room;
                break;
            }
        }

        return $this->findById($room->getUid());
    }

    /** @inheritDoc */
    public function find(RoomSearchSpecification $spec): array
    {
        return $this->rooms;
    }

    /** @inheritDoc */
    public function findById(RoomUid $id)
    {
        foreach ($this->rooms as $room) {
            if ($room->getUid()->isEqual($id)) {
                return $room;
            }
        }
        return null;
    }

    /** @inheritDoc */
    public function findByStatus(RoomStatus $status): array
    {
        $foundRooms = [];

        foreach ($this->rooms as $room) {
            if ($room->getStatus()->isEqual($status)) {
                $foundRooms[] = $room;
            }
        }

        return $foundRooms;
    }

    /** @inheritDoc */
    public function count(RoomSearchSpecification $spec): int
    {
        return count($this->rooms);
    }
}
