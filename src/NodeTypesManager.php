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

use Lepre\Cr\Client\ClientInterface;
use Lepre\Cr\Exception\CRException;
use Lepre\Cr\Exception\NodeTypeNotFoundException;

/**
 * NodeTypeManager
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
final class NodeTypesManager
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return NodeType[]
     * @throws CRException
     */
    public function getNodeTypes(): array
    {
        return $this->client->getNodeTypes();
    }

    /**
     * @param int $nodeTypeId
     * @return NodeType
     * @throws NodeTypeNotFoundException
     * @throws CRException
     */
    public function getNodeType(int $nodeTypeId): NodeType
    {
        return $this->client->getNodeType($nodeTypeId);
    }

    /**
     * @param NodeType $nodeType
     * @return $this
     * @throws CRException
     *
     * @todo
     * @codeCoverageIgnore
     */
    public function saveNodeType(NodeType $nodeType)
    {
        trigger_error('The method ' . __METHOD__ . ' is not implemented.', E_USER_WARNING);

        return $this;
    }

    /**
     * @param NodeType $nodeType
     * @param string   $newName
     * @return $this
     * @throws CRException
     *
     * @todo
     * @codeCoverageIgnore
     */
    public function renameNodeType(NodeType $nodeType, string $newName)
    {
        trigger_error('The method ' . __METHOD__ . ' is not implemented.', E_USER_WARNING);

        return $this;
    }

    /**
     * @param NodeType $nodeType
     * @return $this
     * @throws CRException
     *
     * @todo
     * @codeCoverageIgnore
     */
    public function deleteNodeType(NodeType $nodeType)
    {
        trigger_error('The method ' . __METHOD__ . ' is not implemented.', E_USER_WARNING);

        return $this;
    }
}
