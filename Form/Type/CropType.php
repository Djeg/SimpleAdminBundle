<?php namespace Belton\SimpleAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfoný\Component\Form\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * This form type create a special hidden files operatig with some
 * javascript stuff for create cropper resizement effetc
 * 
 * @author david jegat <david.jegat@gmail.com>
 */
class CropType extends AbstractType {

	/**
	 * Get the defaults options
	 * 
	 * @param array $options
	 */
	public function getDefaultOptions(array $options){
		return array(
			'cropper' => array()
		) + $options;
	}

	/**
	 * @{inheritdoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options){
		$view->vars['crop_options'] = json_encode($options['cropper']);
	}

	/**
	 * @{inheritdoc}
	 */
	public function getParent(){
		return 'hidden';
	}

	/**
	 * @{inheritdoc}
	 */
	public function getName(){
		return 'crop';
	}

}