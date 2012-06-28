<?php

/*
 * This file is part of the Vandpibe package.
 *
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vandpibe\Test\AutoLogin;

use Vandpibe\AutoLogin\Hasher;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class HasherTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->hasher = new Hasher('secret');
    }

    public function testHash()
    {
        $this->assertEquals(hash_hmac('sha1', '0=value', 'secret'), $this->hasher->hash(array('value')));
    }

    public function testHashParameterOrdering()
    {
        $hash = $this->hasher->hash(array(1 => '1', 2 => '2'));

        $this->assertEquals($hash, $this->hasher->hash(array(2 => '2', 1 => '1')));
    }
}
