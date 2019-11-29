<?php


namespace Meeting\Infrastructure\Persistence;


use Meeting\Domain\Meeting;
use Meeting\Domain\Repository\MeetingRepositoryInterface;
use Meeting\Domain\Room;
use Meeting\Domain\User;

class MeetingRepository implements MeetingRepositoryInterface
{
    /** @var \Meeting\Domain\Meeting[] */
    protected $storage = [];

    public function isMeetingExists(Room $room, \DateTime $startsAt, \DateTime $endsAt): bool
    {
        foreach ($this->storage as $meeting) {
            if (!$meeting->getRoom()->getUid()->isEqual($room->getUid())) {
                continue;
            }

            if ($endsAt->getTimestamp() > $meeting->getStartsAt()->getTimestamp()
                && $endsAt->getTimestamp() <= $meeting->getEndsAt()->getTimestamp()
            ) {
                return true;
            }
        }

        return false;
    }

    public function isParticipantBusy(User $user, \DateTime $startsAt, \DateTime $endsAt): bool
    {
        foreach ($this->storage as $meeting) {
            foreach ($meeting->getParticipants() as $participant) {
                if (!$participant->getUid()->isEqual($user->getUid())) {
                    continue;
                }

                if ($endsAt->getTimestamp() > $meeting->getStartsAt()->getTimestamp()
                    && $endsAt->getTimestamp() <= $meeting->getEndsAt()->getTimestamp()
                ) {
                    return true;
                } else {
                    break;
                }
            }
        }

        return false;
    }

    public function save(Meeting $meeting): bool
    {
        $this->storage[$meeting->getUid()->toString()] = $meeting;

        return true;
    }
}