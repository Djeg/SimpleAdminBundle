<?php namespace Belton\SimpleAdminBundle\Model;

interface AdministrableInterface {

	/**
	 * This mthod must return the entity primary key
	 * 
	 * @return mixed
	 */
	public function getId();

	/**
	 * This method must return the name that appear on a 
	 * Entity listing in admin.
	 * 
	 * @return string
	 */
	public function getListName();

	/**
	 * Return the field that are present in the listing. The key
	 * represent the table head and the value the value !
	 * 
	 * @return array
	 */
	public function getListFields();

	/**
	 * Return the success message if a record sounds good ;)
	 * 
	 * @return string or array for translation file
	 */
	public function getSuccessRecordMessage();

	/**
	 * Return the success message if an update sounds good ;)
	 * 
	 * @return string or array for translation file
	 */
	public function getSuccessUpdateMessage();

	/**
	 * Return the success message if a delete sounds good ;)
	 * 
	 * @return string or array for translation file
	 */
	public function getSuccessDeleteMessage();

}