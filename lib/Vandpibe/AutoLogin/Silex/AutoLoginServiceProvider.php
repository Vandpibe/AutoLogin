<?php

/*
 * This file is part of the Vandpibe package.
 *
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vandpibe\AutoLogin\Silex;

use Vandpibe\AutoLogin\Generator;
use Vandpibe\AutoLogin\Hasher;
use Vandpibe\AutoLogin\Twig\AutoLoginExtension;
use Silex\Application;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class AutoLoginServiceProvider implements \Silex\ServiceProviderInterface
{
    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['vandpibe.auto_login.secret'] = null;

        $app['vandpibe.auto_login.hasher'] = $app->share(function () use ($app) {
            return new Hasher($app['vandpibe.auto_login.secret']);
        });

        $app['vandpibe.auto_login.generator'] = $app->share(function () use ($app) {
            return new Generator($app['vandpibe.auto_login.hasher']);
        });

        if (isset($app['twig'], $app['url_generator'])) {
            $app['vandpibe.auto_login.twig.auto_login'] = $app->share(function () use ($app) {
                return new AutoLoginExtension($app['vandpibe.auto_login.generator'], $app['url_generator']);
            });

            $app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
                $twig->addExtension($app['vandpibe.auto_login.twig.auto_login']);

                return $twig;
            }));
        }
    }

    /**
     * @param  Application      $app
     * @throws RuntimeException
     */
    public function boot(Application $app)
    {
        if (false == isset($app['vandpibe.auto_login.secret'])) {
            throw new \RuntimeException('You have not provided a secret for "vandpibe.auto_login.secret"');
        }
    }
}
