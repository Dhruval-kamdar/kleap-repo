<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd289eeaf3a2aeb36a3798744f072e23e
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'BZ_SGN_WZ\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'BZ_SGN_WZ\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd289eeaf3a2aeb36a3798744f072e23e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd289eeaf3a2aeb36a3798744f072e23e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
