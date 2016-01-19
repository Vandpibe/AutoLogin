<?php

/*
 * This file is part of the Vandpibe package.
 *
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vandpibe\Test\AutoLogin\Security;

use Vandpibe\AutoLogin\Generator;
use Vandpibe\AutoLogin\Security\UserProvider;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class UserProviderTest extends \PHPUnit_Framework_TestCase
{
    const VALID_EXPIRE_AT = 999999999999;

    public function setUp()
    {
        $this->userProvider = $this->getMock('Symfony\Component\Security\Core\User\UserProviderInterface');
        $this->hasher = $this->getMock('Vandpibe\AutoLogin\HasherInterface');
        $this->provider = new UserProvider($this->userProvider, $this->hasher);
    }

    public function testThrowsExceptionWithInvalidHash()
    {
        $this->setExpectedException('Jmikola\AutoLogin\Exception\AutoLoginTokenNotFoundException', '"$key" contains invalid information.');

        $this->hasher
            ->expects($this->once())
            ->method('hash')
            ->will($this->returnValue('evenmoreinvalid'))
        ;

        $this->provider->loadUserByAutoLoginToken(Generator::base64url_encode('username=henrik&expireAt=' . self::VALID_EXPIRE_AT . '&hash=invalid'));
    }

    public function testDontCallUserProviderWhenExpireAtIsInvalid()
    {

        $this->setExpectedException('Jmikola\AutoLogin\Exception\AutoLoginTokenNotFoundException', '"$key" contains invalid information.');

        $this->hasher
            ->expects($this->never())
            ->method('hash')
        ;

        $this->userProvider
            ->expects($this->never())
            ->method('loadUserByUsername')
        ;

        $this->provider->loadUserByAutoLoginToken(Generator::base64url_encode('username=henrik&expireAt=0&hash=invalid'));
    }

    public function testCallUserProviderWhenHashIsValid()
    {

        $this->hasher
            ->expects($this->once())
            ->method('hash')
            ->will($this->returnValue('valid'))
        ;

        $this->userProvider
            ->expects($this->once())
            ->method('loadUserByUsername')
            ->with($this->equalTo('henrik'))
        ;

        $this->provider->loadUserByAutoLoginToken(Generator::base64url_encode('username=henrik&expireAt=' . self::VALID_EXPIRE_AT . '&hash=valid'));
    }
}
