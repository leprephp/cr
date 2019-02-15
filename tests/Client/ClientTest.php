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

namespace Lepre\Cr\Tests\Client;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Query\QueryBuilder;
use Lepre\Cr\Client\Client;
use Lepre\Cr\CredentialsInterface;
use Lepre\Cr\Exception\DatabaseException;
use Lepre\Cr\Exception\NodeTypeNotFoundException;
use Lepre\Cr\NodeType;
use Lepre\Cr\Query\NodesQuery;
use Lepre\Cr\Tests\AssertionsTrait;
use Lepre\Cr\Tests\DatabaseTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lepre\Cr\Client\Client
 */
class ClientTest extends TestCase
{
    use AssertionsTrait;
    use DatabaseTrait;

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->closeConnection();
    }

    /**
     * Tests that the `getUserId` method internally calls the credentials object.
     */
    public function testGetUserId()
    {
        $credentials = $this->createCredentialsMock();
        $credentials->method('getUserId')->willReturn(123);

        $client = $this->createClient(null, $credentials);

        $this->assertEquals(123, $client->getUserId());
    }

    /**
     * Tests that the `getNodeType` method returns a `NodeType`.
     */
    public function testGetNodeType()
    {
        $client = $this->createClient();
        $nodeType = $client->getNodeType(1);

        $this->assertIsPageNodeType($nodeType);
    }

    /**
     * Tests that the `getNodeType` method throws an exception if the node type does not exist.
     */
    public function testGetNodeTypeNotFound()
    {
        $this->expectException(NodeTypeNotFoundException::class);

        $client = $this->createClient();
        $client->getNodeType(1000);
    }

    /**
     * Tests that the `getNodeType` method throws an exception when there are database problems.
     */
    public function testGetNodeTypeWhenTheDatabaseThrowsAnException()
    {
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('An error occurred on fetching node type "1"');

        /** @var Connection|\PHPUnit\Framework\MockObject\MockObject $connection */
        $connection = $this->createMock(Connection::class);
        $connection->expects($this->once())
            ->method('executeQuery')
            ->willThrowException(new DBALException());

        $client = $this->createClient($connection);
        $client->getNodeType(1);
    }

    public function testGetNodeTypes()
    {
        $client = $this->createClient();
        $nodeTypes = $client->getNodeTypes();

        $this->assertCount(1, $nodeTypes);
        $this->assertInstanceOf(NodeType::class, $nodeTypes[0]);
        $this->assertIsPageNodeType($nodeTypes[0]);
    }

    public function testGetNodeTypesWhenTheDatabaseThrowsAnException()
    {
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('An error occurred on fetching node types');

        /** @var Connection|\PHPUnit\Framework\MockObject\MockObject $connection */
        $connection = $this->createMock(Connection::class);
        $connection->expects($this->once())
            ->method('executeQuery')
            ->willThrowException(new DBALException());

        $client = $this->createClient($connection);
        $client->getNodeTypes();
    }

    public function testFindNodes()
    {
        $client = $this->createClient();
        $nodes = $client->findNodes($client->createNodesQuery());

        $this->assertCount(3, $nodes);
        $this->assertIsHomePage($nodes[0]);
    }

    public function testFindNodesWhenTheDatabaseThrowsAnException()
    {
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('An error occurred on finding nodes');

        /** @var QueryBuilder|\PHPUnit\Framework\MockObject\MockObject $queryBuilder */
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('execute')->willThrowException(new DBALException());

        /** @var NodesQuery|\PHPUnit\Framework\MockObject\MockObject $nodesQuery */
        $nodesQuery = $this->createMock(NodesQuery::class);
        $nodesQuery->method('getQuery')->willReturn($queryBuilder);

        /** @var Connection|\PHPUnit\Framework\MockObject\MockObject $connection */
        $connection = $this->createMock(Connection::class);

        $client = $this->createClient($connection);
        $client->findNodes($nodesQuery);
    }

    /**
     * @param Connection           $connection
     * @param CredentialsInterface $credentials
     * @return Client
     */
    private function createClient(Connection $connection = null, CredentialsInterface $credentials = null): Client
    {
        if (null === $connection) {
            $connection = $this->getConnection();
        }

        if (null === $credentials) {
            $credentials = $this->createCredentialsMock();
        }

        return new Client($connection, $credentials);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|CredentialsInterface
     */
    private function createCredentialsMock(): CredentialsInterface
    {
        return $this->createMock(CredentialsInterface::class);
    }
}
