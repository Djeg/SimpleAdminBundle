<?php

namespace Belton\SimpleAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class PanelController extends Controller
{

	/**
	 * Render the admin menu
	 * 
	 * @Template()
	 */
	public function menuAction(){
		return array();
	}

	/**
	 * Display the login panel
	 * 
	 * @Template()
	 */
	public function loginPanelAction(){
		return array();
	}

    /**
     * @Route("/", name="belton_simple_admin_panel")
     * @Template()
     */
    public function indexAction(){
        return array();
    }

    /**
     * @Route("/{registration_id}/", name="belton_simple_admin_list")
     * @Template()
     */
    public function listAction($registration_id){
    	$manager = $this->get('belton_simple_admin.manager');
    	if(!$manager->isRegister($registration_id) or !$manager->hasListPermision($registration_id)){
    		throw $this->createNotFoundException();
    	}
    	$list = $manager->getList($registration_id, $this->get('request')->query);
    	return array(
            'list' => $list,
            'register' => $registration_id
        );
    }

    /**
     * @Route("/{registration_id}/persist/{id}", requirements={"id" = "\d+"}, defaults={"id"=0}, name="belton_simple_admin_persist")
     * @Template()
     */
    public function persistAction($registration_id, $id){
        $manager = $this->get('belton_simple_admin.manager');
        if(!$manager->isRegister($registration_id) or 
            !$manager->hasEditPermision($registration_id)){
            throw $this->createNotFoundException();
        }

        if($manager->isFormService($registration_id)){
            $type = $this->get($manager->getFormService($registration_id));
        } else {
            $type = $manager->getForm($registration_id);
        }

        if($id == 0){
            $entity = $manager->getEmptyEntity($registration_id);
        } else {
            $entity = $manager->getEntity($registration_id, $id);
        }

        $form = $this->createForm($type, $entity);
        if($this->get('request')->isMethod('POST')){
            $form->bind($this->get('request'));
            if($form->isValid()){
                $em = $this->getDoctrine()
                    ->getEntityManager();
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('SUCCESS', $entity->getSuccessRecordMessage());
                return $this->redirect($this->generateUrl('belton_simple_admin_list', array('registration_id' => $registration_id)));
            }
        }

        return array(
            'register' => $registration_id,
            'form' => $form->createView(),
            'id' => $id
        );
    }

    /**
     * @Route("/{registration_id}/delete/{id}", requirements={"id"="\d+"}, name="belton_simple_admin_delete")
     */
    public function deleteAction($registration_id, $id){
        $manager = $this->get('belton_simple_admin.manager');
        if(!$manager->isRegister($registration_id) or 
            !$manager->hasDeletePermision($registration_id)){
            throw $this->createNotFoundException('Not gallery exists');
        }

        $em = $this->getDoctrine()->getEntityManager();
        $entry = $manager->getRepository($registration_id)
            ->find($id);

        if($this->get('request')->isMethod('POST')){
            $em->remove($entry);
            $em->flush();

            $this->get('session')->getFlashBag()->add('SUCCESS', $entry->getSuccessDeleteMessage());
            return $this->redirect($this->generateUrl('belton_simple_admin', array('registration_id' => $registration_id)));
        }else {
            throw $this->createNotFoundException('Not post');
        }
    }
}
