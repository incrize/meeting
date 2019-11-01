<?php


namespace Meeting\Domain\Service;


use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\Meeting;
use Meeting\Domain\Repository\MeetingRepositoryInterface;
use Meeting\Domain\Room;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\Meeting\MeetingParticipants;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;

class MeetingService
{
    /** @var \Meeting\Domain\Service\UidGeneratorInterface */
    protected $uidGenerator;

    /** @var \Meeting\Domain\Repository\MeetingRepositoryInterface */
    protected $meetingRepository;

    public function __construct(UidGeneratorInterface $uidGenerator, MeetingRepositoryInterface $meetingRepository)
    {
        $this->uidGenerator = $uidGenerator;
        $this->meetingRepository = $meetingRepository;
    }

    /**
     * @param \Meeting\Domain\User                                    $creator
     * @param \Meeting\Domain\Room                                    $room
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingParticipants $participants
     * @param \DateTime                                               $startsAt
     * @param \DateTime                                               $endsAt
     *
     * @return \Meeting\Domain\Meeting
     * @throws \Meeting\Domain\Exception\DomainException
     * @throws \Exception
     */
    public function createMeeting(User $creator, Room $room, MeetingParticipants $participants, \DateTime $startsAt, \DateTime $endsAt)
    {
        if (!$this->isRoomAvailable($room, $startsAt, $endsAt)) {
            throw new DomainException('Room is not available');
        }

        if (!$this->areParticipantsAvailable($participants, $startsAt, $endsAt)) {
            throw new DomainException('Some participant is not available');
        }

        $meeting = new Meeting(
            MeetingUid::create($this->uidGenerator),
            $room,
            $creator,
            $participants,
            $startsAt,
            $endsAt
        );

        $this->meetingRepository->save($meeting);

        return $meeting;
    }

    public function isRoomAvailable(Room $room, \DateTime $startsAt, \DateTime $endsAt): bool
    {
        return !$this->meetingRepository->isMeetingExists($room, $startsAt, $endsAt);
    }

    public function areParticipantsAvailable(MeetingParticipants $participants, \DateTime $startsAt, \DateTime $endsAt): bool
    {
        foreach ($participants as $user) {
            if ($this->meetingRepository->isParticipantBusy($user, $startsAt, $endsAt)) {
                return false;
            }
        }

        return true;
    }
}