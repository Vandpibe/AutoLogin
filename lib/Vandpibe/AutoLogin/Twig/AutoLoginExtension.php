<?php

/*
 * This file is part of the Vandpibe package.
 *
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vandpibe\AutoLogin\Twig;

use Vandpibe\AutoLogin\GeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class AutoLoginExtension extends \Twig_Extension
{
    /**
     * @var GeneratorInterface
     */
    protected $generator;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @param GeneratorInterface $generator
     */
    public function __construct(GeneratorInterface $generator, UrlGeneratorInterface $router)
    {
        $this->generator = $generator;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'auto_login_url' => new \Twig_Function_Method($this, 'generateAutoLoginUrl'),
            'auto_login'     => new \Twig_Function_Method($this, 'generateAutoLogin'),
        );
    }

    /**
     * @param  UserInterface $user
     * @param  string        $route
     * @param  array         $parameters
     * @param  integer       $ttl
     * @return string
     */
    public function generateAutoLoginUrl(UserInterface $user, $route, array $parameters = array(), $ttl = 86400)
    {
        $parameters['_al'] = $this->generateAutoLogin($user, $ttl);

        return $this->router->generate($route, $parameters, true);
    }

    /**
     * @param  UserInterface $user
     * @param  integer       $ttl
     * @return string
     */
    public function generateAutoLogin(UserInterface $user, $ttl = 86400)
    {
        return $this->generator->generate($user, $ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'auto_login';
    }
}
