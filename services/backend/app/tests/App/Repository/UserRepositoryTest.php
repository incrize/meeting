<?php

namespace Meeting\Tests\Unit;

use DateTime;
use Meeting\Infrastructure\Persistence\UserRepository;
use Meeting\App\Exception\DuplicateUserException;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\User\UserName;
use Meeting\Domain\ValueObject\User\UserUid;
use Meeting\App\Exception\UserNotFoundException;
use Meeting\App\ValueObject\User\UserSearchSpecification;
use Meeting\Domain\ValueObject\User\UserStatus;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    /**
     * @dataProvider dataProviderFindById
     */
    public function testFindById(array $users, UserUid $userUid, $expectedUser)
    {
        $repository = new UserRepository($users);

        $actualUser = $repository->findById($userUid);

        $this->assertEquals($expectedUser, $actualUser);
    }

    public function dataProviderFindById()
    {
        $date = new DateTime();

        return [
            [
                [],
                new UserUid(1),
                null,
            ],
            [
                [
                    new User(new UserUid(1), new UserName('Ignat'), UserStatus::createActiveStatus(), $date, $date),
                    new User(new UserUid(2), new UserName('Ivan'), UserStatus::createActiveStatus(), $date, $date),
                    new User(new UserUid(3), new UserName('Albert'), UserStatus::createActiveStatus(), $date, $date),
                ],
                new UserUid(1),
                new User(new UserUid(1), new UserName('Ignat'), UserStatus::createActiveStatus(), $date, $date),
            ],
            [
                [
                    new User(new UserUid(1), new UserName('Ignat'), UserStatus::createActiveStatus(), $date, $date),
                    new User(new UserUid(2), new UserName('Ivan'), UserStatus::createActiveStatus(), $date, $date),
                    new User(new UserUid(3), new UserName('Albert'), UserStatus::createActiveStatus(), $date, $date),
                ],
                new UserUid(44),
                null,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderFindByStatus
     */
    public function testFindByStatus(array $users, UserStatus $userStatus, $expectedUser)
    {
        $repository = new UserRepository($users);

        $actualUser = $repository->findByStatus($userStatus);

        $this->assertEquals($expectedUser, $actualUser);
    }

    public function dataProviderFindByStatus()
    {
        $date = new DateTime();

        return [
            [
                [],
                UserStatus::createActiveStatus(),
                [],
            ],
            [
                [
                    new User(new UserUid(1), new UserName('Ignat'), UserStatus::createActiveStatus(), $date, $date),
                    new User(new UserUid(2), new UserName('Ivan'), UserStatus::createFiredStatus(), $date, $date),
                    new User(new UserUid(3), new UserName('Albert'), UserStatus::createActiveStatus(), $date, $date),
                ],
                UserStatus::createActiveStatus(),
                [
                    new User(new UserUid(1), new UserName('Ignat'), UserStatus::createActiveStatus(), $date, $date),
                    new User(new UserUid(3), new UserName('Albert'), UserStatus::createActiveStatus(), $date, $date),
                ],
            ],
            [
                [
                    new User(new UserUid(1), new UserName('Ignat'), UserStatus::createActiveStatus(), $date, $date),
                    new User(new UserUid(2), new UserName('Ivan'), UserStatus::createActiveStatus(), $date, $date),
                    new User(new UserUid(3), new UserName('Albert'), UserStatus::createActiveStatus(), $date, $date),
                ],
                UserStatus::createFiredStatus(),
                [],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderDeleteExistingUser
     */
    public function testDeleteExistingUser(array $users, User $user)
    {
        $repository = new UserRepository($users);

        $repository->delete($user);
        $actualIsFound = $repository->findById($user->getUid()) !== null;

        $this->assertFalse($actualIsFound);
    }

    /**
     * @dataProvider dataProviderDeleteMissingUser
     */
    public function testDeleteMissingUser(array $users, User $user)
    {
        $repository = new UserRepository($users);

        $this->expectException(UserNotFoundException::class);

        $repository->delete($user);
    }

    public function dataProviderDeleteExistingUser()
    {
        return [
            [
                [
                    new User(new UserUid(1), new UserName('Ignat')),
                    new User(new UserUid(2), new UserName('Ivan'), UserStatus::createFiredStatus()),
                    new User(new UserUid(3), new UserName('Albert')),
                ],
                new User(new UserUid(2), new UserName('Ivan'), UserStatus::createFiredStatus()),
            ],
        ];
    }

    public function dataProviderDeleteMissingUser()
    {
        return [
            [
                [],
                new User(new UserUid(1), new UserName('Ignat')),
            ],
            [
                [
                    new User(new UserUid(1), new UserName('Ignat')),
                    new User(new UserUid(2), new UserName('Ivan')),
                    new User(new UserUid(3), new UserName('Albert')),
                ],
                new User(new UserUid(99), new UserName('Ivan'), UserStatus::createFiredStatus()),
            ],
        ];
    }

    /**
     * @dataProvider dataProviderUpdateExistingUser
     */
    public function testUpdateExistingUser(array $users, User $user)
    {
        $repository = new UserRepository($users);

        $repository->update($user);

        $actualUser = $repository->findById($user->getUid());

        $this->assertEquals($user, $actualUser);
    }

    /**
     * @dataProvider dataProviderUpdateMissingUser
     */
    public function testUpdateMissingUser(array $users, User $user)
    {
        $repository = new UserRepository($users);

        $this->expectException(UserNotFoundException::class);

        $repository->update($user);
    }

    public function dataProviderUpdateExistingUser()
    {
        return [
            [
                [
                    new User(new UserUid(1), new UserName('Ignat')),
                    new User(new UserUid(2), new UserName('Ivan'), UserStatus::createFiredStatus()),
                    new User(new UserUid(3), new UserName('Albert')),
                ],
                new User(new UserUid(1), new UserName('Igor')),
            ]
        ];
    }

    public function dataProviderUpdateMissingUser()
    {
        return [
            [
                [
                    new User(new UserUid(1), new UserName('Ignat')),
                    new User(new UserUid(2), new UserName('Ivan'), UserStatus::createFiredStatus()),
                    new User(new UserUid(3), new UserName('Albert')),
                ],
                new User(new UserUid(99), new UserName('Igor')),
            ]
        ];
    }

    /**
     * @dataProvider dataProviderCreateNewUser
     */
    public function testCreateNewUser(array $users, User $user)
    {
        $repository = new UserRepository($users);

        $repository->create($user);
        $actualUser = $repository->findById($user->getUid());

        $this->assertEquals($user, $actualUser);
    }

    public function dataProviderCreateNewUser() {
        return [
            [
                [
                    new User(new UserUid(1), new UserName('Ignat')),
                    new User(new UserUid(2), new UserName('Ivan'), UserStatus::createFiredStatus()),
                    new User(new UserUid(3), new UserName('Albert')),
                ],
                new User(new UserUid(4), new UserName('Leonid')),
            ]
        ];
    }

    /**
     * @dataProvider dataProviderCreateExistingUser
     */
    public function testCreateExistingUser(array $users, User $user)
    {
        $repository = new UserRepository($users);

        $this->expectException(DuplicateUserException::class);

        $repository->create($user);
    }

    public function dataProviderCreateExistingUser() {
        return [
            [
                [
                    new User(new UserUid(1), new UserName('Ignat')),
                    new User(new UserUid(2), new UserName('Ivan'), UserStatus::createFiredStatus()),
                    new User(new UserUid(3), new UserName('Albert')),
                ],
                new User(new UserUid(1), new UserName('Leonid')),
            ]
        ];
    }
    /**
     * @dataProvider dataProviderCount
     */
    public function testCount(array $users, UserSearchSpecification $spec, int $expectedCount)
    {
        $repository = new UserRepository($users);

        $actualCount = $repository->count($spec);

        $this->assertEquals($expectedCount, $actualCount);
    }

    public function dataProviderCount()
    {
        $date = new DateTime();

        return [
            [
                [],
                new UserSearchSpecification(),
                0,
            ],
            [
                [
                    new User(new UserUid(1), new UserName('Ignat'), UserStatus::createActiveStatus(), $date, $date),
                    new User(new UserUid(2), new UserName('Ivan'), UserStatus::createActiveStatus(), $date, $date),
                    new User(new UserUid(3), new UserName('Albert'), UserStatus::createActiveStatus(), $date, $date),
                ],
                new UserSearchSpecification(),
                3,
            ],
        ];
    }
}
