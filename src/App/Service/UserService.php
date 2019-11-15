<?php

namespace Meeting\App\Service;

use Meeting\Domain\User;
use Meeting\Domain\ValueObject\User\UserName;
use Meeting\Domain\ValueObject\User\UserUid;

class UserService implements UserServiceInterface {

    public function getAllAvailableUsers(\DateTime $startAt, \DateTime $finishAt): array
    {
        // TODO: Getting all users that available in specified period of time. Not busy in existing meetings, active. (working in specified hours, not in vacation??)
    }

    public function getUserMeetings(UserUid $uid, \DateTime $startAt, \DateTime $finishAt): array
    {
        // TODO: Get meetings for specified user in specified time period. To find spot in somebody schedule, to see somebody schedule at all.
    }

    public function deactivateUser(UserUid $uid): bool
    {
        // TODO: Changing user status and all additional operations connected with it. Not sure it is the right place for this.
    }

    public function activateUser(UserUid $uid): bool
    {
        // TODO: Changing user status and all additional operations connected with it. Not sure it is the right place for this.
    }

    public function createUser(UserName $name): User
    {
        // TODO: Creating user with all validation processes and supporting operations (about operations there is a doubt).
    }
}
