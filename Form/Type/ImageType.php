<?php namespace Belton\SimpleAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * This class represent a basic class where you can
 * inherit all your image kind of form with the getParent 
 * method.
 * 
 * @author david jegat <david.jegat@ggmail.com>
 */
class ImageType extends AbstractType {

	/**
	 * @{inheritdoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options){
		$data = $form->getParent()->getData();
		if(method_exists($data, 'getImagePath')){
			$imageUrl = $data->getImagePath();
		} else {
			$imageUrl = null;
		}
		$view->vars['image_url'] = $imageUrl;
	}

	/**
	 * Defined some default options
	 * 
	 * @param array $options
	 * 
	 * @return array
	 */
	public function getDefaultOptions(array $options){
		return array(
			'attr' => array(
				'class' => 'hidden_input'
			),
			'data_class' => null
		) + $options;
	}

	/**
	 * This form inherit from the file form type
	 * 
	 * @return string
	 */
	public function getParent(){
		return 'file';
	}

	/**
	 * Return the name if this form type
	 * 
	 * @return string
	 */
	public function getName(){
		return 'image';
	}

}