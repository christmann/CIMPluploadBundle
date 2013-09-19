<?php

namespace CIM\PluploadBundle\Twig;

/**
 * Class FileSizeExtension
 * @package CIM\PluploadBundle\Twig
 * @author Patrick Bußmann <patrick.bussmann@christmann.info>
 */
class FileSizeExtension extends \Twig_Extension
{
	/**
	 * {@inheritDoc}
	 */
	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('getMaxUploadSize', array($this, 'getMaxUploadSize')),
		);
	}

	/**
	 * @return string the max upload size for files
	 */
	public function getMaxUploadSize()
	{
		return ini_get('upload_max_filesize');
	}

	/**
	 * @return string the extension name
	 */
	public function getName()
	{
		return 'cim.plupload.twig.filesize';
	}
}
