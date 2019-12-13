<?php


namespace Meeting\App\Service;


use Meeting\App\Service\DataTransferObject\MeetingDTO;
use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;

// TODO: Move to Meeting\App namespace
class ScheduleService
{
    /** @var \Meeting\Domain\Schedule */
    protected $schedule;

    /** @var \Meeting\App\UidGeneratorInterface */
    protected $uuidGenerator;


    public function createMeeting(MeetingDTO $meetingDTO)
    {
        // TODO: retrieval should be realized here, not in DTO
        $meetingDTO->setUid(MeetingUid::create($this->uuidGenerator));
        $meeting = $meetingDTO->retrieval();

        try {
            $this->schedule->addMeeting($meeting);
        } catch (DomainException $domainException) {

        }
        // TODO Specify exceptions for better workaround it
        // TODO Send notifications

        return $meeting;
    }

    public function cancelMeeting(MeetingUid $meetingUid) : bool
    {
        try {
            $meeting = $this->schedule->findMeeting($meetingUid);
            $this->schedule->cancelMeeting($meeting);
        } catch (DomainException $domain_exception) {
            return false;
        }

        // TODO Specify exceptions for better workaround it
        // TODO Send notifications

        return true;
    }

    public function updateMeetingParticipants(MeetingUid $meetingUid, MeetingParticipantsDTO $meetingParticipantsDTO) : bool
    {
        // Not needed? MeetingParticipants no longer an object
        // updateMeetingParticipants does not returns object - no returning object in here either

        $meeting_participants = $meetingParticipantsDTO->retrieval();
        try {
            $meeting = $this->schedule->findMeeting($meetingUid);
            $this->schedule->updateMeetingParticipants($meeting, $meeting_participants);
        } catch (DomainException $domainException) {
            return false;
        }

        // TODO Specify exceptions for better workaround it
        // TODO Send notifications

        return true;
    }
}
