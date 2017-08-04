<?php

namespace FosUserUtil\Model;

class DefaultUser extends AbstractUser
{
    // Type may be \Ramsey\Uuid\Uuid
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
