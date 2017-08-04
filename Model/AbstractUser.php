<?php

namespace FosUserUtil\Model;

use FOS\UserBundle\Model\User;

/**
 * User
 *
 */
abstract class AbstractUser extends User
{
    const DB_COLUMNS_TYPES = [
        'last_login' => 'datetime',
        'password_requested_at' => 'datetime',
    ];

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function setId($id)
    {
        $this->id = $id;
    }
     // provisory
     // should be added in interface and be made stable
     // or one should use the constructor and update all code to work with the new constructor interface!
     // and use new User(EMPTY_USER) instead of new User() or use the multiple constructor tric in http://php.net/manual/fr/language.oop5.decon.php#99903

    public function getVars() {
        return get_object_vars($this);
    }
    // TODO: do we want this public?

    public static function objectVarsToDbArray($a) {
        if (array_key_exists('id', $a)) :
            $a['uuid'] = $a['id']; ///////////////////////////////////
            unset($a['id']);
        endif;
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
            // not saved to DB, but not sure if it should be supressed here => suppressed in User Manager!
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
