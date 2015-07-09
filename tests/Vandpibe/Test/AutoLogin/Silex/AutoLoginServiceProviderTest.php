<?php

/*
 * This file is part of the Vandpibe package.
 *
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vandpibe\Test\AutoLogin\Silex;

use Vandpibe\AutoLogin\Silex\AutoLoginServiceProvider;
use Silex\Application;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class AutoLoginServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->application = new Application();
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
        $twig = $this->getMock('Twig_Environment');
        $twig
            ->expects($this->once())
            ->method('addExtension')
        ;

        $this->application['twig'] = function () use ($twig) {
            return $twig;
        };

        $this->provider->register($this->application);

        $this->application['twig'];

        $this->assertInstanceof('Vandpibe\AutoLogin\Twig\AutoLoginExtension', $this->application['vandpibe.auto_login.twig.auto_login']);
    }

    public function testBootThrowsExceptionWhenSecretIsNotDefined()
    {
        $this->setExpectedException('RuntimeException');

        $this->provider->boot($this->application);
    }

    public function testBoot()
    {
        $this->application['vandpibe.auto_login.secret'] = 'secret';

        $this->provider->boot($this->application);
    }
}
