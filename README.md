# Security bundle

[![Package version](https://img.shields.io/packagist/v/norsys/security-bundle.svg?style=flat-square)](https://packagist.org/packages/norsys/security-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/norsys/security-bundle.svg?style=flat-square)](https://packagist.org/packages/norsys/security-bundle)
[![Build Status](https://img.shields.io/travis/M6Web/ApiExceptionBundle/master.svg?style=flat-square)](https://travis-ci.org/M6Web/ApiExceptionBundle)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/norsys/security-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/norsys/security-bundle/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/norsys/security-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/norsys/security-bundle/?branch=master)
[![License](https://img.shields.io/packagist/l/norsys/security-bundle.svg?style=flat-square)](https://packagist.org/packages/norsys/security-bundle)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1849fe8f-e14c-424c-bc3c-f96ad2a7650f/big.png)](https://insight.sensiolabs.com/projects/1849fe8f-e14c-424c-bc3c-f96ad2a7650f)

Bundle that provides security tools.

Installation
============

Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require norsys/security-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Norsys\SecurityBundle\NorsysSecurityBundle(),
            // ...
        );
        // ...
    }
    // ...
}
```

HTTPS
-----

This bundle provide a system to redirect all requests http to https

To enable https redirect system :

```yaml
norsys_security:
    https_redirect: 
        enabled: true # default false
```

Proxy
-----

This bundle provides a listener to set trusted proxies from environment variable.


```yaml
norsys_security:
    proxy:
        enabled: true # default false
        env_variable_name: 'TRUSTED_PROXIES_LIST' # default 'TRUSTED_PROXIES'
        env_variable_separator: ';' # default ','
        trusted_header_set: 'HEADER_FORWARDED' # default 'HEADER_X_FORWARDED_ALL'
```

Coming Soon
-----------

Coming soon system display a 302 redirect page with coming soon message to all requests.

To configure system :

```yaml
norsys_security:
    coming_soon: 
        enabled: true # default false
        template: ::coming_soon.html.twig # default NorsysSecurityBundle::coming_soon.html.twig
        allowed_ips: ['245.187.56.58', '190.85.134.50'] # default []
```

## Credits
Developped with :heart: by [Norsys](https://www.norsys.fr/)

## License

This project is licensed under the [MIT license](LICENSE).
