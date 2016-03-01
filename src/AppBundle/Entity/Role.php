<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RoleRepository")
 * @ORM\Table(name="app_roles")
 * @UniqueEntity("name", message="A role with that name already exists")
 */
class Role
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 * @var string $name Name of this role.
	 */
	private $name;

	/**
	 * @ORM\ManyToOne(targetEntity="Role")
	 * @ORM\JoinColumn(name="requiredRole_id", referencedColumnName="id", nullable=true)
	 * @var Role $requiredRole Optional. The role an admin must have to set this role on users.
	 */
	private $requiredRole;

	/**
	 * @ORM\Column(type="boolean")
	 * @var boolean $locked If true, this role cannot be used directly.
	 */
	private $locked = false;

	/**
	 * @ORM\Column(type="boolean")
	 * @var bool $mandatory Whether this role is mandatory. Mandatory roles cannot be removed.
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
	 * @return Role The role that an admin must have to set this role on users.
	 */
	public function getRequiredRole()
	{
		return $this->requiredRole;
	}

	/**
	 * @param mixed $role The role that an admin must have to set this role on users. Can be null. Cannot be this role.
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
	 * @return bool $locked Whether this role is locked
	 */
	public function isLocked()
	{
		return $this->locked;
	}

	/**
	 * @param bool $locked Sets whether this role is locked.
	 * @return Role $this
	 */
	public function setLocked($locked)
	{
		$this->locked = $locked;

		return $this;
	}

	/**
	 * @return bool $mandatory Whether this role is mandatory.
	 */
	public function isMandatory()
	{
		return $this->mandatory;
	}

	/**
	 * @param bool $mandatory Whether this role is mandatory.
	 * @return Role $this
	 */
	public function setMandatory($mandatory)
	{
		$this->mandatory = $mandatory;

		return $this;
	}
}