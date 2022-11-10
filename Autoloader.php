<?php

//Class autoloader

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public static function autoload($class)
    {
            $class = str_replace('\\', '/', $class);
            require APP_ROOT . $class . '.php';
    }
}

Autoloader::register();