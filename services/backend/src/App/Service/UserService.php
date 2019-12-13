<?php

namespace Meeting\App\Service;

use Meeting\App\Exception\DuplicateUserException;
use Meeting\App\Exception\UserNotFoundException;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\User\UserStatus;
use Meeting\Domain\ValueObject\User\UserUid;

class UserService implements UserServiceInterface
{
    /** @var \Meeting\App\Repository\UserRepositoryInterface */
    protected $userRepository;

    /**
     * @param \Meeting\Domain\ValueObject\User\UserUid $uid
     *
     * @return bool
     * @throws \Meeting\App\Exception\UserNotFoundException
     */
    public function fire(UserUid $uid): bool
    {
        $user = $this->userRepository->findById($uid);
        if (!$user) {
            throw new UserNotFoundException('Firing user is not exists');
        }

        try {
            //delete method should not returns User object
            $user = $this->userRepository->delete($user);
        } catch (UserNotFoundException $e) {
            return false;
        }
        return !empty($user);
    }

    /**
     * @param \Meeting\Domain\User $user
     *
     * @return bool
     */
    public function hire(User $user): bool
    {
        if (!$user->getStatus()->isActive()) {
            $user->setStatus(UserStatus::createActiveStatus());
        }

        try {
            $this->userRepository->create($user);
        } catch (DuplicateUserException $e) {
            return false;
        }

        return true;
    }

}
