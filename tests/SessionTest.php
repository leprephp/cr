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

use Lepre\Cr\Client\Client;
use Lepre\Cr\Exception\NodeNotFoundException;
use Lepre\Cr\Node;
use Lepre\Cr\Session;
use Lepre\Cr\SimpleCredentials;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lepre\Cr\Session
 */
class SessionTest extends TestCase
{
    use DatabaseTrait;

    /**
     * @var Session
     */
    private $session;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->session = new Session(
            new Client($this->getConnection(), new SimpleCredentials(1))
        );
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->closeConnection();
    }

    /**
     * Tests that the userId is that of the ClientInterface.
     */
    public function testGetUserId()
    {
        $this->assertSame(1, $this->session->getUserId());
    }

    /**
     * Tests the `getNode()` method returns a node.
     */
    public function testGetNode()
    {
        $this->assertInstanceOf(Node::class, $this->session->getNode(1));
    }

    /**
     * If a node does not exists, the `getNode()` method must throw a `NodeNotFoundException` exception.
     */
    public function testGetNodeNotFound()
    {
        $this->expectException(NodeNotFoundException::class);

        $this->session->getNode(999);
    }

    /**
     * Tests the `getNodeByPath()` method returns a node.
     */
    public function testGetNodeByPath()
    {
        $this->assertInstanceOf(Node::class, $this->session->getNodeByPath('/'));
    }

    /**
     * If a node does not exists, the `getNodeByPath()` method must throw a `NodeNotFoundException` exception.
     */
    public function testGetNodeByPathNotFound()
    {
        $this->expectException(NodeNotFoundException::class);

        $this->session->getNodeByPath('/does-not-exist');
    }

    /**
     * Tests that the `NodeTypesManager` is always the same object.
     */
    public function testGetNodeTypesManager()
    {
        $this->assertSame($this->session->getNodeTypesManager(), $this->session->getNodeTypesManager());
    }
}
