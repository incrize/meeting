<?php


namespace Meeting\Domain;


use Meeting\Domain\Exception\Meeting\MeetingNotExistsException;
use Meeting\Domain\Exception\User\ParticipantNotUserException;
use Meeting\Domain\Exception\User\UserNotActiveException;
use Meeting\Domain\Exception\Room\RoomNotAvaliableException;
use Meeting\Domain\Exception\User\ParticipantsNotAvaliableException;
use Meeting\Domain\Exception\Meeting\MeetingAlreadyCanceledException;
use Meeting\Domain\Exception\Meeting\MeetingHasNoParticipantsException;
use Meeting\Domain\Exception\Meeting\MeetingAlreadyOverException;
use Meeting\Domain\Repository\MeetingRepositoryInterface;
use Meeting\Domain\ValueObject\Meeting\MeetingStatus;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;
use DateTime;

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
            throw new RoomNotAvaliableException('Room is not available');
        }

        if (!$this->areParticipantsAvailable($meeting->getParticipants(), $meeting->getStartsAt(), $meeting->getEndsAt())) {
            throw new ParticipantsNotAvaliableException('Some participant is not available');
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
     * @return \Meeting\Domain\Meeting|null
     */
    public function findMeeting(MeetingUid $meetingUid): ?Meeting
    {
        return $this->meetingRepository->find($meetingUid);
    }

    /**
     * @param \Meeting\Domain\Meeting $meeting
     *
     * @throws \Meeting\Domain\Exception\Meeting\MeetingAlreadyCanceledException
     */
    public function cancelMeeting(MeetingUid $meetingUid): void
    {
        $meeting = $this->meetingRepository->find($meetingUid);
        //TODO === null
        if (!$meeting) {
            throw new MeetingNotExistsException('Canceling meeting is not exists');
        }
        if ($meeting->getStatus()->isEqual(MeetingStatus::createCanceledStatus())) {
            throw new MeetingAlreadyCanceledException('Meeting already canceled');
        }

        $meeting->setStatus(MeetingStatus::createCanceledStatus());

        $this->meetingRepository->save($meeting);
    }

    /**
     * @param \Meeting\Domain\Meeting $meeting
     * @param array                   $participants
     *
     * @throws \Meeting\Domain\Exception\Meeting\MeetingHasNoParticipantsException
     * @throws \Meeting\Domain\Exception\Meeting\MeetingAlreadyOverException
     * @throws \Meeting\Domain\Exception\User\ParticipantNotUserException
     * @throws \Meeting\Domain\Exception\User\UserNotActiveException
     */
    public function updateMeetingParticipants(Meeting $meeting, array $participants): void
    {
        if (empty($participants)) {
            throw new MeetingHasNoParticipantsException('Meeting must have participants');
        }

        $now = new DateTime();
        if ($meeting->getEndsAt() < $now) {
            throw new MeetingAlreadyOverException('Meeting already over');
        }

        foreach ($participants as $participant) {
            if (!($participant instanceof User)) {
                throw new ParticipantNotUserException('Only users can be participants');
            } elseif ($participant->getStatus()->isFired()) {
                throw new UserNotActiveException('Only active users can be participants');
            }
        }

        if ($this->areParticipantsAvailable($participants, $meeting->getStartsAt(), $meeting->getEndsAt())) {

            //TODO Domain events? Solve notification problem
            //some participants maybe already added to this meeting - no need to add them again
            $old_participants = $meeting->getParticipants();
            $new_participants = array_diff($participants, $old_participants);

            $meeting->addParticipants($new_participants);
            $this->meetingRepository->save($meeting);
        }
    }
}
