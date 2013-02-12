<?php namespace Belton\SimpleAdminBundle\Model\Entity;

interface AdministrableInterface {

	/**
	 * This mthod must return the entity primary key
	 * 
	 * @return mixed
	 */
	public function getId();

	/**
	 * Return the field that are present in the listing. The key
	 * represent the table head and the value the value !
	 * 
	 * @return array
	 */
	public function getListFields();

	/**
	 * You must implements the __toString method for enable 
	 * the traduction field/
	 * 
	 * @return string
	 */
	public function __toString();

}