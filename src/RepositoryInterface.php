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
 * RepositoryInterface
 *
 * @author Daniele De Nobili <danieledenobili@gmail.com>
 */
interface RepositoryInterface
{
    /**
     * @param CredentialsInterface $credential
     * @return SessionInterface
     */
    public function login(CredentialsInterface $credential): SessionInterface;
}
