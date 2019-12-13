<?php


namespace Meeting\Domain;


use Meeting\Domain\Exception\Meeting\MeetingDateInvalidException;
use Meeting\Domain\Exception\Room\RoomNotOpenException;
use Meeting\Domain\Exception\User\UserNotActiveException;
use Meeting\Domain\ValueObject\Meeting\MeetingStatus;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;
use DateTime;

class Meeting
{
    /** @var \Meeting\Domain\ValueObject\Meeting\MeetingUid */
    protected $uid;

    /** @var \Meeting\Domain\ValueObject\Meeting\MeetingStatus */
    protected $status;

    /** @var \DateTime */
    protected $createdAt;

    /** @var \DateTime */
    protected $updatedAt;

    /** @var \Meeting\Domain\Room */
    protected $room;

    /** @var \Meeting\Domain\User */
    protected $creator;

    /** @var \Meeting\Domain\User[] */
    protected $participants;

    /** @var \DateTime */
    protected $startsAt;

    /** @var \DateTime */
    protected $endsAt;

    /**
     * Meeting constructor.
     *
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingUid          $uid
     * @param \Meeting\Domain\Room                                    $room
     * @param \Meeting\Domain\User                                    $creator
     * @param \DateTime                                               $startsAt
     * @param \DateTime                                               $endsAt
     *
     * @throws \Meeting\Domain\Exception\DomainException
     * @throws \Exception
     */
    public function __construct(
        MeetingUid $uid, Room $room, User $creator,
        DateTime $startsAt, DateTime $endsAt
    ) {
        $this->uid = $uid;
        $this->status = MeetingStatus::createActiveStatus();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();

        $this->setRoom($room);
        $this->setCreator($creator);
        $this->setDates($startsAt, $endsAt);
    }

    public function setRoom(Room $room)
    {
        if ($room->getStatus()->isClosed()) {
            throw new RoomNotOpenException('Room must be open');
        }

        $this->room = $room;
    }

    public function setCreator(User $creator)
    {
        if (!$creator->getStatus()->isActive()) {
            throw new UserNotActiveException('Creator must be active');
        }

        if (!empty($this->creator)) {
            $this->removeParticipant($this->creator);
        }

        $this->addParticipant($creator);
        $this->creator = $creator;
    }

    public function setDates(\DateTime $startsAt, \DateTime $endsAt)
    {
        if ($startsAt->getTimestamp() < time()) {
            throw new MeetingDateInvalidException('Starts date invalid');
        }

        if ($endsAt->getTimestamp() < $startsAt->getTimestamp()) {
            throw new MeetingDateInvalidException('Ends date invalid');
        }

        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
    }

    /**
     * @param \Meeting\Domain\User $participant
     *
     * @throws \Meeting\Domain\Exception\User\UserNotActiveException
     */
    public function addParticipant(User $participant): void
    {
        if (!$participant->getStatus()->isActive()) {
            throw new UserNotActiveException('Participant must be active');
        }

        $this->participants[$participant->getUid()->toString()] = $participant;
    }

    public function addParticipants(array $participants): void
    {
        foreach ($participants as $participant) {
            try {
                $this->addParticipant($participant);
            } catch (UserNotActiveException $e) {
                return;
            }
        }
    }

    public function hasParticipant(User $participant): bool
    {
        return isset($this->participants[$participant->getUid()->toString()]);
    }

    public function removeParticipant(User $participant): void
    {
        unset($this->participants[$participant->getUid()->toString()]);
    }

    /**
     * @return \Meeting\Domain\ValueObject\Meeting\MeetingUid
     */
    public function getUid(): ValueObject\Meeting\MeetingUid
    {
        return $this->uid;
    }

    /**
     * @return \Meeting\Domain\ValueObject\Meeting\MeetingStatus
     */
    public function getStatus(): ValueObject\Meeting\MeetingStatus
    {
        return $this->status;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return \Meeting\Domain\Room
     */
    public function getRoom(): \Meeting\Domain\Room
    {
        return $this->room;
    }

    /**
     * @return \Meeting\Domain\User
     */
    public function getCreator(): \Meeting\Domain\User
    {
        return $this->creator;
    }

    /**
     * @return \Meeting\Domain\User[]
     */
    public function getParticipants(): array
    {
        return $this->participants;
    }

    public function setParticipants(array $participants)
    {
        $this->participants = $participants;
    }

    /**
     * @return \DateTime
     */
    public function getStartsAt(): \DateTime
    {
        return $this->startsAt;
    }

    /**
     * @return \DateTime
     */
    public function getEndsAt(): \DateTime
    {
        return $this->endsAt;
    }

    public function setStatus(MeetingStatus $status)
    {
        $this->status = $status;
    }
}