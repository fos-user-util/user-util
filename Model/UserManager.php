<?php

/*
 * This file is inspired by one of the FOSUserBundle package.
 *
 * (c) 2017 User-util project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FosUserUtil\AbstractModel;

// use Doctrine\Common\Persistence\ObjectManager;
// use Doctrine\Common\Persistence\ObjectRepository;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager as BaseUserManager;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;
// use FosUserUtil\Model\User;

class UserManager extends BaseUserManager
{
    /*
     * @var ObjectManager
     */
    // protected $objectManager;

    /**
     * @var \RaphiaDBAL
     */
    protected $queryObject;

    /**
     * @var string
     */
    private $class;

    /**
     * Constructor.
     *
     * @param PasswordUpdaterInterface $passwordUpdater
     * @param CanonicalFieldsUpdater   $canonicalFieldsUpdater
     * @param ObjectManager            $om
     * @param string                   $class
     */
    public function __construct(PasswordUpdaterInterface $passwordUpdater, CanonicalFieldsUpdater $canonicalFieldsUpdater, 
    // ObjectManager $om, 
    \RaphiaDBAL $qo, // query object? 
    $class)
    {
        parent::__construct($passwordUpdater, $canonicalFieldsUpdater);

        $this->queryObject = $qo;
        $this->class = $class;
    }

    /*
     * @return ObjectRepository
    protected function getRepository()
    {
        return $this->queryObject>getRepository($this->getClass()); // Error:  Call to a member function getRepository() on null
    }
     */

    /**
     * {@inheritdoc}
     */
    public function deleteUser(UserInterface $user)
    {
        $this->queryObject->remove($user);
        die("delete user\n");
        // $this->objectManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        if (false !== strpos($this->class, ':')) {
            // $metadata = $this->objectManager->getClassMetadata($this->class);
            die("get class\n");
            $this->class = $metadata->getName();
        }

        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function findUserBy(array $criteria)
    {
        // dump($criteria);
        // dump(User::objectVarsToDbArray($criteria));
        // return $this->getRepository()->findOneBy($criteria);
        $a = $this->queryObject->findUniqueBy('http_user', AbstractUser::objectVarsToDbArray($criteria));
        // dump(new \DateTime($a['last_login']));
        // die($a['last_login']);
        // TODO: hide table name in an object ///////////////////////////////////////////////////////////////////////////////////////////////
        // dump($this->getClass());
        $user = $this->createUser();
        $user->setId($a['uuid']);
        $user->setUsername($a['username']);
        $user->setUsernameCanonical($a['username_canonical']);
        $user->setEmail($a['email']);
        $user->setemailCanonical($a['email_canonical']);
        $user->setEnabled($a['enabled']);
        $user->setSalt($a['salt']);
        $user->setPassword($a['password']);
        $user->setLastLogin(new \DateTime($a['last_login'])); //
        $user->setConfirmationToken($a['confirmation_token']);
        $user->setPasswordRequestedAt(new \DateTime($a['password_requested_at'])); //
        $user->setRoles(json_decode($a['roles']));
        // TODO: Ouch!
        // Should use a constructor
        // Separation between user and user manager : one should not need to know exactly all fields of the user object (cf. getDbVars for update user method)
        // dump($user);
        return $user;
        // die('find user by');
    }

    /**
     * {@inheritdoc}
     */
    public function findUsers()
    {
        // return $this->getRepository()->findAll();
        die("find users\n");
    }

    /**
     * {@inheritdoc}
     */
    public function reloadUser(UserInterface $user)
    {
        // $this->objectManager->refresh($user);
        die("reload user\n");
    }

    /**
     * {@inheritdoc}
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        // dump($user);
        $a = $user->getDbVars();
        dump($a);
        unset($a['plain_password']);
        if ((!array_key_exists('id', $a) or $a['id'] == null) and (!array_key_exists('uuid', $a) or $a['uuid'] == null)) :
            // create (insert)
            unset($a['id']);
            $this->queryObject->insert_uuid4('http_user', $a, 'uuid');
        else :
            // update
            if (array_key_exists('id', $a)) :
                $id = $a['id'];
                unset($a['id']);
            endif;
            if (array_key_exists('uuid', $a)) :
                $id = $a['uuid'];
                unset($a['uuid']);
            endif;
            // TODO: Manage the erroneous case with both 'id' and 'uuid'!
            unset($a['groups']); // TODO: make work for groups //////////////////////////////////////////////////////////
            dump($a);
            $this->queryObject->updateUniqueBy('http_user', ['uuid' => $id], $a, $user::DB_COLUMNS_TYPES);
            // die("Don\'t know how to update :-( oO9A5l4Q\n");
        endif;
        // $this->objectManager->persist($user); // needed to create user
        if ($andFlush) {
            // $this->objectManager->flush();
        }
    }
}
