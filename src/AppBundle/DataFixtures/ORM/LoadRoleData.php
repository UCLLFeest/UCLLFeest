<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\User;
use AppBundle\Entity\Role;

/**
 * Class LoadRoleData
 * This class is a Doctrine fixture used to initialize the database with default roles
 * @package AppBundle\DataFixtures\ORM
 */
class LoadRoleData implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$superAdmin = new Role();
		$superAdmin->setName(User::ROLE_SUPER_ADMIN);
		$superAdmin->setLocked(true);
		$superAdmin->setMandatory(true);

		$manager->persist($superAdmin);

		$manager->flush();

		$superAdmin->setRequiredRole($superAdmin);

		$manager->persist($superAdmin);

		$manager->flush();

		$adminRole = new Role();
		$adminRole->setName(User::ROLE_ADMIN);
		$adminRole->setRequiredRole($superAdmin);
		$adminRole->setMandatory(true);

		$manager->persist($adminRole);

		$defaultRole = new Role();
		$defaultRole->setName(User::ROLE_DEFAULT);
		$defaultRole->setLocked(true);
		$defaultRole->setMandatory(true);

		$manager->persist($defaultRole);

		$manager->flush();
	}
}