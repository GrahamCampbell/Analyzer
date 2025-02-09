# Analyzer

Analyzer was created by, and is maintained by [Graham Campbell](https://github.com/GrahamCampbell), and checks if referenced classes really exist. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Analyzer/releases), [security policy](https://github.com/GrahamCampbell/Analyzer/security/policy), [license](LICENSE), [code of conduct](.github/CODE_OF_CONDUCT.md), and [contribution guidelines](.github/CONTRIBUTING.md).

![Banner](https://user-images.githubusercontent.com/2829600/71477090-0ea3e100-27e0-11ea-985c-6f0886f30fd9.png)

<p align="center">
<a href="https://github.com/GrahamCampbell/Analyzer/actions?query=workflow%3ATests"><img src="https://img.shields.io/github/actions/workflow/status/GrahamCampbell/Analyzer/tests.yml?label=Tests&style=flat-square" alt="Build Status"></img></a>
<a href="https://github.styleci.io/repos/98643173"><img src="https://github.styleci.io/repos/98643173/shield" alt="StyleCI Status"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square" alt="Software License"></img></a>
<a href="https://packagist.org/packages/graham-campbell/analyzer"><img src="https://img.shields.io/packagist/dt/graham-campbell/analyzer?style=flat-square" alt="Packagist Downloads"></img></a>
<a href="https://github.com/GrahamCampbell/Analyzer/releases"><img src="https://img.shields.io/github/release/GrahamCampbell/Analyzer?style=flat-square" alt="Latest Version"></img></a>
</p>


## Installation

This version requires [PHP](https://www.php.net/) 8.1-8.4 and supports [PHPUnit](https://phpunit.de/) 10-12.

| Analyzer | PHPUnit 6          | PHPUnit 7          | PHPUnit 8          | PHPUnit 9          | PHPUnit 10         | PHPUnit 11         | PHPUnit 12         |
|----------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|--------------------|
| 1.1      | :white_check_mark: | :x:                | :x:                | :x:                | :x:                | :x:                | :x:                |
| 2.4      | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x:                | :x:                | :x:                |
| 3.1      | :x:                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :x:                | :x:                | :x:                |
| 4.2      | :x:                | :x:                | :x:                | :white_check_mark: | :white_check_mark: | :x:                | :x:                |
| 5.0      | :x:                | :x:                | :x:                | :x:                | :white_check_mark: | :white_check_mark: | :white_check_mark: |

To get the latest version, simply require the project using [Composer](https://getcomposer.org/):

```bash
$ composer require "graham-campbell/analyzer:^5.0" --dev
```


## Security

If you discover a security vulnerability within this package, please send an email to security@tidelift.com. All security vulnerabilities will be promptly addressed. You may view our full security policy [here](https://github.com/GrahamCampbell/Analyzer/security/policy).


## License

Analyzer is licensed under [The MIT License (MIT)](LICENSE).


## For Enterprise

Available as part of the Tidelift Subscription

The maintainers of `graham-campbell/analyzer` and thousands of other packages are working with Tidelift to deliver commercial support and maintenance for the open source dependencies you use to build your applications. Save time, reduce risk, and improve code health, while paying the maintainers of the exact dependencies you use. [Learn more.](https://tidelift.com/subscription/pkg/packagist-graham-campbell-analyzer?utm_source=packagist-graham-campbell-analyzer&utm_medium=referral&utm_campaign=enterprise&utm_term=repo)
