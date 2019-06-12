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
use Lepre\Cr\Exception\LanguageNotFoundException;
use Lepre\Cr\Exception\NodeTypeNotFoundException;
use Lepre\Cr\Language;
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
     * @return NodeType
     */
    public function createNodeType(): NodeType;

    /**
     * @param int $nodeTypeId
     * @return NodeType
     * @throws DatabaseException
     * @throws NodeTypeNotFoundException
     */
    public function getNodeType(int $nodeTypeId): NodeType;

    /**
     * @return NodeType[]
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
     * @return Language
     */
    public function createLanguage(): Language;

    /**
     * @param int $languageId
     * @return Language
     * @throws DatabaseException
     * @throws LanguageNotFoundException
     */
    public function getLanguage(int $languageId): Language;

    /**
     * @return Language[]
     * @throws DatabaseException
     */
    public function getLanguages(): array;

    /**
     * @param Language $language
     * @return ClientInterface
     */
    public function saveLanguage(Language $language): ClientInterface;

    /**
     * @param Language $language
     * @return ClientInterface
     */
    public function deleteLanguage(Language $language): ClientInterface;

    /**
     * @return Node
     */
    public function createNode(): Node;

    /**
     * @return NodesQueryInterface
     */
    public function createNodesQuery(): NodesQueryInterface;

    /**
     * @param NodesQueryInterface $query
     * @return Node[]
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
