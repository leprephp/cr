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

namespace Lepre\Cr;

use Doctrine\DBAL\Connection;
use Lepre\Cr\Client\Client;

/**
 * Repository
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
final class Repository implements RepositoryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function login(CredentialsInterface $credentials): SessionInterface
    {
        return new Session(
            new Client($this->connection, $credentials)
        );
    }
}
