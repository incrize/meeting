<?php


namespace Meeting\App;


use Meeting\App\UidGeneratorInterface;
use Ramsey\Uuid\Uuid;

class UidGenerator implements UidGeneratorInterface
{
    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function createUid()
    {
        return Uuid::uuid4()->toString();
    }
}