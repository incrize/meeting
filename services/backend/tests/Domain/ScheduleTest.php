<?php

namespace Meeting\Tests\Domain;

use Meeting\Domain\Exception\Meeting\MeetingAlreadyCanceledException;
use Meeting\Domain\Exception\Meeting\MeetingAlreadyOverException;
use Meeting\Domain\Exception\Meeting\MeetingHasNoParticipantsException;
use Meeting\Domain\Exception\Meeting\MeetingNotExistsException;
use Meeting\Domain\Exception\Room\RoomNotAvaliableException;
use Meeting\Domain\Exception\User\ParticipantsNotAvaliableException;
use Meeting\Domain\Meeting;
use Meeting\Domain\Room;
use Meeting\Domain\Schedule;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\Meeting\MeetingStatus;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;
use Meeting\Domain\ValueObject\Room\RoomName;
use Meeting\Domain\ValueObject\Room\RoomUid;
use Meeting\Domain\ValueObject\User\UserName;
use Meeting\Domain\ValueObject\User\UserUid;
use DateTime;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    protected $meetingRepository;

    public function testAddMeetingWithUnavailableRoom()
    {
        $this->expectException(RoomNotAvaliableException::class);
        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        );

        $schedule = new Schedule($this->getMeetingRepository());

        $schedule->addMeeting($meeting);

        $schedule->addMeeting(new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('2'), new UserName('Petr')),
            new DateTime('+1 day'),
            new DateTime('+2 day')

        ));
    }

    public function testAddMeetingWithBusyParticipant()
    {
        $this->expectException(ParticipantsNotAvaliableException::class);

        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        );

        $schedule = new Schedule($this->getMeetingRepository());

        $meeting->addParticipant(new User(new UserUid('2'), new UserName('Petr')));

        $schedule->addMeeting($meeting);

        $schedule->addMeeting(new Meeting(
            new MeetingUid('2'),
            new Room(new RoomUid('2'), new RoomName('408')),
            new User(new UserUid('2'), new UserName('Petr')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        ));
    }

    public function testFindMeeting()
    {
        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        );

        $schedule = new Schedule($this->getMeetingRepository());

        $schedule->addMeeting($meeting);

        $this->assertEquals(null, $schedule->findMeeting(new MeetingUid('2')));
        $this->assertEquals($meeting, $schedule->findMeeting(new MeetingUid('1')));

    }

    public function testCancelMeeting()
    {
        $this->expectException(MeetingAlreadyCanceledException::class);

        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        );

        $meeting->setStatus(MeetingStatus::createCanceledStatus());

        $schedule = new Schedule($this->getMeetingRepository());

        $schedule->addMeeting($meeting);

        $schedule->cancelMeeting(new MeetingUid('1'));
    }

    public function testCancelNotExistingMeeting()
    {
        $this->expectException(MeetingNotExistsException::class);

        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        );

        $meeting->setStatus(MeetingStatus::createActiveStatus());

        $schedule = new Schedule($this->getMeetingRepository());

        $schedule->addMeeting($meeting);

        $schedule->cancelMeeting(new MeetingUid('2'));
    }

    public function testEmptyMeetingParticipants()
    {
        $this->expectException(MeetingHasNoParticipantsException::class);

        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime('+1 day'),
            new DateTime('+2 day')
        );

        $schedule = new Schedule($this->getMeetingRepository());

        $schedule->addMeeting($meeting);

        $schedule->updateMeetingParticipants($meeting, []);
    }

    public function testUpdateParticipantsOfPastMeeting()
    {
        $this->expectException(MeetingAlreadyOverException::class);

        $meeting = new Meeting(
            new MeetingUid('1'),
            new Room(new RoomUid('1'), new RoomName('407')),
            new User(new UserUid('1'), new UserName('Ivan')),
            new DateTime(),
            new DateTime()
        );

        $schedule = new Schedule($this->getMeetingRepository());

        $schedule->addMeeting($meeting);

        $schedule->updateMeetingParticipants(
            $meeting,
            [
                new User(new UserUid('2'), new UserName('Petr')),
                new User(new UserUid('3'), new UserName('Viktor')),
            ]
        );

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