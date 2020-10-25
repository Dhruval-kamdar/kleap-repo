<?php

// autoload_static.php @generated by FSP_Composer

namespace FSP_Composer\Autoload;

class FSP_ComposerStaticInitdc39eaceb57d60355273d1481f6e956d
{
    public static $files = array (
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '25072dd6e2470089de65ae7bf11d3109' => __DIR__ . '/..' . '/symfony/polyfill-php72/bootstrap.php',
        '905360f519dec826f91650c90c247715' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        '9d9527d0ecf6b6ed2708e8ef92b017e8' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
        'f598d06aa772fa33d905e87be6398fb1' => __DIR__ . '/..' . '/symfony/polyfill-intl-idn/bootstrap.php',
        'efd36dcc57edf7139aab4dda98c4e468' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Php72\\' => 23,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Intl\\Idn\\' => 26,
            'Symfony\\Component\\Process\\' => 26,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
        ),
        'G' => 
        array (
            'FSP_GuzzleHttp\\Psr7\\' => 16,
            'FSP_GuzzleHttp\\Promise\\' => 19,
            'FSP_GuzzleHttp\\' => 11,
        ),
        'F' => 
        array (
            'FSPoster\\' => 9,
        ),
        'A' => 
        array (
            'Abraham\\TwitterOAuth\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Php72\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php72',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Intl\\Idn\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-intl-idn',
        ),
        'Symfony\\Component\\Process\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/process',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'FSP_GuzzleHttp\\Psr7\\' =>
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'FSP_GuzzleHttp\\Promise\\' =>
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'FSP_GuzzleHttp\\' =>
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'FSPoster\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
        'Abraham\\TwitterOAuth\\' => 
        array (
            0 => __DIR__ . '/..' . '/abraham/twitteroauth/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Tumblr\\API' => 
            array (
                0 => __DIR__ . '/..' . '/tumblr/tumblr/lib',
            ),
        ),
        'E' => 
        array (
            'Eher\\OAuth' => 
            array (
                0 => __DIR__ . '/..' . '/eher/oauth/src',
            ),
        ),
    );

    public static $classMap = array (
        'WP_Async_Request' => __DIR__ . '/..' . '/a5hleyrich/wp-background-processing/classes/wp-async-request.php',
        'WP_Background_Process' => __DIR__ . '/..' . '/a5hleyrich/wp-background-processing/classes/wp-background-process.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = FSP_ComposerStaticInitdc39eaceb57d60355273d1481f6e956d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = FSP_ComposerStaticInitdc39eaceb57d60355273d1481f6e956d::$prefixDirsPsr4;
            $loader->prefixesPsr0 = FSP_ComposerStaticInitdc39eaceb57d60355273d1481f6e956d::$prefixesPsr0;
            $loader->classMap = FSP_ComposerStaticInitdc39eaceb57d60355273d1481f6e956d::$classMap;

        }, null, ClassLoader::class);
    }
}