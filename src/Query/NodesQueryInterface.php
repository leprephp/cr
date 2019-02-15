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

/**
 * NodesQuery
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
interface NodesQueryInterface
{
    /**
     * @param int $id
     * @return $this
     */
    public function byId(int $id): NodesQueryInterface;

    /**
     * @param string $path
     * @return $this
     */
    public function byPath(string $path): NodesQueryInterface;

    /**
     * @param string $modelClass
     * @return $this
     */
    public function setModelClass(string $modelClass): NodesQueryInterface;
}