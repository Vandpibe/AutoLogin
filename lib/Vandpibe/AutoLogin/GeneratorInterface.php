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
interface GeneratorInterface
{
    /**
     * Returns a string that can be used to login the user. The default Generator
     * would generate something like:
     *
     *     dXNlcm5hbWU9aGVucmlrYmpvcm4mZXhwaXJlQXQ9MjMyMzIzMjMyMyZoYXNoPTFiYzI5YjM2ZjYyM2JhODJhYWY2NzI0ZmQzYjE2NzE4
     *
     * @param  UserInterface $user
     * @param  integer       $ttl
     * @return string
     */
    public function generate(UserInterface $user, $ttl = 86400);
}
