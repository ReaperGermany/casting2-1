<?php

class Core
{
    protected static $_controller = null;
    protected static $_register = array();
    protected static $_singletons = array();

    public static function app()
    {
        if (!self::$_controller) {
            self::$_controller = &get_instance();
        }

        return self::$_controller;
    }

    public static function getModel($model)
    {
        $key = "autoload_model_".$model;
        self::app()->load->model($model, $key);
        return self::app()->$key;
    }

    public static function register($key, $value)
    {
        self::$_register[$key] = $value;
    }

    public static function registry($key, $default =null)
    {
        if (isset(self::$_register[$key])) {
            return self::$_register[$key];
        }
        return $default;
    }

    public static function getSingleton($model)
    {
        if (!isset(self::$_singletons[$model])) {
            self::$_singletons[$model] = self::getModel($model);
        }

        return self::$_singletons[$model];
    }
}