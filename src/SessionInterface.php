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

use Lepre\Cr\Exception\CRException;
use Lepre\Cr\Exception\NodeNotFoundException;
use Lepre\Cr\Query\NodesQueryInterface;

/**
 * Session
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
interface SessionInterface
{
    /**
     * @return int
     */
    public function getUserId(): int;

    /**
     * @param int $nodeId
     * @return Node
     * @throws NodeNotFoundException
     * @throws CRException
     */
    public function getNode(int $nodeId): Node;

    /**
     * @param string $path
     * @return Node
     * @throws NodeNotFoundException
     * @throws CRException
     */
    public function getNodeByPath(string $path): Node;

    /**
     * @return NodesQueryInterface
     */
    public function createNodesQuery(): NodesQueryInterface;

    /**
     * @return NodeTypesManager
     */
    public function getNodeTypesManager(): NodeTypesManager;
}
