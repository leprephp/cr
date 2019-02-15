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

use Lepre\Cr\Language;
use Lepre\Cr\Node;
use Lepre\Cr\NodeType;

/**
 * AssertionsTrait
 */
trait AssertionsTrait
{
    /**
     * @param Node $node
     */
    private function assertIsHomePage(Node $node): void
    {
        $this->assertSame(1, $node->getId());
        $this->assertSame('Home Page', $node->getTitle());
        $this->assertSame('', $node->getSlug());
        $this->assertSame('/', $node->getPath());
        $this->assertSame([], $node->getProperties());

        $this->assertIsItalian($node->getLanguage());
        $this->assertIsPageNodeType($node->getNodeType());
    }

    /**
     * @param Language $language
     */
    private function assertIsItalian(Language $language)
    {
        $this->assertSame(1, $language->getId());
        $this->assertSame('it_IT', $language->getName());
    }

    /**
     * @param NodeType $nodeType
     */
    private function assertIsPageNodeType(NodeType $nodeType)
    {
        $this->assertSame(1, $nodeType->getId());
        $this->assertSame('page', $nodeType->getName());
    }
}
