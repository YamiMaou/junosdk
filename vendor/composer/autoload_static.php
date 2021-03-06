<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdacfd3da4a847a8eb493c830bbb3767d
{
    public static $prefixLengthsPsr4 = array (
        'Y' => 
        array (
            'YamiTec\\JunoSDK\\' => 16,
            'YamiTec\\DotENV\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'YamiTec\\JunoSDK\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'YamiTec\\DotENV\\' => 
        array (
            0 => __DIR__ . '/..' . '/yamitec/dot-env/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdacfd3da4a847a8eb493c830bbb3767d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdacfd3da4a847a8eb493c830bbb3767d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdacfd3da4a847a8eb493c830bbb3767d::$classMap;

        }, null, ClassLoader::class);
    }
}
