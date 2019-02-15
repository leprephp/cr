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

use Lepre\Cr\Client\Client;
use Lepre\Cr\Exception\NodeNotFoundException;
use Lepre\Cr\Query\NodesQueryInterface;

/**
 * Session
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
final class Session implements SessionInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var NodeTypesManager
     */
    private $nodeTypesManager;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function getUserId(): int
    {
        return $this->client->getUserId();
    }

    /**
     * @inheritDoc
     */
    public function getNode(int $nodeId): Node
    {
        $query = $this->client->createNodesQuery()->byId($nodeId);
        $result = $this->client->findNodes($query);

        if (empty($result)) {
            throw new NodeNotFoundException();
        }

        return reset($result);
    }

    /**
     * @inheritDoc
     */
    public function getNodeByPath(string $path): Node
    {
        $query = $this->createNodesQuery()->byPath($path);
        $result = $this->client->findNodes($query);

        if (empty($result)) {
            throw new NodeNotFoundException();
        }

        return reset($result);
    }

    /**
     * @inheritDoc
     */
    public function createNodesQuery(): NodesQueryInterface
    {
        return $this->client->createNodesQuery();
    }

    /**
     * @inheritDoc
     */
    public function getNodeTypesManager(): NodeTypesManager
    {
        if (null === $this->nodeTypesManager) {
            $this->nodeTypesManager = new NodeTypesManager($this->client);
        }

        return $this->nodeTypesManager;
    }
}
