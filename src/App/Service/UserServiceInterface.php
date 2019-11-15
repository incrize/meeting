<?php

namespace Meeting\App\Service;

use Meeting\Domain\ValueObject\User\UserName;
use Meeting\Domain\ValueObject\User\UserUid;
use Meeting\Domain\User;

interface UserServiceInterface {

    /**
     * @param \DateTime $startAt
     * @param \DateTime $finishAt
     *
     * @return array
     */
    public function getAllAvailableUsers(\DateTime $startAt, \DateTime $finishAt) : array;

    /**
     * @param \Meeting\Domain\ValueObject\User\UserUid $uid
     * @param \DateTime                                $startAt
     * @param \DateTime                                $finishAt
     *
     * @return array
     */
    public function getUserMeetings(UserUid $uid, \DateTime $startAt, \DateTime $finishAt) : array;

    /**
     * @param \Meeting\Domain\ValueObject\User\UserUid $uid
     *
     * @return bool
     */
    public function deactivateUser(UserUid $uid) : bool;

    /**
     * @param \Meeting\Domain\ValueObject\User\UserUid $uid
     *
     * @return bool
     */
    public function activateUser(UserUid $uid) : bool;

    /**
     * @param \Meeting\Domain\ValueObject\User\UserName $name
     *
     * @return \Meeting\Domain\User
     */
    public function createUser(UserName $name) : User;

}