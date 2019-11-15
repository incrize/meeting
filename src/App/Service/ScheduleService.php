<?php


namespace Meeting\App\Service;


use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\Meeting;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;

class ScheduleService
{
    /** @var \Meeting\Domain\Schedule */
    protected $schedule;

    /** @var \Meeting\App\UidGeneratorInterface */
    protected $uuidGenerator;

    public function createMeeting(MeetingDTO $meetingDTO)
    {
        // TODO Convert MeetingDTO to Meeting
        // TODO Generate UUID
        // TODO Validate Meeting

        try {
            $this->schedule->addMeeting();
        } catch (DomainException $domainException) {

        }

        // TODO Send notifications
        // TODO return Result Object
    }

    public function cancelMeeting(MeetingUid $meetingUid)
    {
        $meeting = $this->schedule->findMeeting($meetingUid);

        // TODO Validate

        try {
            $this->schedule->cancelMeeting($meeting);
        } catch (DomainException $domainException) {

        }

        // TODO Send notifications
        // TODO return Result Object
    }

    public function updateMeetingParticipants(MeetingUid $meetingUid, MeetingParticipantsDTO $meetingParticipantsDTO)
    {
        $meeting = $this->schedule->findMeeting($meetingUid);
        // TODO Validate

        try {
            $this->schedule->updateMeetingParticipants($meeting);
        } catch (DomainException $domainException) {

        }

        // TODO Send notifications
        // TODO return Result Object
    }
}