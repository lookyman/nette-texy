Nette Texy!
===========

[![Build Status](https://travis-ci.org/lookyman/nette-texy.svg?branch=master)](https://travis-ci.org/lookyman/nette-texy)
[![Downloads](https://img.shields.io/packagist/dt/lookyman/nette-texy.svg)](https://packagist.org/packages/lookyman/nette-texy)
[![Latest stable](https://img.shields.io/packagist/v/lookyman/nette-texy.svg)](https://packagist.org/packages/lookyman/nette-texy)

Installation
------------

The best way to install Nette Texy! is using the [Composer](http://getcomposer.org/):

```sh
$ composer require lookyman/nette-texy:dev-master
```

Enable the extension using your neon config:

```yml
extensions:
    texy: Nette\Bridges\TexyDI\TexyExtension
```

Use the texy helper in your latte templates like this:

```smarty
{$texyContent|texy}
```

WIP
---