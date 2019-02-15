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

namespace Lepre\Cr\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Lepre\Cr\Node;

/**
 * NodesQuery
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
class NodesQuery implements NodesQueryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @return string
     */
    private $modelClass = Node::class;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $path;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function byId(int $id): NodesQueryInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function byPath(string $path): NodesQueryInterface
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return QueryBuilder
     *
     * @internal
     */
    public function getQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'n.node_id',
                'n.title AS node_title',
                'n.slug AS node_slug',
                'n.path AS node_path',
                'n.properties AS node_properties',
                'nt.nodetype_id',
                'nt.name AS nodetype_name',
                'l.language_id',
                'l.name AS language_name'
            )
            ->from('cr_nodes', 'n')
            ->innerJoin('n', 'cr_nodetypes_languages', 'ntl', 'n.nodetype_language_id = ntl.nodetype_language_id')
            ->innerJoin('ntl', 'cr_nodetypes', 'nt', 'ntl.nodetype_id = nt.nodetype_id')
            ->innerJoin('ntl', 'cr_languages', 'l', 'ntl.language_id = l.language_id')
        ;

        if ($this->id) {
            $qb->where('n.node_id = :id')->setParameter('id', $this->id);
        } elseif ($this->path) {
            $qb->where('n.path = :path')->setParameter('path', $this->path);
        }

        return $qb;
    }

    /**
     * @param string $modelClass
     * @return $this
     */
    public function setModelClass(string $modelClass): NodesQueryInterface
    {
        if (!is_subclass_of($modelClass, Node::class)) {
            throw new \InvalidArgumentException(
                'The class "' . $modelClass . '" is not a subclass of "' . Node::class . '"'
            );
        }

        $this->modelClass = $modelClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return $this->modelClass;
    }
}
