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
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadTestUsers implements FixtureInterface, ContainerAwareInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * Sets the container.
	 *
	 * @param ContainerInterface|null $container A ContainerInterface instance or null
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
		/**
		 * @var UserManagerInterface $userManager
		 */
		$userManager = $this->container->get('fos_user.user_manager');

		/**
		 * @var User $user
		 */
        $user = $userManager->createUser();
        $user->setFirstName("test");
        $user->setLastName("test");
        $user->setGender(0);
        $user->setBirthday(new \DateTime());
        $user->setUsername('user');
        $user->setEmail('test@gmail.com');
        $user->setPlainPassword('test');
		$user->setEnabled(true);

		$userManager->updateUser($user);

        $manager->persist($user);
        $manager->flush();

		/**
		 * @var User $admin
		 */
		$admin = $userManager->createUser();
        $admin->setFirstName("test");
        $admin->setLastName("test");
        $admin->setGender(0);
        $admin->setBirthday(new \DateTime());
        $admin->setUsername('admin');
        $admin->setEmail('test2@gmail.com');
        $admin->setPlainPassword('test');
        $admin->setRoles(array(User::ROLE_ADMIN));
		$user->setEnabled(true);

		$userManager->updateUser($admin);

        $manager->persist($admin);
        $manager->flush();

		/**
		 * @var User $superAdmin
		 */
		$superAdmin = $userManager->createUser();
        $superAdmin->setFirstName("test");
        $superAdmin->setLastName("test");
        $superAdmin->setGender(0);
        $superAdmin->setBirthday(new \DateTime());
        $superAdmin->setUsername('super');
        $superAdmin->setEmail('test3@gmail.com');
        $superAdmin->setPlainPassword('test');
        $superAdmin->setRoles(array(User::ROLE_SUPER_ADMIN));
		$user->setEnabled(true);

		$userManager->updateUser($superAdmin);

        $manager->persist($superAdmin);
        $manager->flush();

    }
}