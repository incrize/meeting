<?php


namespace Meeting\Tests\Domain;

use DateTime;
use Meeting\Domain\Exception\Meeting\MeetingDateInvalidException;
use Meeting\Domain\Exception\Room\RoomNotOpenException;
use Meeting\Domain\Exception\User\UserNotActiveException;
use Meeting\Domain\Meeting;
use Meeting\Domain\Room;
use Meeting\Domain\User;
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
        $this->expectException(RoomNotOpenException::class);

        new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createClosedStatus()),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        );
    }

    public function testSetFiredCreator()
    {
        $this->expectException(UserNotActiveException::class);

        new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createFiredStatus()),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        );
    }

    public function testSetOutdatedStartDate()
    {
        $this->expectException(MeetingDateInvalidException::class);
        $this->expectExceptionMessage('Starts date invalid');

        new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('-1 day'),
            new DateTime('+2 day')
        );
    }

    public function testSetOutdatedEndDate()
    {
        $this->expectException(MeetingDateInvalidException::class);
        $this->expectExceptionMessage('Ends date invalid');

        new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+2 day'),
            new DateTime('+1 day')
        );
    }

    public function testPositiveCreate()
    {
        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        );

        $this->assertEquals('1', $meeting->getUid()->toString());
    }
}