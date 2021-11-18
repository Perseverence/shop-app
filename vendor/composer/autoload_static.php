<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc9dea76a4b4b3a3e58bd3196483c3d54
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'App\\Cart' => __DIR__ . '/../..' . '/app/Cart.php',
        'App\\CartItem' => __DIR__ . '/../..' . '/app/CartItem.php',
        'App\\Product' => __DIR__ . '/../..' . '/app/Product.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc9dea76a4b4b3a3e58bd3196483c3d54::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc9dea76a4b4b3a3e58bd3196483c3d54::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc9dea76a4b4b3a3e58bd3196483c3d54::$classMap;

        }, null, ClassLoader::class);
    }
}
