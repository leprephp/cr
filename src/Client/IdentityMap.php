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

/**
 * IdentityMap
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
final class IdentityMap
{
    /**
     * @var object[]
     */
    private $map = [];

    /**
     * @param string $id
     * @return mixed
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            return $this->map[$id];
        }

        return null;
    }

    /**
     * @param string      $id
     * @param null|object $entity
     * @return $this
     */
    public function set(string $id, $entity = null)
    {
        // @codeCoverageIgnoreStart
        /**
         * @todo php72: Use the 'object' parameter type
         * @link https://www.php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration
         */
        if (!is_object($entity) && !is_null($entity)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 2 passed to %s() must be an object, %s given',
                __METHOD__,
                gettype($entity)
            ));
        }
        // @codeCoverageIgnoreEnd

        $this->map[$id] = $entity;

        return $this;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->map);
    }
}
