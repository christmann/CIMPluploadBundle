# Installation

## config.yml

Create a new class with CIM\PluploadBundle\Entity\File as parent class.

	cim_plupload:
    	entity: CIM\CMSBundle\Entity\File

## form

    $builder->add('file', 'plupload', array(
        'multiple' => (true|false)
    ))
