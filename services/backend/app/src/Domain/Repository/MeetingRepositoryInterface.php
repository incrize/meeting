<?php


namespace Meeting\Domain\Repository;


use Meeting\Domain\Meeting;
use Meeting\Domain\Room;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;
use DateTime;

interface MeetingRepositoryInterface
{
    public function isMeetingExists(Room $room, DateTime $startsAt, DateTime $endsAt): bool;

    public function isParticipantBusy(User $user, DateTime $startsAt, DateTime $endsAt): bool;

    public function save(Meeting $meeting): bool;

    public function find(MeetingUid $meeting_uid) : ?Meeting;
}