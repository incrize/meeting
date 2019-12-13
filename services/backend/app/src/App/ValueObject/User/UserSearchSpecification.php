<?php

namespace Meeting\App\ValueObject\User;

class UserSearchSpecification
{
    //TODO Finish it
    protected $limit;

    protected $offset;

    /** @var \Meeting\App\ValueObject\User\UserOrderByClause */
    protected $orderBy;

    /** @var \Meeting\App\ValueObject\User\UserConditionClause */
    protected $condition;

    public function __construct() { }

}
