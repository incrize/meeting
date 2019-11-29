<?php

namespace Meeting\App\Service;

use Meeting\Domain\ValueObject\User\UserUid;
use Meeting\Domain\User;

interface UserServiceInterface
{

    /**
     * @param \Meeting\Domain\ValueObject\User\UserUid $uid
     *
     * @return bool
     */
    public function fire(UserUid $uid) : bool;

    /**
     * @param \Meeting\Domain\User $user
     *
     * @return bool
     */
    public function hire(User $user) : bool;

}