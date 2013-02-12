<?php namespace Belton\SimpleAdminBundle\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Allow to your entity to inherit from the video and directly 
 * get the good attributes  ;)
 * 
 * @author david jegat <david.jegat@gmail.com>
 */
class AbstractVideo {

	/**
	 * @var string $video
	 * @access protected
	 * 
	 * @ORM\Column(name="video", type="text")
	 */
	protected $video;

	/**
	 * Return the video
	 * 
	 * @return string
	 */
	public function getVideo(){
		return $this->video;
	}

	/**
	 * Set a new video
	 * 
	 * @param string $newVideo
	 * 
	 * @return AbstractVideo
	 */
	public function setVideo($new_video){
		$this->video = $newVideo;

		return $this->video;
	}

}