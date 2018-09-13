<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit255ddfed3559273c75d18d375a4b4b7f
{
    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpspider\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpspider\\' => 
        array (
            0 => __DIR__ . '/..' . '/owner888/phpspider',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit255ddfed3559273c75d18d375a4b4b7f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit255ddfed3559273c75d18d375a4b4b7f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
