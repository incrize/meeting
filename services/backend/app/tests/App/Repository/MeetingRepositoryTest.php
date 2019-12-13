<?php


namespace Meeting\Test\App\Repository;

use DateTime;
use Meeting\Infrastructure\Persistence\MeetingRepository;
use Meeting\Domain\Meeting;
use Meeting\Domain\Room;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;
use Meeting\Domain\ValueObject\Room\RoomName;
use Meeting\Domain\ValueObject\Room\RoomUid;
use Meeting\Domain\ValueObject\User\UserName;
use Meeting\Domain\ValueObject\User\UserUid;
use PHPUnit\Framework\TestCase;

class MeetingRepositoryTest extends TestCase
{
    public function testSave()
    {
        $repository = new MeetingRepository();
        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        );

        $this->assertTrue($repository->save($meeting));

        $this->assertTrue($repository->isMeetingExists(
            new Room(new RoomUid('1'), new RoomName('407')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        ));

        $this->assertTrue($repository->isParticipantBusy(
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        ));

        $this->assertFalse($repository->isParticipantBusy(
            new User(new UserUid('2'), new UserName('Alexander')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        ));

        $this->assertFalse($repository->isMeetingExists(
            new Room(new RoomUid('1'), new RoomName('407')),
            new DateTime('+2 day'),
            new DateTime('+3 day')
        ));

        $this->assertFalse($repository->isMeetingExists(
            new Room(new RoomUid('2'), new RoomName('408')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        ));

        $this->assertFalse($repository->isParticipantBusy(
            new User(new UserUid('3'), new UserName('Sergey')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        ));

        $this->assertFalse($repository->isParticipantBusy(
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+2 day'),
            new DateTime('+3 day')
        ));
    }
}