<?php


namespace Meeting\Domain;


use Meeting\Domain\ValueObject\User\UserName;
use Meeting\Domain\ValueObject\User\UserStatus;
use Meeting\Domain\ValueObject\User\UserUid;

class User
{
    /** @var \Meeting\Domain\ValueObject\User\UserUid */
    protected $uid;

    /** @var \Meeting\Domain\ValueObject\User\UserName */
    protected $name;

    /** @var \Meeting\Domain\ValueObject\User\UserStatus */
    protected $status;

    /** @var \DateTime */
    protected $createdAt;

    /** @var \DateTime */
    protected $updatedAt;

    /**
     * User constructor.
     *
     * @param \Meeting\Domain\ValueObject\User\UserUid    $uid
     * @param \Meeting\Domain\ValueObject\User\UserName   $name
     * @param \Meeting\Domain\ValueObject\User\UserStatus $status
     */
    public function __construct(
        UserUid $uid, UserName $name,
        UserStatus $status
    ) {
        $this->uid = $uid;
        $this->name = $name;
        $this->status = $status;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return \Meeting\Domain\ValueObject\User\UserUid
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return \Meeting\Domain\ValueObject\User\UserName
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \Meeting\Domain\ValueObject\User\UserStatus
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