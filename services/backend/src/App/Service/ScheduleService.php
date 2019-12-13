<?php


namespace Meeting\App\Service;


use Meeting\App\DataTransferObject\MeetingDTO;
use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\Exception\Meeting\MeetingAlreadyCanceledException;
use Meeting\Domain\Exception\Meeting\MeetingAlreadyOverException;
use Meeting\Domain\Exception\Meeting\MeetingHasNoParticipantsException;
use Meeting\Domain\Exception\Meeting\MeetingNotExistsException;
use Meeting\Domain\Exception\User\UserNameInvalidException;
use Meeting\Domain\Exception\User\UserNotActiveException;
use Meeting\Domain\Exception\User\ParticipantNotUserException;
use Meeting\Domain\Exception\User\ParticipantsNotAvaliableException;
use Meeting\Domain\Exception\Room\RoomNotAvaliableException;
use Meeting\Domain\Exception\User\UserStatusInvalidException;
use Meeting\Domain\Exception\User\UserUidInvalidException;
use Meeting\Domain\Meeting;
use Meeting\Domain\Repository\MeetingRepositoryInterface;
use Meeting\Domain\Schedule;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\Meeting\MeetingUid;
use Exception;

class ScheduleService
{
    /** @var \Meeting\Domain\Schedule */
    protected $schedule;

    /** @var \Meeting\App\UidGeneratorInterface */
    protected $uuidGenerator;

    /**
     * @param \Meeting\App\DataTransferObject\MeetingDTO $meetingDTO
     *
     * @return \Meeting\Domain\Meeting|null
     */
    public function createMeeting(MeetingDTO $meetingDTO) : ?Meeting
    {
        try {
            $meeting = new Meeting(
                MeetingUid::create($this->uuidGenerator),
                $meetingDTO->getRoom(),
                $meetingDTO->getCreator(),
                $meetingDTO->getStartsAt(),
                $meetingDTO->getEndsAt()
            );
        } catch (DomainException $domainException) {
            return null;
        } catch (Exception $exception) {
            return null;
        }

        $meeting->addParticipants($meetingDTO->getParticipants());

        try {
            $this->schedule->addMeeting($meeting);
        } catch (RoomNotAvaliableException $domainException) {
            return null;
        } catch (ParticipantsNotAvaliableException $domainException) {
            return null;
        }
        // TODO Create meeting notifications

        return $meeting;
    }

    /**
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingUid $meetingUid
     *
     * @return bool
     */
    public function cancelMeeting(MeetingUid $meetingUid) : bool
    {
        try {
            $this->schedule->cancelMeeting($meetingUid);
        } catch (MeetingNotExistsException $exception) {
            return false;
        } catch (MeetingAlreadyCanceledException $domainException) {
            return false;
        }

        // TODO Cancel meeting notifications

        return true;
    }

    /**
     * @param \Meeting\Domain\ValueObject\Meeting\MeetingUid $meetingUid
     * @param array                                          $meetingParticipantsDTO
     *
     * @return bool
     */
    public function updateMeetingParticipants(MeetingUid $meetingUid, array $meetingParticipantsDTO) : bool
    {
        $meetingParticipants = [];
        foreach ($meetingParticipantsDTO as $meetingParticipantDTO) {
            try {
                $meetingParticipants[] = new User($meetingParticipantDTO['uid'], $meetingParticipantDTO['name']);
            } catch (UserUidInvalidException | UserNameInvalidException | UserStatusInvalidException  $userInvalidException) {
                return false;
            } catch (Exception $exception) {
                return false;
            }
        }
        try {
            $meeting = $this->schedule->findMeeting($meetingUid);
            if ($meeting) {
                $this->schedule->updateMeetingParticipants($meeting, $meetingParticipants);
            }
        } catch (MeetingHasNoParticipantsException $domainException) {
            return false;
        } catch (MeetingAlreadyOverException $domainException) {
            return false;
        } catch (UserNotActiveException $domainException) {
            return false;
        } catch (ParticipantNotUserException $domainException) {
            return false;
        }
        // TODO Updated participants notifications

        return true;
    }
}
