<?php


namespace Meeting\Tests\Domain;


use Meeting\Domain\Exception\DomainException;
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

class MeetingTest extends TestCase
{
    public function testSetClosedRoom()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Room must be open');

        new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createClosedStatus()),
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
            new MeetingParticipants([
                new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
                new User(new UserUid('2'), new UserName('Alexander'), UserStatus::createActiveStatus()),
            ]),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        );
    }

    public function testSetFiredCreator()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Creator must be active');

        new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus()),
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createFiredStatus()),
            new MeetingParticipants([
                new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
                new User(new UserUid('2'), new UserName('Alexander'), UserStatus::createActiveStatus()),
            ]),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        );
    }

    public function testSetOutdatedStartDate()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Starts date invalid');

        new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus()),
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
            new MeetingParticipants([
                new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
                new User(new UserUid('2'), new UserName('Alexander'), UserStatus::createActiveStatus()),
            ]),
            new \DateTime('-1 day'),
            new \DateTime('+2 day')
        );
    }

    public function testSetOutdatedEndDate()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Ends date invalid');

        new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus()),
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
            new MeetingParticipants([
                new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
                new User(new UserUid('2'), new UserName('Alexander'), UserStatus::createActiveStatus()),
            ]),
            new \DateTime('+2 day'),
            new \DateTime('+1 day')
        );
    }

    public function testPositiveCreate()
    {
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

        $this->assertEquals('1', $meeting->getUid()->toString());
    }
}