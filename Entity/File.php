<?php
namespace CIM\PluploadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Contains informations about files
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class File
{
	/**
	 * Name
	 *
	 * @ORM\Column(name="name", type="string", length=255, nullable=true)
	 *
	 * @var string $name
	 */
	private $name;

	/**
	 * Path to the file
	 *
	 * @ORM\Column(name="path", type="string", length=255, nullable=true)
	 *
	 * @var string $path
	 */
	private $path;

	/**
	 * Mime Type
	 *
	 * @ORM\Column(name="mimeType", type="string", length=255, nullable=true)
	 *
	 * @var string $path
	 */
	private $mimeType;

	/**
	 * Size
	 *
	 * @ORM\Column(type="integer", nullable=true)
	 * @var int
	 */
	private $size;

	/**
	 * Contains the file information for the upload
	 *
	 * @var \Symfony\Component\HttpFoundation\File\UploadedFile
	 */
	private $file;

	/**
	 * Remove file
	 *
	 * @var bool
	 */
	private $removeFile;

	/**
	 * Returns the file id
	 *
	 * @abstract
	 * @return int
	 */
	abstract public function getId();

	/**
	 * Moves the file to the upload dir
	 *
	 * @ORM\PreUpdate()
	 * @ORM\PrePersist()
	 */
	public function upload()
	{
		if ($this->removeFile)
		{
			if ($this->getPath())
			{
				$this->removeUpload();
			}
		}

		if (!($this->file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile))
		{
			return;
		}

		$this->setMimeType($this->file->getMimeType());
		$this->setName($this->file->getClientOriginalName());
		if ($this->getPath())
		{
			$this->removeUpload();
		}
		$this->setPath(uniqid() . '.' . $this->file->guessExtension());
		$this->setSize($this->file->getSize());

		// you must throw an exception here if the file cannot be moved
		// so that the entity is not persisted to the database
		// which the UploadedFile move() method does automatically
		$this->file->move($this->getUploadRootDir(), $this->path);

		unset($this->file);
	}

	/**
	 * Removes the file, if the entity was deleted
	 *
	 * @ORM\PreRemove()
	 */
	public function removeUpload()
	{
		$file = $this->getFullPath();
		if ($file && file_exists($file))
		{
			unlink($file);
		}
	}

	/**
	 * returns the full path to the file
	 *
	 * @return null|string
	 */
	public function getFullPath()
	{
		return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
	}

	/**
	 * Returns the root file dir
	 *
	 * @return string
	 */
	public function getUploadRootDir()
	{
		return 'media';
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set path
	 *
	 * @param string $path
	 */
	public function setPath($path)
	{
		$this->path = $path;
	}

	/**
	 * Get path
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Set mimeType
	 *
	 * @param string $mimeType
	 */
	public function setMimeType($mimeType)
	{
		$this->mimeType = $mimeType;
	}

	/**
	 * Get mimeType
	 *
	 * @return string
	 */
	public function getMimeType()
	{
		return $this->mimeType;
	}

	/**
	 * Set size
	 *
	 * @param integer $size
	 */
	public function setSize($size)
	{
		$this->size = $size;
	}

	/**
	 * Get size
	 *
	 * @return integer
	 */
	public function getSize()
	{
		return $this->size;
	}

	/**
	 * @param boolean $removeFile
	 */
	public function setRemoveFile($removeFile)
	{
		$this->removeFile = $removeFile;
	}

	/**
	 * @return boolean
	 */
	public function getRemoveFile()
	{
		return $this->removeFile;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
	 */
	public function setFile($file)
	{
		$this->file = $file;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\File\UploadedFile
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * Returns if the file is an image
	 *
	 * @return bool
	 */
	public function isImage()
	{
		return stripos($this->getMimeType(), "image/") !== false;
	}

	/**
	 * Returns the file extension
	 *
	 * @return mixed|string
	 */
	public function getExtension()
	{
		return pathinfo($this->getPath(), PATHINFO_EXTENSION);
	}

	/**
	 * Returns the filename
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this->getName();
	}
}