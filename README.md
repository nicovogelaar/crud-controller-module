CRUD controller module
=======

Introduction
------------

This CRUD controller module is intended to easily create controllers with CRUD functionality.

The module works also very good with the [paginator module](https://github.com/nicovogelaar/paginator-module).

Usage
------------
* [Example](https://github.com/nicovogelaar/crud-controller-module/blob/master/docs/example.md)

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2)

Installation
------------

#### Install with composer

```sh
./composer.phar require nicovogelaar/crud-controller-module
#when asked for a version, type "*".
```

#### Enable module

Enable the module in your `application.config.php` file.


```php
<?php
return array(
    'modules' => array(
        // ...
        'Nicovogelaar\CrudController',
    ),
    // ...
);
```
