<?php namespace Belton\SimpleAdminBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Allow to your entity to inherit from the video and directly 
 * get the good attributes  ;)
 */
class AbstractVideo {

	/**
	 * @var string $video
	 * @access protected
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