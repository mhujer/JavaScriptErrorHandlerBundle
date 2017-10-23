# JavaScriptErrorHandlerBundle
[![Build Status](https://travis-ci.org/mhujer/JavaScriptErrorHandlerBundle.svg?branch=master)](https://travis-ci.org/mhujer/JavaScriptErrorHandlerBundle)  [![Coverage Status](https://coveralls.io/repos/github/mhujer/JavaScriptErrorHandlerBundle/badge.svg?branch=dev)](https://coveralls.io/github/mhujer/JavaScriptErrorHandlerBundle?branch=dev) [![Latest Stable Version](https://poser.pugx.org/mhujer/javascript-error-handler-bundle/v/stable)](https://packagist.org/packages/mhujer/javascript-error-handler-bundle) [![License](https://poser.pugx.org/mhujer/javascript-error-handler-bundle/license)](https://packagist.org/packages/mhujer/javascript-error-handler-bundle)

It is easy to break the JavaScript in the application while doing some non-JS change. And if you don't have the browser console open, you may not notice it.

This Bundle injects a JavaScript handler, which converts JavaScript errors to `alert()`. So they can't hide in the console unnoticed.


Usage
----
1. Install the latest version with `composer require mhujer/javascript-error-handler-bundle`
2. Register the Bundle in the `AppKernel.php`:

```php
<?php

class AppKernel extends \Symfony\Component\HttpKernel\Kernel
{

	...

	public function registerBundles()
	{
		$bundles = [
			...
			new \Mhujer\JavaScriptErrorHandlerBundle\JavaScriptErrorHandlerBundle(),
		];

	}

```

Configuration
-------
The Bundle is automatically enabled only in `dev` mode (by using `kernel.debug` configuration option).

You can configure it manually by adding this to your `config.yml`:

```yaml
java_script_error_handler:
    enabled: '%kernel.debug%'
```


Requirements
------------
PHP 7.1/7.2 and Symfony 3.3+.


Author
------
[Martin Hujer](https://www.martinhujer.cz) 


Changelog
----------

### 0.1 (2017-10-23)
- initial release
