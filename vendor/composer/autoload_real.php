<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitab7bbe64d16a3d4825d224b97cb02ae2
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitab7bbe64d16a3d4825d224b97cb02ae2', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitab7bbe64d16a3d4825d224b97cb02ae2', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitab7bbe64d16a3d4825d224b97cb02ae2::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
