<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'authFilter'    => \App\Filters\AuthFilter::class,
        'tellerFilter'  => \App\Filters\TellerFilter::class,
        'undinFilter'  => \App\Filters\UndefinedFilter::class,

    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            'authFilter' => ['except' => ['/home/*', 'auth/*']],
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            'authFilter' => ['except' => ['/', '/auth/*',  'product/*', 'Dashboard/*', 'product/', 'profile/*', 'salereport/*', 'sell/*', 'stockreport/*', 'stocktransaction/*', 'supplier/*', 'sell/*', 'user/*']],
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don’t expect could bypass the filter.
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [
        'undinFilter' => ['before' => ['sell/*', 'product/*', 'salereport/*', 'stockreport/*', 'stocktransaction/*', 'supplier/*', 'user/*']],
        'tellerFilter' => ['before' => ['user/*', 'product/edit/*', 'product/delete', 'product/updatedata', 'supplier/edit/*', 'supplier/delete', 'supplier/updatedata']],
    ];
}
