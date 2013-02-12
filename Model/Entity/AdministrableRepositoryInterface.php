<?php namespace Belton\SimpleAdminBundle\Model\Entity;

use Knp\Component\Pager\Paginator;

interface AdministrableRepositoryInterface {

	/**
	 * Return a KnpPaginator object. Takes 2 parameters,
	 * the KnpPaginator service and the search session 
	 * that can be null if no search is given.
	 * 
	 * @param Knp\Component\Pager\Paginator $paginator
	 * @param array $search_field = null
	 * @param integer $page = 1
	 * 
	 * @return Knp\Component\Pager\Paginator
	 */
	public function listPaginate(Paginator $paginator, array $search_field = null, $page = 1);

}