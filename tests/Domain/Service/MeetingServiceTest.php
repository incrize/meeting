<?php


namespace Meeting\Tests\Domain\Service;


use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\Meeting;
use Meeting\Domain\Room;
use Meeting\Domain\Service\MeetingService;
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

class MeetingServiceTest extends TestCase
{
    protected $meetingRepository;

    public function testCreateMeetingWithUnavailableRoom()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Room is not available');

        $service = $this->createService();

        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus(), new \DateTime(), new \DateTime()),
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
            new MeetingParticipants([
                new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
                new User(new UserUid('2'), new UserName('Alexander'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
            ]),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        );
        $this->getMeetingRepository()->save($meeting);

        $service->createMeeting(
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus(), new \DateTime(), new \DateTime()),
            new MeetingParticipants([
                new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
                new User(new UserUid('2'), new UserName('Alexander'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
            ]),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        );
    }

    public function testCreateMeetingWithBusyParticipant()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Some participant is not available');

        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus(), new \DateTime(), new \DateTime()),
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
            new MeetingParticipants([
                new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
                new User(new UserUid('2'), new UserName('Alexander'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
            ]),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        );
        $this->getMeetingRepository()->save($meeting);

        $service = $this->createService();

        $service->createMeeting(
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
            new Room(new RoomUid('2'), new RoomName('408'), '', RoomStatus::createOpenStatus(), new \DateTime(), new \DateTime()),
            new MeetingParticipants([
                new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
                new User(new UserUid('2'), new UserName('Alexander'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
            ]),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        );
    }

    public function testCreateMeeting()
    {
        $service = $this->createService();

        $meeting = $service->createMeeting(
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
            new Room(new RoomUid('2'), new RoomName('408'), '', RoomStatus::createOpenStatus(), new \DateTime(), new \DateTime()),
            new MeetingParticipants([
                new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
                new User(new UserUid('2'), new UserName('Alexander'), UserStatus::createActiveStatus(), new \DateTime(), new \DateTime()),
            ]),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        );

        $this->assertInstanceOf(Meeting::class, $meeting);

        $this->assertTrue($this->getMeetingRepository()->isMeetingExists($meeting->getRoom(), $meeting->getStartsAt(), $meeting->getEndsAt()));
    }

    protected function createService()
    {
        $uidGenerator = new UidGenerator();

        return new MeetingService($uidGenerator, $this->getMeetingRepository());
    }

    protected function getMeetingRepository()
    {
        if ($this->meetingRepository !== null) {
            return $this->meetingRepository;
        }

        return $this->meetingRepository = new MeetingRepository();
    }
}


class MeetingRepository extends \Meeting\Infrastructure\Persistence\MeetingRepository
{

}

class UidGenerator extends \Meeting\App\UidGenerator
{

}