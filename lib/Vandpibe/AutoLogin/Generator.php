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

        return self::base64UrlEncode(http_build_query($parameters));
    }

    public static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64UrlDecode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
