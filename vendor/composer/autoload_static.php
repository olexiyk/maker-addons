<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf778dc86359b7752559aa6e64c9835e2
{
    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'Checkdomain\\Holiday' => 
            array (
                0 => __DIR__ . '/..' . '/checkdomain/holiday/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitf778dc86359b7752559aa6e64c9835e2::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
