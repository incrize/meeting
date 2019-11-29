<?php

namespace Meeting\App\Service\DataTransferObject;

use Meeting\Domain\Meeting;
use phpDocumentor\Reflection\DocBlock\Tags\Method;

class MeetingDTO
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
     * MeetingDTO constructor.
     *
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingUid $uid
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingStatus $status
     * @param \DateTime $createdAt
     * @param \DateTime $updatedAt
     * @param \Meeting\Domain\Room $room
     * @param \Meeting\Domain\User $creator
     * @param \Meeting\Domain\User[] $participants
     * @param \DateTime $startsAt
     * @param \DateTime $endsAt
     */

    // TODO: поля и значения актуальные для клиента
    public function __construct(
        \Meeting\Domain\ValueObject\Meeting\MeetingUid $uid = null,
        \Meeting\Domain\ValueObject\Meeting\MeetingStatus $status = null,
        \DateTime $createdAt = null,
        \DateTime $updatedAt = null,
        \Meeting\Domain\Room $room = null,
        \Meeting\Domain\User $creator = null,
        array $participants = null,
        \DateTime $startsAt = null,
        \DateTime $endsAt = null
    ) {
        $this->uid = $uid;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->room = $room;
        $this->creator = $creator;
        $this->participants = $participants;
        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
    }

    public function retrieval() : ?Meeting
    {
        $meeting = new Meeting($this->getUid(), $this->getRoom(), $this->getCreator(), $this->getStartsAt(), $this->getEndsAt());
    }

    /**
     * @return \Meeting\Domain\ValueObject\Meeting\MeetingUid
     */
    public function getUid(): \Meeting\Domain\ValueObject\Meeting\MeetingUid
    {
        return $this->uid;
    }

    /**
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingUid $uid
     */
    public function setUid(\Meeting\Domain\ValueObject\Meeting\MeetingUid $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @return \Meeting\Domain\ValueObject\Meeting\MeetingStatus
     */
    public function getStatus(): \Meeting\Domain\ValueObject\Meeting\MeetingStatus
    {
        return $this->status;
    }

    /**
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingStatus $status
     */
    public function setStatus(\Meeting\Domain\ValueObject\Meeting\MeetingStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \Meeting\Domain\Room
     */
    public function getRoom(): \Meeting\Domain\Room
    {
        return $this->room;
    }

    /**
     * @param \Meeting\Domain\Room $room
     */
    public function setRoom(\Meeting\Domain\Room $room): void
    {
        $this->room = $room;
    }

    /**
     * @return \Meeting\Domain\User
     */
    public function getCreator(): \Meeting\Domain\User
    {
        return $this->creator;
    }

    /**
     * @param \Meeting\Domain\User $creator
     */
    public function setCreator(\Meeting\Domain\User $creator): void
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
     * @return \DateTime
     */
    public function getStartsAt(): \DateTime
    {
        return $this->startsAt;
    }

    /**
     * @param \DateTime $startsAt
     */
    public function setStartsAt(\DateTime $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    /**
     * @return \DateTime
     */
    public function getEndsAt(): \DateTime
    {
        return $this->endsAt;
    }

    /**
     * @param \DateTime $endsAt
     */
    public function setEndsAt(\DateTime $endsAt): void
    {
        $this->endsAt = $endsAt;
    }




}
