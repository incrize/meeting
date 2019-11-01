<?php

namespace Meeting\App\Repository;

use Meeting\App\Exception\DuplicateUserException;
use Meeting\App\Exception\UserNotFoundException;
use Meeting\App\ValueObject\User\UserSearchSpecification;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\User\UserStatus;
use Meeting\Domain\ValueObject\User\UserUid;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var \Meeting\Domain\User[]
     */
    protected $users;

    /**
     * DummyUserRepository constructor.
     *
     * @param \Meeting\Domain\User[] $users
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /** @inheritDoc */
    public function create(User $user): User
    {
        if ($this->findById($user->getUid())) {
            throw new DuplicateUserException();
        }
        $this->users[] = $user;

        return $user;
    }

    /** @inheritDoc */
    public function delete(User $user): User
    {
        if (!$this->findById($user->getUid())) {
            throw new UserNotFoundException();
        }

        $this->users = array_filter($this->users, function (User $repoUser) use ($user) {
            return !$repoUser->getUid()->isEqual($user->getUid());
        });

        return $user;
    }

    /** @inheritDoc */
    public function update(User $user): User
    {
        if (!$this->findById($user->getUid())) {
            throw new UserNotFoundException();
        }

        foreach ($this->users as $index => $repoUser) {
            if ($repoUser->getUid()->isEqual($user->getUid())) {
                $this->users[$index] = $user;
                break;
            }
        }

        return $this->findById($user->getUid());
    }

    /** @inheritDoc */
    public function find(UserSearchSpecification $spec): array
    {
        return $this->users;
    }

    /** @inheritDoc */
    public function findById(UserUiD $id)
    {
        foreach ($this->users as $user) {
            if ($user->getUid()->isEqual($id)) {
                return $user;
            }
        }
        return null;
    }

    /** @inheritDoc */
    public function findByStatus(UserStatus $status): array
    {
        $foundUsers = [];

        foreach ($this->users as $user) {
            if ($user->getStatus()->isEqual($status)) {
                $foundUsers[] = $user;
            }
        }

        return $foundUsers;
    }

    /** @inheritDoc */
    public function count(UserSearchSpecification $spec): int
    {
        return count($this->users);
    }
}
