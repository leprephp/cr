**🚧 This project is in early development stage and it could change significantly in the future.**

# Lepre Content Repository

[![Build Status](https://travis-ci.org/leprephp/cr.svg?branch=master)](https://travis-ci.org/leprephp/cr)
[![Coverage Status](https://coveralls.io/repos/github/leprephp/cr/badge.svg?branch=master)](https://coveralls.io/github/leprephp/cr?branch=master)

The Content Repository (CR) is the persistence layer of a CMS.

## Installation

Install the latest version with [Composer][composer]:

```
$ composer require lepre/cr
```

### Requirements

This project works with PHP 7.1+ and MySQL 5.7+

### Run tests

1. Create a MySQL database
2. Import `resources/db/mysql.sql` into your new database
3. Copy `phpunit.xml.dist` to `phpunit.xml` and set the db parameters
4. Run `composer test`

## License

This project is licensed under the MIT License. See the LICENSE file for details.

[composer]: https://getcomposer.org/
