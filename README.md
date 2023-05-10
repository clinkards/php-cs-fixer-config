# Clinkards - Shared Coding Standard

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/clinkards/php-cs-fixer-config/blob/master/LICENSE)

This is a shared config for [FriendsOfPHP/PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) used in our projects.

## Installation

Add the package to your composer.json as a dev dependency.
```json
"require-dev": {
    "clinkards/php-cs-fixer-config": "dev-main"
}
```
You will also need to add the repository to your composer.json.
```json
"repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:/clinkards/php-cs-fixer-config.git"
    }
],

```

## Usage

Create a `.php-cs-fixer.dist.php` configuration file in the root of your project.

```php
<?php

$config = new Clinkards\PhpCsFixerConfig\Config();
$config->getFinder()
    ->in(__DIR__ . "/src")
    ->in(__DIR__ . "/tests");

return $config;
```

### Adding or overloading rules

If you need to override rules or add new rules you can pass them to the config constructor.

```php
<?php

$config = new Clinkards\PhpCsFixerConfig\Config([
    'psr0' => false,
    'psr4' => false,
]);
$config->getFinder()
    ->in(__DIR__ . "/src")
    ->in(__DIR__ . "/tests");

return $config;
```


## Security

If you discover a security vulnerability, please send an e-mail to Matt Davis via matt.davis@clinkard.co.uk. All security vulnerabilities will be promptly addressed.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
