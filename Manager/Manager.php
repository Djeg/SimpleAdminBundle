<?php namespace Belton\SimpleAdminBundle\Manager;

use Twig_Extension;
use Doctrine\ORM\EntityManager;
use ReflectionClass;
use Belton\SimpleAdminBundle\Exceptions\AdminManagerException;
use Belton\SimpleAdminBundle\Model\AdministrableRepositoryInterface;
use Knp\Component\Pager\Paginator;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\ParameterBag;

class Manager extends Twig_Extension {

	/**
	 * @var array $params
	 * @access protected
	 */
	protected $params;

	/**
	 * @var array $menu
	 * @access protected
	 */
	protected $menu;

	/**
	 * @var array $repositories
	 * @access private
	 */
	protected $repositories;

	/**
	 * @var EntityManager
	 * @access protected
	 */
	protected $em;

	/**
	 * @var Symfony\Component\Security\Core\SecurityContext
	 * @access protected
	 */
	protected $security;

	/**
	 * @var array
	 * @access protected
	 */
	protected $infos;

	/**
	 * @var Paginator $paginator_service
	 * @access protected
	 */
	protected $paginator_service;

	public function test(){
		return "TEST";
	}

	/**
	 * Set the paginator Service
	 * 
	 * @param Knp\Component\Pager\Paginator
	 */
	public function setPaginator(Paginator $p){
		$this->paginator_service = $p;
	}

	/**
	 * Set the security context
	 * 
	 * @param Symfony\Component\Security\Core\SecurityContext
	 */
	public function setSecurity(SecurityContext $secu){
		$this->security = $secu;
	}

	/**
	 * Return usefull twig functions
	 * 
	 * @return array
	 */
	public function getFunctions(){
		return array(
			'has_permision_to_list' => new \Twig_Function_Method($this, 'hasListPermision'),
			'has_permision_to_create' => new \Twig_Function_Method($this, 'hasCreatePermision'),
			'has_permision_to_edit' => new \Twig_Function_Method($this, 'hasEditPermision'),
			'has_permision_to_delete' => new \Twig_Function_Method($this, 'hasDeletePermision'),
			'has_display_permision' => new \Twig_Function_Method($this, 'hasDisplayPermision'),
			'has_image' => new \Twig_Function_Method($this, 'hasImage'),
			'get_image' => new \Twig_Function_Method($this, 'getImage'),
		);
	}

	/**
	 * Returns some globals for twig
	 * 
	 * @return array
	 */
	public function getGlobals(){
		return array(
			'admin_manager' => array(
				'menu' => $this->menu,
				'registration' => $this->params,
				'website' => $this->infos['website'],
				'backlink' => $this->infos['backlink']
			)
		);
	}


	/**
	 * Return the extension name for TWIG
	 * 
	 * @return string
	 */
	public function getName(){
		return 'belton_simple_admin.manager';
	}

	/**
	 * Return a repository of an entity managed by the admin manager
	 * service. Precise at offset the identifier of the entity entry from
	 * the service.ym file.
	 * 
	 * @param string $identifier
	 * 
	 * @return EntityRepository or null
	 */
	public function getRepository($identifier){
		if(isset($this->repositories[$identifier])){
			return $this->repositories[$identifier];
		} else {
			if(isset($this->params[$identifier])){	
				if(isset($this->params[$identifier]['repository'])){

					$this->repositories[$identifier] = $this->em->getRepository($this->params[$identifier]['repository']);
					if(!$this->repositories[$identifier] instanceof AdministrableRepositoryInterface){
						throw new AdminManagerException('A repository register in the BeltonSimpleaAdminBundle must implement the 
							Belton\SimpleAdminBundle\Model\AdmistrableRepositoryInterface !');
					}

					return $this->repositories[$identifier];
				} else {
					return null;
				}
			}
			return null;
		}
	}

	/**
	 * Return a corect pagination of a register entity
	 * 
	 * @param string  $offset
	 */
	public function getList($offset, ParameterBag $query){
		if(!$r = $this->getRepository($offset)){
			throw new AdminManagerException('No entity register at '.$offset.' check your services configuration');
		}
		if($search = $query->get('search')){
			$search = json_decode($search);
		} else {
			$search = null;
		}
		if(!$page = $query->get('page')){
			$page = 1;
		} else {
			$page = (int)$page;
		}
		return $r->listPaginate($this->paginator_service, $search, $page);
	}

	/**
	 * Test if the current log user have the permission to get the 
	 * asked list
	 * 
	 * @param string $offset
	 * 
	 * @return boolean
	 */
	public function hasListPermision($offset){
		if(!$this->isRegister($offset)){
			return false;
		}

		foreach($this->params[$offset]['actions']['list'] as $role){
			if($this->security->isGranted($role)){
				return true;
			}
		}

		return false;
	}

	/**
	 * Test if the current log user have the permission to edit
	 * 
	 * @param string $offset
	 * 
	 * @return boolean
	 */
	public function hasEditPermision($offset){
		if(!$this->isRegister($offset)){
			return false;
		}

		foreach($this->params[$offset]['actions']['edit'] as $role){
			if($this->security->isGranted($role)){
				return true;
			}
		}

		return false;
	}

	/**
	 * Test if the current log user have the permission to create
	 * 
	 * @param string $offset
	 * 
	 * @return boolean
	 */
	public function hasCreatePermision($offset){
		if(!$this->isRegister($offset)){
			return false;
		}

		foreach($this->params[$offset]['actions']['create'] as $role){
			if($this->security->isGranted($role)){
				return true;
			}
		}

		return false;
	}

	/**
	 * Test if the current log user have the permission to get the 
	 * asked list
	 * 
	 * @param string $offset
	 * 
	 * @return boolean
	 */
	public function hasDeletePermision($offset){
		if(!$this->isRegister($offset)){
			return false;
		}

		foreach($this->params[$offset]['actions']['delete'] as $role){
			if($this->security->isGranted($role)){
				return true;
			}
		}

		return false;
	}

	/**
	 * Test if they are a list image
	 * 
	 * @reurn boolean
	 */
	public function hasImage($offset){
		if(!$this->isRegister($offset)){
			return false;
		}

		if(isset($this->params[$offset]['image'])){
			return true;
		}

		return false;
	}

	/**
	 * Return the list image
	 * 
	 * @return string or null
	 */
	public function getImage($offset){
		if(!$this->hasImage($offset)){
			return null;
		}
		return $this->params[$offset]['image'];
	}

	/**
	 * Return if the current user have the display permissions
	 * 
	 * @param string $offset
	 * 
	 * @return boolean
	 */
	public function hasDisplayPermision($offset){
		if(!$this->isRegister($offset)){
			return false;
		}

		foreach($this->params[$offset]['display'] as $role){
			if($this->security->isGranted($role)){
				return true;
			}
		}

		return false;
	}

	/**
	 * Test if the given registration exist
	 * 
	 * @param string $offset
	 * 
	 * @return boolean
	 */
	public function isRegister($offset){
		return isset($this->params[$offset]);
	}

	/**
	 * Test if the form is a service
	 * 
	 * @param string $offset
	 * 
	 * @return boolean
	 */
	public function isFormService($offset){
		if(!$this->isRegister($offset)){
			return null;
		}

		return isset($this->params[$offset]['form_service']);
	}

	/**
	 * Return the associated form for the given register
	 * entity
	 * 
	 * @param strig $offset
	 * 
	 * @return form or null
	 */
	public function getForm($offset){
		if(!$this->isRegister($offset)){
			return null;
		}
		return new $this->params[$offset]['form']();
	}

	/**
	 * Return the form service name
	 * 
	 * @param string $offset
	 * 
	 * @return string
	 */
	public function getFormService($offset){
		if(!$this->isRegister($offset)){
			return null;
		}
		return $this->params[$offset]['form_service'];
	}

	/**
	 * Return an empty entity object for the given register
	 * entity
	 * 
	 * @param string $offset
	 * 
	 * @return Object
	 */
	public function getEmptyEntity($offset){
		if(!$this->isRegister($offset)){
			return null;
		}

		return new $this->params[$offset]['entity_class']();
	}

	/**
	 * Return the entity with the given primary key for the given
	 * register entity.
	 * 
	 * @param string $offset
	 * @param integer $id
	 * 
	 * @return Object
	 */
	public function getEntity($offset, $id){
		if(!$this->isRegister($offset)){
			return null;
		}

		return $this->getRepository($offset)
			->find($id);
	}

	/**
	 * Construct from the Service Container with the registratio parameters
	 * 
	 * @param array $registration
	 */
	public function __construct(EntityManager $em, array $registration, array $menu, array $infos){
		$this->params = $registration;
		$this->em = $em;
		$this->menu = $menu;
		$this->infos = $infos;
		foreach($registration as $name => $infos){
			
			// test interface :
			if(isset($infos['entity_class'])){
				$ref = new ReflectionClass($infos['entity_class']);
				if(!in_array('Belton\SimpleAdminBundle\Model\AdministrableInterface', $ref->getInterfaceNames())){
					throw new AdminManagerException('An admin entity object must implement the 
						Belton\SimpleAdminBundle\Model\AdministrableInterface !');
				}
			}
		}
	}

}