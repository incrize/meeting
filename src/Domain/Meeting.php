<?php


namespace Meeting\Domain;


use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\ValueObject\Meeting\MeetingParticipants;
use Meeting\Domain\ValueObject\Meeting\MeetingStatus;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;

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

    /** @var \Meeting\Domain\ValueObject\Meeting\MeetingParticipants */
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
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingParticipants $participants
     * @param \DateTime                                               $startsAt
     * @param \DateTime                                               $endsAt
     *
     * @throws \Meeting\Domain\Exception\DomainException
     * @throws \Exception
     */
    public function __construct(
        MeetingUid $uid, Room $room, User $creator,
        MeetingParticipants $participants, \DateTime $startsAt, \DateTime $endsAt
    ) {
        $this->uid = $uid;
        $this->status = MeetingStatus::createActiveStatus();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        $this->setRoom($room);
        $this->setCreator($creator);
        $this->setParticipants($participants);
        $this->setDates($startsAt, $endsAt);
    }

    public function setRoom(Room $room)
    {
        if ($room->getStatus()->isClosed()) {
            throw new DomainException('Room must be open');
        }

        $this->room = $room;
    }

    public function setCreator(User $creator)
    {
        if (!$creator->getStatus()->isActive()) {
            throw new DomainException('Creator must be active');
        }

        $this->creator = $creator;
    }

    public function setDates(\DateTime $startsAt, \DateTime $endsAt)
    {
        if ($startsAt->getTimestamp() < time()) {
            throw new DomainException('Starts date invalid');
        }

        if ($endsAt->getTimestamp() < $startsAt->getTimestamp()) {
            throw new DomainException('Ends date invalid');
        }

        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
    }

    public function setParticipants(MeetingParticipants $participants)
    {
        if (!$participants->has($this->creator)) {
            throw new DomainException('Participants date invalid');
        }

        $this->participants = $participants;
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
     * @return \Meeting\Domain\ValueObject\Meeting\MeetingParticipants
     */
    public function getParticipants(): ValueObject\Meeting\MeetingParticipants
    {
        return $this->participants;
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
}