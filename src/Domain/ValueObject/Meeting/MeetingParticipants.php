<?php


namespace Meeting\Domain\ValueObject\Meeting;


use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\User;

class MeetingParticipants implements \Iterator
{
    /** @var \Meeting\Domain\User[] */
    protected $users = [];

    public function __construct(array $users)
    {
        if (empty($users)) {
            throw new DomainException('Participants must specified');
        }

        foreach ($users as $user) {
            $this->addUser($user);
        }
    }

    public function addUser(User $user)
    {
        if (!$user->getStatus()->isActive()) {
            throw new DomainException('Participant must be active');
        }

        $this->users[$user->getUid()->toString()] = $user;
    }

    public function has(User $user)
    {
        return isset($this->users[$user->getUid()->toString()]);
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return current($this->users);
    }

    /**
     * @inheritDoc
     * @return \Meeting\Domain\User
     */
    public function next()
    {
        return next($this->users);
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return key($this->users);
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->current() !== false;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        reset($this->users);
    }
}