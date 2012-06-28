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

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class Hasher implements HasherInterface
{
    /**
     * @var string
     */
    protected $secret;

    /**
     * @param string $secret
     */
    public function __construct($secret)
    {
        $this->secret = (string) $secret;
    }

    /**
     * {@inheritdoc}
     */
    public function hash(array $parameters)
    {
        // Helps to make sure the parameters are in the correct order no matter what order
        // they are given to the method.
        asort($parameters);

        return hash_hmac('sha1', http_build_query($parameters), $this->secret);
    }
}
