<?php

namespace Tisseo\TidBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use CanalTP\SamCoreBundle\DataFixtures\ORM\UserTrait;

class FixturesUser extends AbstractFixture implements OrderedFixtureInterface
{
    use UserTrait;

    private $users = array(
        array(
            'id'        => null,
            'username'  => 'utilisateur TID',
            'firstname' => 'utilisateur',
            'lastname'  => 'TID',
            'email'     => 'user-tid@tisseo.fr',
            'password'  => 'tid',
            'roles'     => array('role-user-tid'),
            'customer'  => 'customer-tisseo'
        ),
        array(
            'id'        => null,
            'username'  => 'admin TID',
            'firstname' => 'admin',
            'lastname'  => 'TID',
            'email'     => 'admin-tid@tisseo.fr',
            'password'  => 'admin',
            'roles'     => array('role-admin-tid'),
            'customer'  => 'customer-tisseo'
        ),
        array(
            'id'        => null,
            'username'  => 'user_tid_iv',
            'firstname' => 'user tid iv',
            'lastname'  => 'TID',
            'email'     => 'user_tid_iv@tisseo.fr',
            'password'  => 'admin',
            'roles'     => array('role-user-tid-iv'),
            'customer'  => 'customer-tisseo'
        ),
        array(
            'id'        => null,
            'username'  => 'user_tid_sig',
            'firstname' => 'user tid sig',
            'lastname'  => 'TID',
            'email'     => 'user_tid_sig@tisseo.fr',
            'password'  => 'admin',
            'roles'     => array('role-user-tid-sig'),
            'customer'  => 'customer-tisseo'
        )
    );



    public function load(ObjectManager $om)
    {
        foreach ($this->users as $userData) {
            $userEntity = $this->createUser($om, $userData);
        }
        $om->flush();
    }

    /**
    * {@inheritDoc}
    */
    public function getOrder()
    {
        return 5;
    }
}
