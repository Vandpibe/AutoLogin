<?php

/*
 * This file is part of the Vandpibe package.
 *
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vandpibe\Test\AutoLogin\Cilex;

use Cilex\Application;
use Vandpibe\AutoLogin\Cilex\AutoLoginServiceProvider;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class AutoLoginServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->application = new Application('AutoLogin');
        $this->application['url_generator'] = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');

        $this->provider = new AutoLoginServiceProvider();
    }

    public function testRegister()
    {
        $this->provider->register($this->application);

        $this->assertInternalType('null', $this->application['vandpibe.auto_login.secret']);
        $this->assertInstanceOf('Vandpibe\AutoLogin\GeneratorInterface', $this->application['vandpibe.auto_login.generator']);
        $this->assertInstanceOf('Vandpibe\AutoLogin\HasherInterface', $this->application['vandpibe.auto_login.hasher']);

        $this->setExpectedException('InvalidArgumentException');

        $this->application['vandpibe.auto_login.twig.auto_login'];
    }

    public function testRegisterTwigExtensionIfAvaiable()
    {
        $this->application['twig'] = $this->getMock('Twig_Environment');
        $this->application['twig']
            ->expects($this->once())
            ->method('addExtension')
        ;

        $this->provider->register($this->application);

        $this->assertInstanceof('Vandpibe\AutoLogin\Twig\AutoLoginExtension', $this->application['vandpibe.auto_login.twig.auto_login']);
    }
}
