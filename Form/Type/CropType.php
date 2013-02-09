<?php namespace Belton\SimpleAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * This form type create a special hidden files operatig with some
 * javascript stuff for create cropper resizement effect
 * 
 * @author david jegat <david.jegat@gmail.com>
 */
class CropType extends AbstractType {

	/**
	 * @var array $cropOptions
	 * @access private
	 */
	private $cropOptions;

	/**
	 * Get the defaults options
	 * 
	 * @param array $options
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'cropper' => $this->cropOptions
		));
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

	/**
	 * Construct the CropType form type service
	 * 
	 * @param array $cropOptions, the croppper options
	 */
	public function __construct(array $cropOptions = null){
		$this->cropOptions = $cropOptions;
	}

}