<?php


namespace Meeting\Domain\Repository;


use Meeting\Domain\Meeting;
use Meeting\Domain\Room;
use Meeting\Domain\User;

interface MeetingRepositoryInterface
{
    public function isMeetingExists(Room $room, \DateTime $startsAt, \DateTime $endsAt): bool;

    public function isParticipantBusy(User $user, \DateTime $startsAt, \DateTime $endsAt): bool;

    public function save(Meeting $meeting): bool;
}