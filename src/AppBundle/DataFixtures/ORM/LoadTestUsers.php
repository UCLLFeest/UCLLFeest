<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 1/03/2016
 * Time: 16:59
 */

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\User;
class LoadTestUsers implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName("test");
        $user->setLastName("test");
        $user->setGender(0);
        $user->setBirthday(new \DateTime());
        $user->setUsername('user');
        $user->setEmail('test@gmail.com');
        $user->setPassword('test');

        $manager->persist($user);
        $manager->flush();

        $admin = new User();
        $admin->setFirstName("test");
        $admin->setLastName("test");
        $admin->setGender(0);
        $admin->setBirthday(new \DateTime());
        $admin->setUsername('admin');
        $admin->setEmail('test2@gmail.com');
        $admin->setPassword('test');
        $admin->setRoles(array(User::ROLE_ADMIN));

        $manager->persist($admin);
        $manager->flush();

        $superAdmin = new User();
        $superAdmin->setFirstName("test");
        $superAdmin->setLastName("test");
        $superAdmin->setGender(0);
        $superAdmin->setBirthday(new \DateTime());
        $superAdmin->setUsername('super');
        $superAdmin->setEmail('test3@gmail.com');
        $superAdmin->setPassword('test');
        $superAdmin->setRoles(array(User::ROLE_SUPER_ADMIN));

        $manager->persist($superAdmin);
        $manager->flush();

    }
}