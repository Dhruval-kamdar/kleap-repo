<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class EVO_ComposerStaticInite6632287c7d3eaeab4b59c4d74ce6149
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = EVO_ComposerStaticInite6632287c7d3eaeab4b59c4d74ce6149::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = EVO_ComposerStaticInite6632287c7d3eaeab4b59c4d74ce6149::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
