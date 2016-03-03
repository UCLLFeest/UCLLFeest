<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

/**
 * This class is a Doctrine repository class for Role.
 *
 * @package AppBundle\Entity
 */
class RoleRepository extends EntityRepository
{
	/**
	 * Creates a query builder whose query returns an array of roles that the user does not have and that the given admin can add.
	 * @param User $admin
	 * @param User $user
	 * @return QueryBuilder
	 */
	public function getUnassignedRolesQuery(User $admin, User $user)
	{
		$qb = $this->createQueryBuilder('r');
		$exp = $qb->expr();

		$qb
			->leftJoin('AppBundle:Role', 'r2', Expr\Join::WITH, 'r.requiredRole = r2')
			->leftJoin('AppBundle:User', 'u', Expr\Join::WITH, 'u = :admin')
			->where(
				$exp->andX(
					$exp->notIn('r.name', $user->getRoles()),
					$exp->orX(
						$exp->isNull('r.requiredRole'),
						$exp->in('r2.name', $admin->getRoles())
					)
				)
			)
			->setParameter('admin', $admin);

		return $qb;
	}
}
