<?php


namespace Meeting\Domain\ValueObject\Meeting;


use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\Service\UidGeneratorInterface;

class MeetingUid
{
    protected $uid;

    public function __construct($uid)
    {
        if (empty($uid)) {
            throw new DomainException('ID must be specified.');
        }

        $this->uid = $uid;
    }

    public static function create(UidGeneratorInterface $uidGenerator)
    {
        return new self($uidGenerator->createUid());
    }

    public function toString()
    {
        return $this->uid;
    }

    public function isEqual(MeetingUid $uid)
    {
        return $this->toString() === $uid->toString();
    }
}