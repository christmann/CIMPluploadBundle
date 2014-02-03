CIMPluploadBundle
=================

The CIMPluploadBundle adds support for plupload in Symfony2.

Installation
------------

### Step 1: Download CIMPluploadBundle using composer

Add CIMPluploadBundle in your composer.json:

```js
{
    "require": {
        "christmann/pluploadbundle": "dev-Symfony2.3"
    }
}
```
You could use "dev-Symfony2.2" for Symfony2.2 or "dev-master" for Symfony2.1.

Now download the bundle per command.

``` bash
$ php composer.phar update christmann/pluploadbundle
```

Now Composer install the bundle to your project's `vendor/CIM/PluploadBundle` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new CIM\PluploadBundle\CIMPluploadBundle(),
    );
}
```

### Step 3: Use the form type "plupload"

``` php
<?php
// src/Acme/DemoBundle/Form/DemoType.php

// ...
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// ...
	    $builder->add('image', 'plupload')
		// ...
	}
// ...
```

License
-------

This bundle and the included Plupload is under the GPLv2 license. See the complete license in the bundle:

    LICENSE

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/christmann/CIMPluploadBundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.
