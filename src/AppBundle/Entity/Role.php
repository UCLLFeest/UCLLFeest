<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * This class defines a role that users can have. It sets usage restrictions and can be set to disallow removal.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RoleRepository")
 * @ORM\Table(name="app_roles")
 * @UniqueEntity("name", message="A role with that name already exists")
 */
class Role
{
	/**
	 * @var integer id.
	 *
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string Name of this role.
	 *
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 */
	private $name;

	/**
	 * @var Role Optional. The role an admin must have to set this role on users.
	 *
	 * @ORM\ManyToOne(targetEntity="Role")
	 * @ORM\JoinColumn(name="requiredRole_id", referencedColumnName="id", nullable=true)
	 */
	private $requiredRole;

	/**
	 * @var boolean If true, this role cannot be used directly.
	 *
	 * @ORM\Column(type="boolean")
	 */
	private $locked = false;

	/**
	 * @var boolean Whether this role is mandatory. Mandatory roles cannot be removed.
	 *
	 * @ORM\Column(type="boolean")
	 */
	private $mandatory = false;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Gets the name of this role
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Sets the name of this role
	 * @param string $name
	 * @return Role $this
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Gets the role that an admin must have to set this role on users.
	 * @return Role
	 */
	public function getRequiredRole()
	{
		return $this->requiredRole;
	}

	/**
	 * Sets the role that an admin must have to set this role on users. Can be null. Cannot be this role.
	 * @param mixed $role
	 * @return Role $this
	 */
	public function setRequiredRole($role)
	{
		if($role === null || $role instanceof Role)
		{
			$this->requiredRole = $role;
		}

		return $this;
	}

	/**
	 * Returns whether this role is locked or not.
	 * @return bool
	 */
	public function isLocked()
	{
		return $this->locked;
	}

	/**
	 * Sets whether this role is locked.
	 * @param bool $locked
	 * @return Role $this
	 */
	public function setLocked($locked)
	{
		$this->locked = $locked;

		return $this;
	}

	/**
	 * Whether this role is mandatory.
	 * @return bool $mandatory
	 */
	public function isMandatory()
	{
		return $this->mandatory;
	}

	/**
	 * Whether this role is mandatory.
	 * @param bool $mandatory
	 * @return Role $this
	 */
	public function setMandatory($mandatory)
	{
		$this->mandatory = $mandatory;

		return $this;
	}
}