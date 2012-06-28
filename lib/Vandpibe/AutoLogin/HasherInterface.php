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
interface HasherInterface
{
    /**
     * Must return a hashed value of $value. Could be `md5($value)` or something more
     * secure using a shared secret.
     *
     * @param  arrat  $parameters
     * @return string
     */
    public function hash(array $parameters);
}
