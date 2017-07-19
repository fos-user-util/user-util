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
        if (array_key_exists('roles', $a)) :
            $a['roles'] = json_encode($a['roles']);
        endif;
        if (array_key_exists('usernameCanonical', $a)) :
            $a['username_canonical'] = $a['usernameCanonical'];
            unset($a['usernameCanonical']);
        endif;
        if (array_key_exists('emailCanonical', $a)) :
            $a['email_canonical'] = $a['emailCanonical'];
            unset($a['emailCanonical']);
        endif;
        if (array_key_exists('plainPassword', $a)) :
            $a['plain_password'] = $a['plainPassword'];
            unset($a['plainPassword']);
        endif;
        if (array_key_exists('lastLogin', $a)) :
            $a['last_login'] = $a['lastLogin'];
            unset($a['lastLogin']);
        endif;
        if (array_key_exists('confirmationToken', $a)) :
            $a['confirmation_token'] = $a['confirmationToken'];
            unset($a['confirmationToken']);
        endif;
        if (array_key_exists('passwordRequestedAt', $a)) :
            $a['password_requested_at'] = $a['passwordRequestedAt'];
            unset($a['passwordRequestedAt']);
        endif;
        if (array_key_exists('groups', $a) and $a['groups'] == null) :
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
