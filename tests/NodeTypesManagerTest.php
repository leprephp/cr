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

use Lepre\Cr\Client\ClientInterface;
use Lepre\Cr\Exception\NodeTypeNotFoundException;
use Lepre\Cr\NodeType;
use Lepre\Cr\NodeTypesManager;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lepre\Cr\NodeTypesManager
 */
class NodeTypesManagerTest extends TestCase
{
    /**
     * @var ClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $client;

    /**
     * @var NodeTypesManager
     */
    private $nodeTypesManager;

    protected function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->nodeTypesManager = new NodeTypesManager($this->client);
    }

    public function testGetNodeTypes()
    {
        $this->client->expects($this->once())->method('getNodeTypes')->willReturn([]);

        $this->assertEquals([], $this->nodeTypesManager->getNodeTypes());
    }

    public function testGetNodeType()
    {
        $nodeType = new NodeType();
        $this->client->expects($this->once())->method('getNodeType')->with(123)->willReturn($nodeType);

        $this->assertSame($nodeType, $this->nodeTypesManager->getNodeType(123));
    }

    public function testIfGetNodeTypeThrowsNotFoundException()
    {
        $this->expectException(NodeTypeNotFoundException::class);

        $this->client->expects($this->once())->method('getNodeType')
            ->with(123)
            ->willThrowException(new NodeTypeNotFoundException());

        $this->nodeTypesManager->getNodeType(123);
    }
}
