<?php

namespace Meeting\App\Repository;

use Meeting\App\ValueObject\User\UserSearchSpecification;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\User\UserStatus;
use Meeting\Domain\ValueObject\User\UserUid;

interface UserRepositoryInterface
{
    /**
     * @param \Meeting\Domain\User $user
     *
     * @return \Meeting\Domain\User
     * @throws \Meeting\App\Exception\DuplicateUserException
     */
    public function create(User $user): User;

    /**
     * @param \Meeting\Domain\User $user
     *
     * @return \Meeting\Domain\User
     * @throws \Meeting\App\Exception\UserNotFoundException
     */
    public function delete(User $user): User;

    /**
     * @param \Meeting\Domain\User $user
     *
     * @return \Meeting\Domain\User
     * @throws \Meeting\App\Exception\UserNotFoundException
     */
    public function update(User $user): User;

    /**
     * @param \Meeting\App\ValueObject\User\UserSearchSpecification $spec
     *
     * @return \Meeting\Domain\User[]
     */
    public function find(UserSearchSpecification $spec): array;

    /**
     * @param \Meeting\Domain\ValueObject\User\UserUid $id
     *
     * @return \Meeting\Domain\User|null
     */
    public function findById(UserUid $id);

    /**
     * @param \Meeting\Domain\ValueObject\User\UserStatus $status
     *
     * @return \Meeting\Domain\User[]
     */
    public function findByStatus(UserStatus $status): array;

    public function count(UserSearchSpecification $spec): int;
}
