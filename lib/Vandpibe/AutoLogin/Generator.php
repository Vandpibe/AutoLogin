<?php

/*
 * This file is part of the Vandpibe package.
 *
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vandpibe\AutoLogin;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Generator implements GeneratorInterface
{
    /**
     * @var HasherInterface
     */
    protected $hasher;

    /**
     * @param HasherInterface $hasher
     */
    public function __construct(HasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(UserInterface $user, $ttl = 86400)
    {
        $expireAt = time() + $ttl;

        $parameters = array(
            'username' => $user->getUsername(),
            'expireAt' => time() + $ttl,
        );

        $parameters['hash'] = $this->hasher->hash($parameters);

        return base64_encode(http_build_query($parameters));
    }
}
