<?php


namespace Meeting\Test\App\Repository;


use Meeting\App\Repository\MeetingRepository;
use Meeting\Domain\Meeting;
use Meeting\Domain\Room;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\Meeting\MeetingParticipants;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;
use Meeting\Domain\ValueObject\Room\RoomName;
use Meeting\Domain\ValueObject\Room\RoomStatus;
use Meeting\Domain\ValueObject\Room\RoomUid;
use Meeting\Domain\ValueObject\User\UserName;
use Meeting\Domain\ValueObject\User\UserStatus;
use Meeting\Domain\ValueObject\User\UserUid;
use PHPUnit\Framework\TestCase;

class MeetingRepositoryTest extends TestCase
{
    public function testSave()
    {
        $repository = new MeetingRepository();
        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus()),
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
            new MeetingParticipants([
                new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
                new User(new UserUid('2'), new UserName('Alexander'), UserStatus::createActiveStatus()),
            ]),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        );

        $this->assertTrue($repository->save($meeting));

        $this->assertTrue($repository->isMeetingExists(
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus()),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        ));

        $this->assertTrue($repository->isParticipantBusy(
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        ));

        $this->assertTrue($repository->isParticipantBusy(
            new User(new UserUid('2'), new UserName('Alexander'), UserStatus::createActiveStatus()),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        ));

        $this->assertFalse($repository->isMeetingExists(
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus()),
            new \DateTime('+2 day'),
            new \DateTime('+3 day')
        ));

        $this->assertFalse($repository->isMeetingExists(
            new Room(new RoomUid('2'), new RoomName('408'), '', RoomStatus::createOpenStatus()),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        ));

        $this->assertFalse($repository->isParticipantBusy(
            new User(new UserUid('3'), new UserName('Sergey'), UserStatus::createActiveStatus()),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        ));

        $this->assertFalse($repository->isParticipantBusy(
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
            new \DateTime('+2 day'),
            new \DateTime('+3 day')
        ));
    }
}