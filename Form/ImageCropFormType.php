<?php namespace Belton\SimpleAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Belton\SimpleAdminBundle\Form\Type\ImageType;
use Belton\SimpleAdminBundle\Form\Type\CropType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ImageCropFormType extends AbstractType {

	/**
	 * @var array $options
	 * @access private
	 */
	private $cropOptions;

	/**
	 * @{inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder->add('file', new ImageType(), array('label' => 'Image', 'required' => false));
		$builder->add('imageName', 'text', array('required' => false));
		$builder->add('cropper', new CropType(), array('cropper' => $this->cropOptions, 'required' => false));
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
	 * Construct the ImageCropFormType
	 * 
	 * @param array $cropOptons = array()
	 */
	public function __construct(array $cropOptions = array()){
		$this->cropOptions = $cropOptions;
	}

}