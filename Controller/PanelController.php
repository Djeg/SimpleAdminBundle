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
     * @Route("/{registration}/", name="belton_simple_admin_list")
     * @Template()
     */
    public function listAction($registration){
    	$manager = $this->get('belton_simple_admin.manager');
    	if(!$manager->isRegister($registration) or !$manager->hasListPermision($registration)){
    		throw $this->createNotFoundException();
    	}
    	$list = $manager->getList($registration, $this->get('request')->query);
    	return array(
            'list' => $list,
            'register' => $registration
        );
    }

    /**
     * @Route("/{registration}/persist/{id}", requirements={"id" = "\d+"}, defaults={"id"=0}, name="belton_simple_admin_persist")
     * @Template()
     */
    public function persistAction($registration, $id){
        $manager = $this->get('belton_simple_admin.manager');
        if(!$manager->isRegister($registration) or 
            !$manager->hasEditPermision($registration, $id)){
            throw $this->createNotFoundException();
        }

        if($manager->isFormService($registration)){
            $type = $this->get($manager->getFormService($registration));
        } else {
            $type = $manager->getForm($registration);
        }

        if($id == 0){
            $entity = $manager->getEmptyEntity($registration);
        } else {
            $entity = $manager->getEntity($registration, $id);
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
                return $this->redirect($this->generateUrl('belton_simple_admin_list', array('registration' => $registration)));
            }
        }

        return array(
            'register' => $registration,
            'form' => $form->createView(),
            'id' => $id
        );
    }

    /**
     * @Route("/{registration}/delete/{id}", requirements={"id"="\d+"}, name="belton_simple_admin_delete")
     */
    public function deleteAction($registration, $id){
        $manager = $this->get('belton_simple_admin.manager');
        if(!$manager->isRegister($registration) or 
            !$manager->hasDeletePermision($registration)){
            throw $this->createNotFoundException('Not gallery exists');
        }

        $em = $this->getDoctrine()->getEntityManager();
        $entry = $manager->getRepository($registration)
            ->find($id);

        if($this->get('request')->isMethod('POST')){
            $em->remove($entry);
            $em->flush();

            $this->get('session')->getFlashBag()->add('SUCCESS', $entry->getSuccessDeleteMessage());
            return $this->redirect($this->generateUrl('belton_simple_admin', array('registration' => $registration)));
        }else {
            throw $this->createNotFoundException('Not post');
        }
    }
}
