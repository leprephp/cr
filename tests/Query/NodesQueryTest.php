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

namespace Lepre\Cr\Tests\Query;

use Lepre\Cr\Node;
use Lepre\Cr\Query\NodesQuery;
use Lepre\Cr\Tests\DatabaseTrait;
use Lepre\Cr\Tests\Fixture\InvalidNode;
use Lepre\Cr\Tests\Fixture\Product;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lepre\Cr\Query\NodesQuery
 */
class NodesQueryTest extends TestCase
{
    use DatabaseTrait;

    /**
     * @var NodesQuery
     */
    private $nodesQuery;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->nodesQuery = new NodesQuery($this->getConnection());
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->closeConnection();
    }

    /**
     * The default model class is `Lepre\Cr\Node`.
     */
    public function testDefaultModelClass()
    {
        $this->assertEquals(Node::class, $this->nodesQuery->getModelClass());
    }

    /**
     * Tests the `setModelClass` & `getModelClass` methods.
     */
    public function testModelClass()
    {
        $this->nodesQuery->setModelClass(Product::class);
        $this->assertEquals(Product::class, $this->nodesQuery->getModelClass());
    }

    /**
     * Tests to set an invalid model class.
     */
    public function testInvalidModelClass()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'The class "Lepre\Cr\Tests\Fixture\InvalidNode" is not a subclass of "Lepre\Cr\Node"'
        );

        $this->nodesQuery->setModelClass(InvalidNode::class);
    }

    /**
     * Tests that `byId` method queries by id.
     */
    public function testById()
    {
        $queryBuilder = $this->nodesQuery->byId(123)->getQuery();

        $this->assertStringContainsString('node_id = :id', $queryBuilder->getSQL());
        $this->assertEquals(123, $queryBuilder->getParameter('id'));
    }

    /**
     * Tests that `byPath` method queries by path.
     */
    public function testByPath()
    {
        $queryBuilder = $this->nodesQuery->byPath('/the-path')->getQuery();

        $this->assertStringContainsString('path = :path', $queryBuilder->getSQL());
        $this->assertEquals('/the-path', $queryBuilder->getParameter('path'));
    }

    /**
     * Tests that `byId` method has precedence over `byPath`
     */
    public function testByIdExcludeByPath()
    {
        $queryBuilder = $this->nodesQuery->byId(123)->byPath('/the-path')->getQuery();

        $this->assertStringContainsString('node_id = :id', $queryBuilder->getSQL());
        $this->assertEquals(123, $queryBuilder->getParameter('id'));

        $this->assertStringNotContainsStringIgnoringCase('path = :path', $queryBuilder->getSQL());
        $this->assertNull($queryBuilder->getParameter('path'));
    }
}
