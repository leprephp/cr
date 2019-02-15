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
use Lepre\Cr\CredentialsInterface;
use Lepre\Cr\Repository;
use Lepre\Cr\SessionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lepre\Cr\Repository
 */
class RepositoryTest extends TestCase
{
    public function testLogin()
    {
        $connection = $this->createDbConnectionMock();
        $credentials = $this->createCredentialsMock();
        $credentials->method('getUserId')->willReturn(123);

        $repository = new Repository($connection);
        $session = $repository->login($credentials);

        $this->assertInstanceOf(SessionInterface::class, $session);
        $this->assertEquals(123, $session->getUserId());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|Connection
     */
    private function createDbConnectionMock()
    {
        return $this->createMock(Connection::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|CredentialsInterface
     */
    private function createCredentialsMock()
    {
        return $this->createMock(CredentialsInterface::class);
    }
}
