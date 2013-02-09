<?php namespace Belton\SimpleAdminBundle\Model;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use FOS\UserBundle\Model\UserInterface;
use Belton\SimpleAdminBundle\Model\AbstractImage;

/**
 * This class subscribe the postRemove/Update/Delete and load event
 * for the abstractImage object kind
 * 
 * @author david jegat <david.jegat@gmail.com>
 */
class ImageSubscriber implements EventSubscriber {

	/**
	 * Attach events
	 * 
	 * @return array
	 */
	public function getSubscribedEvents(){
		return array(
			Events::postLoad,
			Events::postRemove,
			Events::postUpdate,
			Events::postPersist
		);
	}

	/**
	 * On the PostLoad
	 * 
	 * @param LifecycleEventArgs $args
	 */
	public function postLoad(LifecycleEventArgs $args){
		$entity = $args->getEntity();
		if($entity instanceof AbstractImage){
			$entity->onLoad();
		}
	}

	/**
	 * On the postRemove
	 * 
	 * @param LicecycleEventArgs $args
	 */
	public function postRemove(LifecycleEventArgs $args){
		$entity = $args->getEntity();
		if($entity instanceof AbstractImage){
			$entity->onDelete();
		}
	}

	/**
	 * On the post update
	 * 
	 * @param LifecycleEventArgs $args
	 */
	public function postUpdate(LifecycleEventArgs $args){
		$this->mergeUploadedFile($args);
	}

	/**
	 * On the post persist
	 * 
	 * @param LifecycleEventArgs $args
	 */
	public function postPersist(LifecycleEventArgs $args){
		$this->mergeUploadedFile($args);
	}


	/**
	 * This method is used for postUpdate and postPersist
	 * 
	 * @param LifecycleEventArgs $args
	 */
	private function mergeUploadedFile(LifecycleEventArgs $args){
		$entity = $args->getEntity();
		if($entity instanceof AbstractImage){
			$entity->upload();
		}
	}
}