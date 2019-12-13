<?php


namespace Meeting\Domain;


use Meeting\Domain\ValueObject\Room\RoomName;
use Meeting\Domain\ValueObject\Room\RoomStatus;
use Meeting\Domain\ValueObject\Room\RoomUid;
use DateTime;

class Room
{
    /** @var \Meeting\Domain\ValueObject\Room\RoomUid */
    protected $uid;

    /** @var \Meeting\Domain\ValueObject\Room\RoomName */
    protected $name;

    /** @var string */
    protected $description;

    /** @var \Meeting\Domain\ValueObject\Room\RoomStatus */
    protected $status;

    /** @var \DateTime */
    protected $createdAt;

    /** @var \DateTime */
    protected $updatedAt;

    /**
     * Room constructor.
     *
     * @param \Meeting\Domain\ValueObject\Room\RoomUid    $uid
     * @param \Meeting\Domain\ValueObject\Room\RoomName   $name
     * @param string                                      $description
     * @param \Meeting\Domain\ValueObject\Room\RoomStatus $status
     * @param \DateTime                                   $createdAt
     * @param \DateTime                                   $updatedAt
     *
     * @throws \Meeting\Domain\Exception\DomainException
     * @throws \Exception
     */
    public function __construct(
        RoomUid $uid, RoomName $name, $description = '',
        RoomStatus $status = null, \DateTime $createdAt = null, \DateTime $updatedAt = null
    ) {
        $this->uid = $uid;
        $this->name = $name;
        $this->description = $description;
        $this->status = ($status) ? $status : RoomStatus::createOpenStatus();
        $this->createdAt = ($createdAt) ? $createdAt : new DateTime();
        $this->updatedAt = ($updatedAt) ? $updatedAt : new DateTime();
    }

    /**
     * @return \Meeting\Domain\ValueObject\Room\RoomUid
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return \Meeting\Domain\ValueObject\Room\RoomName
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return \Meeting\Domain\ValueObject\Room\RoomStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}