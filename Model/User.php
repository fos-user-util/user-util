<?php

namespace FosUserUtil\Model;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 */
abstract class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function getVars() {
        return get_object_vars($this);
    }
    // TODO: do we want this public?

    public static function objectVarsToDbArray($a) {
        // $a['roles'] = serialize($a['roles']);
        $a['roles'] = json_encode($a['roles']);
        $a['username_canonical'] = $a['usernameCanonical'];
        $a['email_canonical'] = $a['emailCanonical'];
        $a['last_login'] = $a['lastLogin'];
        $a['confirmation_token'] = $a['confirmationToken'];
        $a['password_requested_at'] = $a['passwordRequestedAt'];
        unset($a['usernameCanonical'], $a['emailCanonical'], $a['plainPassword'], $a['lastLogin'], $a['confirmationToken'], $a['passwordRequestedAt']);
        if ($a['groups'] == null) :
            unset($a['groups']);
        endif;
        return $a;
    }
    // TODO: put this in a trait.

    public function getDbVars() {
        return self::objectVarsToDbArray($this->getVars());
    }
    // TODO: put this in a trait.
    // TODO: do we want this public?
}
