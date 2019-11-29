<?php

namespace Meeting\Tests\Domain;

use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\Meeting;
use Meeting\Domain\Room;
use Meeting\Domain\Schedule;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;
use Meeting\Domain\ValueObject\Room\RoomName;
use Meeting\Domain\ValueObject\Room\RoomStatus;
use Meeting\Domain\ValueObject\Room\RoomUid;
use Meeting\Domain\ValueObject\User\UserName;
use Meeting\Domain\ValueObject\User\UserStatus;
use Meeting\Domain\ValueObject\User\UserUid;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    protected $meetingRepository;

    public function testAddMeetingWithUnavailableRoom()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Room is not available');
        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus(), new \DateTime(), new \DateTime()),
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        );

        $schedule = new Schedule($this->getMeetingRepository());

        $schedule->addMeeting($meeting);

        $schedule->addMeeting(new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus(), new \DateTime(), new \DateTime()),
            new User(new UserUid('2'), new UserName('Petr'), UserStatus::createActiveStatus()),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')

        ));
    }

    public function testAddMeetingWithBusyParticipant()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Some participant is not available');

        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407'), '', RoomStatus::createOpenStatus(), new \DateTime(), new \DateTime()),
            new User(new UserUid('1'), new UserName('Ivan'), UserStatus::createActiveStatus()),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        );

        $schedule = new Schedule($this->getMeetingRepository());

        $meeting->addParticipant(new User(new UserUid('2'), new UserName('Petr'), UserStatus::createActiveStatus()));

        $schedule->addMeeting($meeting);

        $schedule->addMeeting(new Meeting(
            new MeetingUid('2'),
            new Room(new RoomUid('2'), new RoomName('408'), '', RoomStatus::createOpenStatus(), new \DateTime(), new \DateTime()),
            new User(new UserUid('2'), new UserName('Petr'), UserStatus::createActiveStatus()),
            new \DateTime('+1 day'),
            new \DateTime('+2 day')
        ));
    }

    // TODO: test findMeeting
    // TODO: test cancelMeeting
    // TODO: test updateMeetingParticipants


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