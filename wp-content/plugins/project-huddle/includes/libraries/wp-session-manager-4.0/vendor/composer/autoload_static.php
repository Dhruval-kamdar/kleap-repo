<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1ba865ad7b126278abb4880ac1cddcc8
{
    public static $files = array (
        '5255c38a0faeba867671b61dfda6d864' => __DIR__ . '/..' . '/paragonie/random_compat/lib/random.php',
        'bee91f6e081cee6ae314324bd77cdd19' => __DIR__ . '/../..' . '/includes/deprecated.php',
    );

    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'EAMann\\Sessionz\\' => 16,
        ),
        'D' => 
        array (
            'Defuse\\Crypto\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'EAMann\\Sessionz\\' => 
        array (
            0 => __DIR__ . '/..' . '/ericmann/sessionz/php',
        ),
        'Defuse\\Crypto\\' => 
        array (
            0 => __DIR__ . '/..' . '/defuse/php-encryption/src',
        ),
    );

    public static $classMap = array (
        'EAMann\\WPSession\\DatabaseHandler' => __DIR__ . '/../..' . '/includes/DatabaseHandler.php',
        'EAMann\\WPSession\\Objects\\Option' => __DIR__ . '/../..' . '/includes/Option.php',
        'EAMann\\WPSession\\OptionsHandler' => __DIR__ . '/../..' . '/includes/OptionsHandler.php',
        'EAMann\\WPSession\\SessionHandler' => __DIR__ . '/../..' . '/includes/SessionHandler.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1ba865ad7b126278abb4880ac1cddcc8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1ba865ad7b126278abb4880ac1cddcc8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1ba865ad7b126278abb4880ac1cddcc8::$classMap;

        }, null, ClassLoader::class);
    }
}
