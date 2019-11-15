<?php


namespace Meeting\Domain;


use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\Repository\MeetingRepositoryInterface;
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
     * @throws \Exception
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

    public function isRoomAvailable(Room $room, \DateTime $startsAt, \DateTime $endsAt): bool
    {
        return !$this->meetingRepository->isMeetingExists($room, $startsAt, $endsAt);
    }

    public function areParticipantsAvailable(array $participants, \DateTime $startsAt, \DateTime $endsAt): bool
    {
        foreach ($participants as $user) {
            if ($this->meetingRepository->isParticipantBusy($user, $startsAt, $endsAt)) {
                return false;
            }
        }

        return true;
    }

    public function findMeeting(MeetingUid $meetingUid): Meeting
    {

    }

    /**
     * @param \Meeting\Domain\Meeting $meeting
     * @throws \Meeting\Domain\Exception\DomainException
     */
    public function cancelMeeting(Meeting $meeting): void
    {
        // TODO: 2019-11-15
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
        // TODO: 2019-11-15
        $this->meetingRepository->save($meeting);
    }
}