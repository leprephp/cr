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

use Lepre\Cr\SimpleCredentials;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lepre\Cr\SimpleCredentials
 */
class SimpleCredentialsTest extends TestCase
{
    public function testGetUserId()
    {
        $this->assertEquals(123, (new SimpleCredentials(123))->getUserId());
    }
}
