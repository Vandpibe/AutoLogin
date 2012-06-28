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

use Vandpibe\AutoLogin\Generator;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->hasher = $this->getMock('Vandpibe\AutoLogin\HasherInterface');
        $this->user = $this->getMock('Symfony\Component\Security\Core\User\UserInterface');
        $this->generator = new Generator($this->hasher);
    }

    public function testGenerate()
    {
        $this->hasher
            ->expects($this->once())
            ->method('hash')
            ->will($this->returnValue('rikke-likes-hash'))
        ;

        $this->user
            ->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('rikkipige'))
        ;

        $query = $this->generator->generate($this->user);

        parse_str(base64_decode($query));

        $this->assertEquals('rikkipige', $username);
        $this->assertEquals('rikke-likes-hash', $hash);
        $this->assertEquals(time() + 86400, $expireAt);
    }
}
