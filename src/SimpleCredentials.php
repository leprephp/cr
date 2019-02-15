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

/**
 * SimpleCredentials
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
final class SimpleCredentials implements CredentialsInterface
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @inheritDoc
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
