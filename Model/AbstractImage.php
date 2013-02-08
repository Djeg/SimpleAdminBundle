<?php namespace Belton\SimpleAdminBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gregwar\ImageBundle\Image as GregwarImage;
use Exception;

/**
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractImage {

	CONST UPLOAD_DIR = 'images/upload';

	/**
	 * @var string $imageName
	 */
	protected $imageName;

	/**
	 * @var string $extension
	 */
	protected $imageExtension;

	/**
	 * @var integer $imageUniqId
	 */
	protected $imageUniqId;

	/**
	 * @var string $imageFullName
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $imageFullName;

	/**
	 * @var array $cropper
	 */
	protected $cropper;

	/**
	 * @var Symfony\Component\HttpFoundation\File\UploadedFile
	 * @Assert\File()
	 */
	protected $file;

	/**
	 * This method return the actual entoty id
	 * 
	 * @return integer
	 */
	abstract public function getId();

	/**
	 * This method return the max width for this image
	 * 
	 * @return integer
	 */
	abstract public function getMaxWidth();

	/**
	 * This method return the max height for this image
	 * 
	 * @return integer
	 */
	abstract public function getMaxHeight();

	/**
	 * Get image name
	 * 
	 * @return string
	 */
	public function getImageName(){

		return $this->imageName;
	}

	/**
	 * Set the Image Name
	 * 
	 * @param string $name
	 * 
	 * @return void
	 */
	public function setImageName($name){
		if($this->getId() !== null){
			$uniqId = uniqid();
			rename($this->getImageAbsolutePath(), $this->getUploadedDirectoryPath().'/'.
				$name.'.'.$uniqId.'.'.$this->imageExtension);
			$this->imageUniqId = $uniqId;
		}
		$this->imageName = $name;
		$this->imageFullName = $this->getImageFullName();
	}

	/**
	 * Get the ilage extension
	 * 
	 * @return string
	 */
	public function getImageExtension(){

		return $this->imageExtension;
	}

	/**
	 * Set the image extension
	 * 
	 * @param string $newExtension
	 */
	public function setImageExtension($newExtension){
		$this->imageExtension = $newExtension;
	}

	/**
	 * Get the image uniq id
	 * 
	 * @return integer
	 */
	public function getImageUniqId(){
		if($this->imageUniqId === null){
			$this->imageUniqId = uniqid();
		}

		return $this->imageUniqId;
	}

	/**
	 * Get the image path
	 * 
	 * @return string
	 */
	public function getImageFullName(){
		if($this->imageName !== null and 
			$this->imageExtension !== null){
			$this->imageFullName = $this->getImageName()
				.'.'.$this->getImageUniqId()
				.'.'.$this->getImageExtension();
		}

		return $this->imageFullName;
	}

	/**
	 * Set the image full name
	 * 
	 * @param string $fullName
	 */
	public function setImageFullName($fullName){
		$exploder = explode('.', $fullName);
		if(count($exploder) == 3){
			$this->imageName = $exploder[0];
			$this->imageUniqId = $exploder[1];
			$this->imageExtension = $exploder[2];
		}
		$this->imageFullName = $fullName;
	}

	/**
	 * Return the cropper informations
	 * 
	 * @return array
	 */
	public function getCropper(){
		return $this->cropper;
	}

	/**
	 * Set the cropper informations
	 * 
	 * @param array/string $cropInfos
	 */
	public function setCropper($cropInfo){
		// Test if it's a string
		if(is_string($cropInfo)){
			if( ($cropInfo = json_decode($cropInfo, true)) === null){
				throw new Exception('Cropping informations must be a valid JSON string or a valid Array');
			}
		} else {
			if(!is_array($cropInfo)){
				throw new Exception('Cropping informations must be a valid JSON string or a valid Array');
			}
		}

		// Test the array values :
		$keys = ['x', 'x2', 'y', 'y2', 'w', 'h'];
		foreach($keys as $k){
			if(!isset($cropInfo[$k])){
				throw new Exception('Cropping informations must have the following keys : x, y, x2, y2, w, h');
			}
		}

		// cropInfo is OK ;)
		$this->cropper = $cropInfo;
	}

	/**
	 * Get the file
	 * 
	 * @return Symfony\Component\HttpFoundation\File\UploadedFile
	 */
	public function getFile(){

		return $this->file;
	}

	/**
	 * Set a file
	 * 
	 * @param UploadedFile $fileUploaded
	 */
	public function setFile(UploadedFile $fileUploaded){
		$this->file = $fileUploaded;
		$this->imageExtension = $this->file->guessExtension();
	}

	/**
	 * Return the Image Absolute Path
	 * 
	 * @return string
	 */
	public function getImageAbsolutePath(){
		if(!is_dir($this->getUploadedDirectoryPath())){
			mkdir($this->getUploadedDirectoryPath());
		}
		if(null === $this->getImageFullName()){
			return;
		}

		return $this->getUploadedDirectoryPath().'/'.$this->getImageFullName();
	}

	/**
	 * Return the uploaded directory path
	 * 
	 * @return string
	 */
	public function getUploadedDirectoryPath(){

		return __DIR__.'/../../../../web/'.AbstractImage::UPLOAD_DIR;
	}

	/**
	 * Return the image web path
	 * 
	 * @return string
	 */
	public function getImagePath(){
		if(null === $this->getImageFullName()){
			return;
		}
		return AbstractImage::UPLOAD_DIR.'/'.$this->getImageFullName();
	}

	/**
	 * Method to execute on the load
	 * 
	 */
	public function onLoad(){
		if($this->imageFullName !== null){
			$this->setImageFullName($this->imageFullName);
		}
	}

	/**
	 * Method to execute after the object deletion
	 */
	public function onDelete(){
		if($this->getImageAbsolutePath() !== null){
			unlink($this->getImageAbsolutePath());
		}
	}


	/**
	 * Upload the image into the corect folder with the corect
	 * crop.
	 */
	public function upload(){
		if(null === $this->file){
			return;
		}

		// Move the uploaded file
		$this->file->move($this->getUploadedDirectoryPath(), $this->getImageFullName());
		// Destroy the file :
		unset($this->file);

		// Now crop the image and resiez it :
		$image = GregwarImage::open($this->getImageAbsolutePath());
		if($this->cropper !== null){
			$image->crop($this->cropper['x'], $this->cropper['y'],
				$this->cropper['w'], $this->cropper['h']);
		}
		$image->cropResize($this->getMaxWidth(), $this->getMaxHeight())
			->save($this->getImageAbsolutePath());

	}

}