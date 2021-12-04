# JavaScriptErrorHandlerBundle
[![Build Status](https://travis-ci.org/mhujer/JavaScriptErrorHandlerBundle.svg?branch=master)](https://travis-ci.org/mhujer/JavaScriptErrorHandlerBundle)  [![Coverage Status](https://coveralls.io/repos/github/mhujer/JavaScriptErrorHandlerBundle/badge.svg?branch=master)](https://coveralls.io/github/mhujer/JavaScriptErrorHandlerBundle?branch=master) [![Latest Stable Version](https://poser.pugx.org/mhujer/javascript-error-handler-bundle/v/stable)](https://packagist.org/packages/mhujer/javascript-error-handler-bundle) [![License](https://poser.pugx.org/mhujer/javascript-error-handler-bundle/license)](https://packagist.org/packages/mhujer/javascript-error-handler-bundle)

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
The Bundle is automatically enabled only in `dev` mode (by using `kernel.debug` configuration parameter).

You can configure it manually by adding this to your `config.yml`:

```yaml
java_script_error_handler:
    enabled: true # or false
```


Requirements
------------
PHP 8.0+ and Symfony 5.4+.


Author
------
[Martin Hujer](https://www.martinhujer.cz) 


Changelog
----------

### 1.7 (2021-12-04)
- require Symfony 5.4+
- PHP 8.1 support

### 1.6 (2021-08-01)
- require PHP 8.0+
- require Symfony 5.3+

### 1.5 (2021-02-28)
- require PHP 7.4+
- require Symfony 4.4+/5.2+

### 1.4 (2019-11-24)
- Symfony 5.0 compatibility

### 1.3 (2018-11-30)
- Symfony 4.2 compatibility
- dropped support for PHP 7.1 as it is no longer supported

### 1.2 (2018-11-16)
- PHP 7.3 compatibility

### 1.1 (2018-02-08)
Fixed support for Symfony 4 and Flex

- [#3](https://github.com/mhujer/JavaScriptErrorHandlerBundle/pull/3) Symfony 4 + Flex support

### 1.0 (2017-10-29)
As we are already using the Bundle at [**@driveto**](https://github.com/driveto) and with the new Symfony container tests, I'm fine with releasing it as 1.0.

- [#1](https://github.com/mhujer/JavaScriptErrorHandlerBundle/pull/1) Script should have an ID (thanks @tomasfejfar)
- [#2](https://github.com/mhujer/JavaScriptErrorHandlerBundle/pull/2) Container tests

### 0.1 (2017-10-23)
- initial release
