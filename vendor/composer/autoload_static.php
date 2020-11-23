<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3e4ef7758fc3ca57cc202253f380ad0a
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

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3e4ef7758fc3ca57cc202253f380ad0a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3e4ef7758fc3ca57cc202253f380ad0a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3e4ef7758fc3ca57cc202253f380ad0a::$classMap;

        }, null, ClassLoader::class);
    }
}
