<?php


namespace Meeting\Domain\Service;


interface UidGeneratorInterface
{
    /**
     * @return string
     */
    public function createUid();
}