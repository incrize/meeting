<?php


namespace Meeting\Infrastructure\Persistence;


use DateTime;
use Meeting\Domain\Meeting;
use Meeting\Domain\Repository\MeetingRepositoryInterface;
use Meeting\Domain\Room;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;

class MeetingRepository implements MeetingRepositoryInterface
{
    /** @var \Meeting\Domain\Meeting[] */
    protected $storage = [];

    /**
     * @param \Meeting\Domain\Room $room
     * @param \DateTime            $startsAt
     * @param \DateTime            $endsAt
     *
     * @return bool
     */
    public function isMeetingExists(Room $room, DateTime $startsAt, DateTime $endsAt): bool
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

    /**
     * @param \Meeting\Domain\User $user
     * @param \DateTime            $startsAt
     * @param \DateTime            $endsAt
     *
     * @return bool
     */
    public function isParticipantBusy(User $user, DateTime $startsAt, DateTime $endsAt): bool
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

    /**
     * @param \Meeting\Domain\Meeting $meeting
     *
     * @return bool
     */
    public function save(Meeting $meeting): bool
    {
        $this->storage[$meeting->getUid()->toString()] = $meeting;

        return true;
    }

    /**
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingUid $meeting_uid
     *
     * @return \Meeting\Domain\Meeting|null
     */
    public function find(MeetingUid $meeting_uid) : ?Meeting
    {
        if (isset($this->storage[$meeting_uid->toString()])) {
            return $this->storage[$meeting_uid->toString()];
        } else {
            return null;
        }
    }
}