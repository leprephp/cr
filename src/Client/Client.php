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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\ParameterType;
use Lepre\Cr\CredentialsInterface;
use Lepre\Cr\Exception\DatabaseException;
use Lepre\Cr\Exception\NodeTypeNotFoundException;
use Lepre\Cr\Language;
use Lepre\Cr\Node;
use Lepre\Cr\NodeType;
use Lepre\Cr\Query\NodesQuery;
use Lepre\Cr\Query\NodesQueryInterface;

/**
 * Client
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
final class Client implements ClientInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var CredentialsInterface
     */
    private $credentials;

    /**
     * @var IdentityMap
     */
    private $identityMap;

    /**
     * @param Connection           $connection
     * @param CredentialsInterface $credentials
     */
    public function __construct(Connection $connection, CredentialsInterface $credentials)
    {
        $this->connection = $connection;
        $this->credentials = $credentials;

        $this->identityMap = new IdentityMap();
    }

    /**
     * @inheritDoc
     */
    public function getUserId(): int
    {
        return $this->credentials->getUserId();
    }

    /**
     * @inheritDoc
     */
    public function getNodeType(int $nodeTypeId): NodeType
    {
        try {
            $sth = $this->connection->executeQuery(
                'SELECT nodetype_id, name AS nodetype_name FROM cr_nodetypes WHERE nodetype_id = :id',
                [
                    'id' => $nodeTypeId,
                ],
                [
                    'id' => ParameterType::INTEGER,
                ]
            );

            $row = $sth->fetch();

            if (!$row) {
                throw new NodeTypeNotFoundException();
            }

            return $this->hydrateNodeType($row);
        } catch (DBALException $e) {
            throw new DatabaseException("An error occurred on fetching node type \"{$nodeTypeId}\"", 0, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        try {
            $sth = $this->connection->executeQuery(
                'SELECT nodetype_id, name AS nodetype_name FROM cr_nodetypes'
            );

            $result = [];
            foreach ($sth as $row) {
                $result[] = $this->hydrateNodeType($row);
            }

            return $result;
        } catch (DBALException $e) {
            throw new DatabaseException('An error occurred on fetching node types', 0, $e);
        }
    }

    /**
     * @inheritDoc
     *
     * @todo
     * @codeCoverageIgnore
     */
    public function saveNodeType(NodeType $nodeType): ClientInterface
    {
        trigger_error('The method ' . __METHOD__ . ' is not implemented.', E_USER_WARNING);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @todo
     * @codeCoverageIgnore
     */
    public function deleteNodeType(NodeType $nodeType): ClientInterface
    {
        trigger_error('The method ' . __METHOD__ . ' is not implemented.', E_USER_WARNING);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createNodesQuery(): NodesQueryInterface
    {
        return new NodesQuery($this->connection);
    }

    /**
     * @inheritDoc
     */
    public function findNodes(NodesQueryInterface $query): array
    {
        try {
            $result = [];
            foreach ($query->getQuery()->execute() as $row) {
                $result[] = $this->hydrateNode($row);
            }

            return $result;
        } catch (DBALException $e) {
            throw new DatabaseException('An error occurred on finding nodes', 0, $e);
        }
    }

    /**
     * @inheritDoc
     *
     * @todo
     * @codeCoverageIgnore
     */
    public function saveNode(Node $node): ClientInterface
    {
        trigger_error('The method ' . __METHOD__ . ' is not implemented.', E_USER_WARNING);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @todo
     * @codeCoverageIgnore
     */
    public function deleteNode(Node $node): ClientInterface
    {
        trigger_error('The method ' . __METHOD__ . ' is not implemented.', E_USER_WARNING);

        return $this;
    }

    /**
     * @param array $row
     * @return NodeType
     *
     * @internal
     */
    public function hydrateNodeType(array $row): NodeType
    {
        $identity = NodeType::class . '-' . $row['nodetype_id'];

        if ($this->identityMap->has($identity)) {
            return $this->identityMap->get($identity);
        }

        $nodeType = new NodeType();

        $hydrator = (function (array $row) {
            $this->id = (int) $row['nodetype_id'];
            $this->name = (string) $row['nodetype_name'];
        })->bindTo($nodeType, NodeType::class);

        $hydrator($row);

        $this->identityMap->set($identity, $nodeType);

        return $nodeType;
    }

    /**
     * @param array $row
     * @return Language
     *
     * @internal
     */
    public function hydrateLanguage(array $row): Language
    {
        $identity = Language::class . '-' . $row['language_id'];

        if ($this->identityMap->has($identity)) {
            return $this->identityMap->get($identity);
        }

        $language = new Language();

        $hydrator = (function (array $row) {
            $this->id = (int) $row['language_id'];
            $this->name = (string) $row['language_name'];
        })->bindTo($language, Language::class);

        $hydrator($row);

        $this->identityMap->set($identity, $language);

        return $language;
    }

    /**
     * @param array $row
     * @return Node
     *
     * @internal
     */
    public function hydrateNode(array $row): Node
    {
        $identity = Node::class . '-' . $row['node_id'];

        if ($this->identityMap->has($identity)) {
            return $this->identityMap->get($identity);
        }

        $node = new Node();

        $client = $this;
        $hydrator = (function (array $row) use ($client) {
            $this->id = (int) $row['node_id'];

            $this->title = (string) $row['node_title'];
            $this->slug = (string) $row['node_slug'];
            $this->path = (string) $row['node_path'];
            $this->properties = json_decode($row['node_properties'], true);

            $this->nodeType = $client->hydrateNodeType($row);
            $this->language = $client->hydrateLanguage($row);
        })->bindTo($node, Node::class);

        $hydrator($row);

        $this->identityMap->set($identity, $node);

        return $node;
    }
}
