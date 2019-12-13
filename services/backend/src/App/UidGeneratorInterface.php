<?php


namespace Meeting\App;


interface UidGeneratorInterface
{
    /**
     * @return string
     */
    public function createUid();
}