<?php


namespace Meeting\Tests\Domain\ValueObject\Meeting;


use Meeting\Domain\Exception\DomainException;
use Meeting\Domain\User;
use Meeting\Domain\ValueObject\Meeting\MeetingParticipants;
use Meeting\Domain\ValueObject\User\UserName;
use Meeting\Domain\ValueObject\User\UserStatus;
use Meeting\Domain\ValueObject\User\UserUid;
use PHPUnit\Framework\TestCase;

class MeetingParticipantsTest extends TestCase
{
    public function testEmptyParticipants()
    {
        $this->expectException(DomainException::class);
        new MeetingParticipants([]);
    }

    public function testNotActiveParticipants()
    {
        $this->expectException(DomainException::class);

        new MeetingParticipants([
            new User(new UserUid('test'), new UserName('name'), UserStatus::createFiredStatus(), new \DateTime(), new \DateTime())
        ]);
    }

    public function testHas()
    {
        $meetingParticipants = new MeetingParticipants([
            $this->createUser('user1', UserStatus::createActiveStatus()),
            $this->createUser('user2', UserStatus::createActiveStatus())
        ]);

        $this->assertTrue($meetingParticipants->has($this->createUser('user1', UserStatus::createActiveStatus())));
        $this->assertFalse($meetingParticipants->has($this->createUser('user3', UserStatus::createActiveStatus())));

        foreach ($meetingParticipants as $user) {
            $this->assertInstanceOf(User::class, $user);
        }

        $meetingParticipants->rewind();
        $this->assertTrue($meetingParticipants->current()->getUid()->isEqual(new UserUid('user1')));
        $this->assertTrue($meetingParticipants->next()->getUid()->isEqual(new UserUid('user2')));
    }

    protected function createUser($id, $status)
    {
        return new User(new UserUid($id), new UserName('name'), $status, new \DateTime(), new \DateTime());
    }
}