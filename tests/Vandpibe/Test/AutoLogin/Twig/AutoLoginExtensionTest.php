<?php

/*
 * This file is part of the Vandpibe package.
 *
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vandpibe\Test\AutoLogin\Twig;

use Vandpibe\AutoLogin\Twig\AutoLoginExtension;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class AutoLoginExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->generator = $this->getMock('Vandpibe\AutoLogin\GeneratorInterface');
        $this->user = $this->getMock('Symfony\Component\Security\Core\User\UserInterface');
        $this->router = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $this->extension = new AutoLoginExtension($this->generator, $this->router);
    }

    public function testGetFunctions()
    {
        $functions = $this->extension->getFunctions();

        $this->assertContainsOnly('Twig_Function', $functions);
        $this->assertArrayHasKey('auto_login_url', $functions);
        $this->assertArrayHasKey('auto_login', $functions);
    }

    public function testGenerateAutoLoginUrl()
    {
        $this->generator
            ->expects($this->once())
            ->method('generate')
            ->with($this->equalTo($this->user), $this->equalTo(86400))
            ->will($this->returnValue('auto_login'))
        ;

        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with($this->equalTo('some_route'), $this->equalTo(array('_al' => 'auto_login')), $this->equalTo(true))
            ->will($this->returnValue('someurl'))
        ;

        $this->extension->generateAutoLoginUrl($this->user, 'some_route', array());
    }

    public function testGenerateAutoLogin()
    {
        $this->generator
            ->expects($this->once())
            ->method('generate')
            ->will($this->returnValue('generated-value'))
        ;

        $this->assertEquals('generated-value', $this->extension->generateAutoLogin($this->user));
    }

    public function testGetName()
    {
        $this->assertEquals('auto_login', $this->extension->getName());
    }
}
