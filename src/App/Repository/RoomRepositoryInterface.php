<?php

namespace Meeting\Room;

use Meeting\App\ValueObject\Room\RoomSearchSpecification;
use Meeting\Domain\Room;
use Meeting\Domain\ValueObject\Room\RoomStatus;
use Meeting\Domain\ValueObject\Room\RoomUid;

interface RoomRepositoryInterface
{
    /**
     * @param \Meeting\Domain\Room $room
     *
     * @return \Meeting\Domain\Room
     * @throws \Meeting\App\Exception\DuplicateRoomException
     */
    public function create(Room $room): Room;

    /**
     * @param \Meeting\Domain\Room $room
     *
     * @return \Meeting\Domain\Room
     * @throws \\Meeting\App\Exception\RoomNotFoundException
     */
    public function delete(Room $room): Room;

    /**
     * @param \Meeting\Domain\Room $room
     *
     * @return \Meeting\Domain\Room
     * @throws \Meeting\App\Exception\RoomNotFoundException
     */
    public function update(Room $room): Room;

    /**
     * @param \Meeting\App\ValueObject\Room\RoomSearchSpecification $spec
     *
     * @return \Meeting\Domain\Room[]
     */
    public function find(RoomSearchSpecification $spec): array;

    /**
     * @param \Meeting\Domain\ValueObject\Room\RoomUid $id
     *
     * @return \Meeting\Domain\Room|null
     */
    public function findById(RoomUid $id);

    /**
     * @param \Meeting\Domain\ValueObject\Room\RoomStatus $status
     *
     * @return \Meeting\Domain\Room[]
     */
    public function findByStatus(RoomStatus $status): array;

    public function count(RoomSearchSpecification $spec): int;
}
