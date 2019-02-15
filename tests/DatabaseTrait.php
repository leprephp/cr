<?php

/*
 * This file is part of the Lepre package.
 *
 * (c) Daniele De Nobili <danieledenobili@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Lepre\Cr\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;

/**
 * DatabaseTrait
 */
trait DatabaseTrait
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @return Connection
     */
    protected function getConnection(): Connection
    {
        if (null === $this->connection) {
            if (!extension_loaded('pdo')) {
                $this->markTestSkipped('The "pdo" PHP extension must be installed to run this unit test.');
            }

            if (!class_exists('PDO') || !in_array('mysql', \PDO::getAvailableDrivers())) {
                $this->markTestSkipped('The "pdo_mysql" driver must be installed to run this unit test.');
            }

            try {
                $this->connection = DriverManager::getConnection([
                    'driver'   => 'pdo_mysql',
                    'host'     => $GLOBALS['DB_HOST'],
                    'user'     => $GLOBALS['DB_USER'],
                    'password' => $GLOBALS['DB_PASS'],
                    'dbname'   => $GLOBALS['DB_NAME'],
                    'port'     => $GLOBALS['DB_PORT'],
                ]);
            } catch (DBALException $e) {
                $this->markTestSkipped($e->getMessage());
            }
        }

        return $this->connection;
    }

    /**
     * Closes the db connection if it is defined.
     *
     * Use this method inside the tearDown().
     */
    protected function closeConnection(): void
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
