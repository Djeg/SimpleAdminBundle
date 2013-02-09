<?php namespace Belton\SimpleAdminBundle\Model\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Belton\SimpleAdminBundle\Form\Type\ImageType;
use Belton\SimpleAdminBundle\Form\Type\CropType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

abstract class AbstractImageCropType extends AbstractType {

	/**
	 * @{inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
		// Merge default crop options with the options.
		if(isset($options['croppper'])){
			$this->cropOptions = $this->getCropOptions() + $options['cropper'];
		} else {
			$this->cropOptions = $this->getCropOptions();
		}
		// Build field :
		$builder->add('file', 'image', array('label' => 'Image', 'required' => false));
		$builder->add('imageName', 'text', array('required' => false));
		$builder->add('cropper', 'crop', array('cropper' => $this->cropOptions, 'required' => false));
	}

	/**
	 * Add a crop option
	 * 
	 * @param string $optionName
	 * @param mixed  $value
	 * 
	 * @return this
	 */
	public function setCropOption($optionName, $value){
		$this->cropOptions[$optionName] = $value;

		return $this;
	}

	/**
	 * Test if a crop option is alwas set
	 * 
	 * @param string $optionName
	 * 
	 * @return boolean
	 */
	public function hasCropOption($optionName){
		return isset($this->cropOptions[$optionName]);
	}

	/**
	 * Return the form name
	 * 
	 * @return string
	 */
	public function getName(){
		return 'belton_simpleadminbundle_imagecrop';
	}

	/**
	 * Defined the crop options for all childs objects
	 * 
	 * @return array
	 */
	abstract public function getCropOptions();

}