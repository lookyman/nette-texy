Nette Texy
==========

Quick and easy [Texy](https://texy.info/) integration into you [Nette Framework](https://nette.org/) projects.

[![Build Status](https://travis-ci.org/lookyman/nette-texy.svg?branch=master)](https://travis-ci.org/lookyman/nette-texy)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lookyman/nette-texy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lookyman/nette-texy/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/lookyman/nette-texy/badge.svg?branch=master)](https://coveralls.io/github/lookyman/nette-texy?branch=master)
[![Downloads](https://img.shields.io/packagist/dt/lookyman/nette-texy.svg)](https://packagist.org/packages/lookyman/nette-texy)
[![Latest stable](https://img.shields.io/packagist/v/lookyman/nette-texy.svg)](https://packagist.org/packages/lookyman/nette-texy)


Installation
------------

Install using the [Composer](http://getcomposer.org/):

```sh
$ composer require lookyman/nette-texy
```

Enable the extension in your [NEON](https://ne-on.org/) config:

```yml
extensions:
	texy: lookyman\NetteTexy\DI\TexyExtension
```

Use the Texy macro or helper in your [Latte](https://latte.nette.org/) templates like this:

```smarty
{texy}some content{/texy}
{$var |texy}
```
