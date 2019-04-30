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

namespace Lepre\Cr\Client;

use Lepre\Cr\Exception\DatabaseException;
use Lepre\Cr\Exception\NodeTypeNotFoundException;
use Lepre\Cr\Node;
use Lepre\Cr\NodeType;
use Lepre\Cr\Query\NodesQueryInterface;

/**
 * Client
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
interface ClientInterface
{
    /**
     * @return int
     */
    public function getUserId(): int;

    /**
     * @param int $nodeTypeId
     * @return NodeType
     * @throws DatabaseException
     * @throws NodeTypeNotFoundException
     */
    public function getNodeType(int $nodeTypeId): NodeType;

    /**
     * @return array
     * @throws DatabaseException
     */
    public function getNodeTypes(): array;

    /**
     * @param NodeType $nodeType
     * @return ClientInterface
     */
    public function saveNodeType(NodeType $nodeType): ClientInterface;

    /**
     * @param NodeType $nodeType
     * @return ClientInterface
     */
    public function deleteNodeType(NodeType $nodeType): ClientInterface;

    /**
     * @return NodesQueryInterface
     */
    public function createNodesQuery(): NodesQueryInterface;

    /**
     * @param NodesQueryInterface $query
     * @return array
     * @throws DatabaseException
     */
    public function findNodes(NodesQueryInterface $query): array;

    /**
     * @param Node $node
     * @return ClientInterface
     */
    public function saveNode(Node $node): ClientInterface;

    /**
     * @param Node $node
     * @return ClientInterface
     */
    public function deleteNode(Node $node): ClientInterface;
}
