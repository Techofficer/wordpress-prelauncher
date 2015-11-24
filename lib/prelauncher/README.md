# Prelauncher

Pre-launch website builder which helps new online store owners to easily build a viral pre-launch website and start referral prelaunch campaign. No coding skills required.


The design of this library was heavily influenced by [Httpful](https://github.com/nategood/httpful).


## Requirements

- [PHP](http://www.php.net) >= 5.3 **with** [cURL](http://www.php.net/manual/en/curl.installation.php)
- [RESTful](https://github.com/matthewfl/restful) >= 1.0.x
- [Httpful](https://github.com/nategood/httpful) >= 0.2.x

## Issues

Please use appropriately tagged github [issues](https://github.com/techofficer/prelauncher-php/issues) to request features or report bugs.

## Installation

You can install using [composer](#composer) or from [source](#source). Note that Prelauncher is [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) compliant:

### Composer

If you don't have Composer [install](http://getcomposer.org/doc/00-intro.md#installation) it:

    $ curl -s https://getcomposer.org/installer | php

Require prelauncher in your `composer.json`:

```javascript
{
    "require": {
        "prelauncher/prelauncher": "1.*"
    }
}
```


Refresh your dependencies:

```bash
$ php composer.phar update
```


Then make sure to `require` the autoloader and initialize all:

```php
<?php
require(__DIR__ . '/vendor/autoload.php');

\Httpful\Bootstrap::init();
\RESTful\Bootstrap::init();
\Prelauncher\Bootstrap::init();
...
```

### Source

Download [Httpful](https://github.com/nategood/httpful) source:

```bash
$ curl -s -L -o httpful.zip https://github.com/nategood/httpful/zipball/v0.2.3;
$ unzip httpful.zip; mv nategood-httpful* httpful; rm httpful.zip
```

Download [RESTful](https://github.com/matthewfl/restful) source:

```bash
$ curl -s -L -o restful.zip https://github.com/matthewfl/restful/zipball/master;
$ unzip restful.zip; mv matthewfl-restful* restful; rm restful.zip
```

Download the Prelauncher source:

```bash
$ curl -s -L -o prelauncher.zip https://github.com/techofficer/prelauncher-php/zipball/master
$ unzip prelauncher.zip; mv prelauncher-prelauncher-php-* prelauncher; rm prelauncher.zip
```


And then `require` all bootstrap files:

```php
<?php
require(__DIR__ . "/httpful/bootstrap.php")
require(__DIR__ . "/restful/bootstrap.php")
require(__DIR__ . "/prelauncher/bootstrap.php")

\Httpful\Bootstrap::init();
\RESTful\Bootstrap::init();
\Prelauncher\Bootstrap::init();
...
```


## Usage

See https://prelauncher.info/docs for guides and documentation.


## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Write your code **and [tests](#testing)**
4. Commit your changes (`git commit -am 'Add some feature'`)
5. Push to the branch (`git push origin my-new-feature`)
6. Create new pull request


