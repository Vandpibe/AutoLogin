<?php

/*
 * This file is part of the Vandpibe package.
 *
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vandpibe\AutoLogin\Security;

use Jmikola\AutoLogin\Exception\AutoLoginTokenNotFoundException;
use Jmikola\AutoLogin\User\AutoLoginUserProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Vandpibe\AutoLogin\HasherInterface;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class UserProvider implements AutoLoginUserProviderInterface
{
    /**
     * @var UserProviderInterface
     */
    protected $provider;

    /**
     * @var HasherInterface
     */
    protected $hasher;

    /**
     * @param UserProviderInterface $provider
     */
    public function __construct(UserProviderInterface $provider, HasherInterface $hasher)
    {
        $this->provider = $provider;
        $this->hasher = $hasher;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByAutoLoginToken($key)
    {
        $hash = null;
        $expireAt = null;
        $username = null;

        // Parse the query string
        parse_str(base64_decode($key));

        if (time() < $expireAt && $hash == $this->hasher->hash(compact('username', 'expireAt'))) {
            return $this->provider->loadUserByUsername($username);
        }

        throw new AutoLoginTokenNotFoundException('"$key" contains invalid information.');
    }
}
