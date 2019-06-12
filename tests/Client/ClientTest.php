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
use Lepre\Cr\Exception\LanguageNotFoundException;
use Lepre\Cr\Exception\NodeTypeNotFoundException;
use Lepre\Cr\Language;
use Lepre\Cr\Node;
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
     * Tests that the `createNodeType` method returns a valid instance of `NodeType`.
     */
    public function testCreateNodeType()
    {
        $this->assertInstanceOf(NodeType::class, $this->createClient()->createNodeType());
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
     * Tests the identity map for the node types.
     */
    public function testNodeTypeIdentity()
    {
        $client = $this->createClient();

        $this->assertSame($client->getNodeType(1), $client->getNodeType(1));
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

    /**
     * Tests the identity map for the node types.
     */
    public function testNodeTypesIdentity()
    {
        $client = $this->createClient();
        $pageNodeType = $client->getNodeType(1);

        foreach ($client->getNodeTypes() as $nodeType) {
            if ($nodeType->getId() === 1) {
                $this->assertSame($pageNodeType, $nodeType);

                return;
            }
        }

        $this->markAsRisky();
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

    /**
     * Tests that the `createLanguage` method returns a valid instance of `Language`.
     */
    public function testCreateLanguage()
    {
        $this->assertInstanceOf(Language::class, $this->createClient()->createLanguage());
    }

    /**
     * Tests that the `getLanguage` method returns a `Language`.
     */
    public function testGetLanguage()
    {
        $client = $this->createClient();
        $language = $client->getLanguage(1);

        $this->assertIsItalian($language);
    }

    /**
     * Tests the identity map for the languages.
     */
    public function testLanguageIdentity()
    {
        $client = $this->createClient();

        $this->assertSame($client->getLanguage(1), $client->getLanguage(1));
    }

    /**
     * Tests that the `getLanguage` method throws an exception if the language does not exist.
     */
    public function testGetLanguageNotFound()
    {
        $this->expectException(LanguageNotFoundException::class);

        $client = $this->createClient();
        $client->getLanguage(1000);
    }

    /**
     * Tests that the `getLanguage` method throws an exception when there are database problems.
     */
    public function testGetLanguageWhenTheDatabaseThrowsAnException()
    {
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('An error occurred on fetching language "1"');

        /** @var Connection|\PHPUnit\Framework\MockObject\MockObject $connection */
        $connection = $this->createMock(Connection::class);
        $connection->expects($this->once())
            ->method('executeQuery')
            ->willThrowException(new DBALException());

        $client = $this->createClient($connection);
        $client->getLanguage(1);
    }

    public function testGetLanguages()
    {
        $client = $this->createClient();
        $languages = $client->getLanguages();

        $this->assertCount(1, $languages);
        $this->assertInstanceOf(Language::class, $languages[0]);
        $this->assertIsItalian($languages[0]);
    }

    /**
     * Tests the identity map for the node types.
     */
    public function testLanguagesIdentity()
    {
        $client = $this->createClient();
        $italianLanguage = $client->getLanguage(1);

        foreach ($client->getLanguages() as $language) {
            if ($language->getId() === 1) {
                $this->assertSame($italianLanguage, $language);

                return;
            }
        }

        $this->markAsRisky();
    }

    public function testGetLanguagesWhenTheDatabaseThrowsAnException()
    {
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('An error occurred on fetching languages');

        /** @var Connection|\PHPUnit\Framework\MockObject\MockObject $connection */
        $connection = $this->createMock(Connection::class);
        $connection->expects($this->once())
            ->method('executeQuery')
            ->willThrowException(new DBALException());

        $client = $this->createClient($connection);
        $client->getLanguages();
    }

    /**
     * Tests that the `createNode` method returns a valid instance of `Node`.
     */
    public function testCreateNode()
    {
        $this->assertInstanceOf(Node::class, $this->createClient()->createNode());
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
     * Tests the identity map for the nodes.
     */
    public function testNodesIdentity()
    {
        $client = $this->createClient();

        $nodesById = $client->findNodes($client->createNodesQuery()->byId(1));
        $nodesByPath = $client->findNodes($client->createNodesQuery()->byPath('/'));

        $this->assertCount(1, $nodesById);
        $this->assertCount(1, $nodesByPath);

        /**
         * @var Node $nodeById
         * @var Node $nodeByPath
         */
        $nodeById = reset($nodesById);
        $nodeByPath = reset($nodesByPath);

        $this->assertSame($nodeById, $nodeByPath);
    }

    public function testNodesInternalIdentity()
    {
        $client = $this->createClient();

        /**
         * @var Node $nodesOne
         * @var Node $nodesTwo
         * @var Node $nodesThree
         */
        $nodesOne = $client->findNodes($client->createNodesQuery()->byId(1))[0];
        $nodesTwo = $client->findNodes($client->createNodesQuery()->byId(2))[0];
        $nodesThree = $client->findNodes($client->createNodesQuery()->byId(3))[0];

        $this->assertSame($nodesOne->getNodeType(), $nodesTwo->getNodeType());
        $this->assertSame($nodesOne->getNodeType(), $nodesThree->getNodeType());
        $this->assertSame($nodesOne->getLanguage(), $nodesTwo->getLanguage());
        $this->assertSame($nodesOne->getLanguage(), $nodesThree->getLanguage());
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
