<?php

namespace Meeting\App\ValueObject\User;

class UserOrderByClause
{
    /** @var \Meeting\App\ValueObject\User\UserField */
    protected $field;

    /** @var \Meeting\App\ValueObject\User\OrderByDirection */
    protected $direction;

}