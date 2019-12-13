<?php

namespace Meeting\App\DataTransferObject;

use Meeting\Domain\ValueObject\Meeting\MeetingStatus;
use DateTime;
use Meeting\Domain\Room;
use Meeting\Domain\User;

/**
 * Class MeetingDTO
 *
 * @package Meeting\App\DataTransferObject
 */
class MeetingDTO
{
    /** @var \Meeting\Domain\ValueObject\Meeting\MeetingStatus */
    protected $status;

    /** @var \Meeting\Domain\Room */
    protected $room;

    /** @var \Meeting\Domain\User */
    protected $creator;

    /** @var \Meeting\Domain\User[] */
    protected $participants;

    /** @var DateTime */
    protected $startsAt;

    /** @var DateTime */
    protected $endsAt;

    /**
     * MeetingDTO constructor.
     *
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingStatus $status
     * @param \Meeting\Domain\Room $room
     * @param \Meeting\Domain\User $creator
     * @param \Meeting\Domain\User[] $participants
     * @param DateTime $startsAt
     * @param DateTime $endsAt
     */

    public function __construct(
        MeetingStatus $status = null,
        Room $room = null,
        User $creator = null,
        array $participants = null,
        DateTime $startsAt = null,
        DateTime $endsAt = null
    ) {
        $this->status = $status;
        $this->room = $room;
        $this->creator = $creator;
        $this->participants = $participants;
        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
    }

    /**
     * @return \Meeting\Domain\ValueObject\Meeting\MeetingStatus
     */
    public function getStatus(): MeetingStatus
    {
        return $this->status;
    }

    /**
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingStatus $status
     */
    public function setStatus(MeetingStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return \Meeting\Domain\Room
     */
    public function getRoom(): Room
    {
        return $this->room;
    }

    /**
     * @param \Meeting\Domain\Room $room
     */
    public function setRoom(Room $room): void
    {
        $this->room = $room;
    }

    /**
     * @return \Meeting\Domain\User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

    /**
     * @param \Meeting\Domain\User $creator
     */
    public function setCreator(User $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return \Meeting\Domain\User[]
     */
    public function getParticipants(): array
    {
        return $this->participants;
    }

    /**
     * @param \Meeting\Domain\User[] $participants
     */
    public function setParticipants(array $participants): void
    {
        $this->participants = $participants;
    }

    /**
     * @return DateTime
     */
    public function getStartsAt(): DateTime
    {
        return $this->startsAt;
    }

    /**
     * @param DateTime $startsAt
     */
    public function setStartsAt(DateTime $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    /**
     * @return DateTime
     */
    public function getEndsAt(): DateTime
    {
        return $this->endsAt;
    }

    /**
     * @param DateTime $endsAt
     */
    public function setEndsAt(DateTime $endsAt): void
    {
        $this->endsAt = $endsAt;
    }
}
