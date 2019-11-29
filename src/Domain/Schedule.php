<?php


namespace Meeting\Domain;


use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\Exception\MeetingNotExistsException;
use Meeting\Domain\Repository\MeetingRepositoryInterface;
use Meeting\Domain\ValueObject\Meeting\MeetingStatus;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;

class Schedule
{
    /** @var \Meeting\Domain\Repository\MeetingRepositoryInterface */
    protected $meetingRepository;

    public function __construct(MeetingRepositoryInterface $meetingRepository)
    {
        $this->meetingRepository = $meetingRepository;
    }

    /**
     * @param \Meeting\Domain\Meeting   $meeting
     *
     * @throws \Meeting\Domain\Exception\DomainException
     */
    public function addMeeting(Meeting $meeting): void
    {
        if (!$this->isRoomAvailable($meeting->getRoom(), $meeting->getStartsAt(), $meeting->getEndsAt())) {
            throw new DomainException('Room is not available');
        }

        if (!$this->areParticipantsAvailable($meeting->getParticipants(), $meeting->getStartsAt(), $meeting->getEndsAt())) {
            throw new DomainException('Some participant is not available');
        }

        $this->meetingRepository->save($meeting);
    }

    /**
     * @param \Meeting\Domain\Room $room
     * @param \DateTime            $startsAt
     * @param \DateTime            $endsAt
     *
     * @return bool
     */
    public function isRoomAvailable(Room $room, \DateTime $startsAt, \DateTime $endsAt): bool
    {
        return !$this->meetingRepository->isMeetingExists($room, $startsAt, $endsAt);
    }

    /**
     * @param array     $participants
     * @param \DateTime $startsAt
     * @param \DateTime $endsAt
     *
     * @return bool
     */
    public function areParticipantsAvailable(array $participants, \DateTime $startsAt, \DateTime $endsAt): bool
    {
        foreach ($participants as $user) {
            if ($this->meetingRepository->isParticipantBusy($user, $startsAt, $endsAt)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingUid $meetingUid
     *
     * @return \Meeting\Domain\Meeting
     * @throws \Meeting\Domain\Exception\DomainException
     */
    public function findMeeting(MeetingUid $meetingUid): Meeting
    {
        $meeting = $this->meetingRepository->find($meetingUid);

        // TODO: that exception needed in getMeeting, no need here
        if (!$meeting) {
            throw new MeetingNotExistsException();
        }

        return $meeting;

    }

    /**
     * @param \Meeting\Domain\Meeting $meeting
     *
     * @throws \Meeting\Domain\Exception\DomainException
     */
    public function cancelMeeting(Meeting $meeting): void
    {
        if ($meeting->getStatus()->isEqual(MeetingStatus::createCanceledStatus())) {
            throw new DomainException('Meeting already canceled');
        }

        $meeting->setStatus(MeetingStatus::createCanceledStatus());

        $this->meetingRepository->save($meeting);
    }

    /**
     * @param \Meeting\Domain\Meeting $meeting
     * @param \Meeting\Domain\User[]  $participants
     *
     * @throws \Meeting\Domain\Exception\DomainException
     */
    public function updateMeetingParticipants(Meeting $meeting, array $participants): void
    {
        if (empty($participants)) {
            throw new DomainException('Meeting must have participants');
        }

        $now = new \DateTime();
        if ($meeting->getEndsAt() < $now) {
            throw new DomainException('Meeting already over');
        }

        // TODO: add validate for state of participants (busy, fired, not User)

        // TODO: potential change to setParticipants
        //some participants maybe already added to this meeting - no need to add them again
        $old_participants = $meeting->getParticipants();
        $new_participants = array_diff($participants, $old_participants);

        $meeting->addParticipants($new_participants);

        $this->meetingRepository->save($meeting);
    }
}
