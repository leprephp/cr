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

use Lepre\Cr\Client\IdentityMap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lepre\Cr\Client\IdentityMap
 */
class IdentityMapTest extends TestCase
{
    /**
     * @var IdentityMap
     */
    private $map;

    protected function setUp(): void
    {
        $this->map = new IdentityMap();
    }

    /**
     * Tests that the initial state is empty.
     */
    public function testInitialState()
    {
        $this->assertFalse($this->map->has('key'));
        $this->assertNull($this->map->get('key'));
    }

    /**
     * Tests a simple procedure.
     */
    public function testSimple()
    {
        $this->assertFalse($this->map->has('key'));

        $entity = new \stdClass();
        $this->map->set('key', $entity);

        $this->assertTrue($this->map->has('key'));
        $this->assertSame($entity, $this->map->get('key'));
    }

    /**
     * Tests that the identity map returns always the same entity for the same identifier.
     */
    public function testGetReturnsAlwaysTheSameEntity()
    {
        $entity = new \stdClass();
        $this->map->set('key', $entity);

        $this->assertSame($this->map->get('key'), $this->map->get('key'));
    }
}
