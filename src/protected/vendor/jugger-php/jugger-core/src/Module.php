<?php

namespace jugger\core;

class Module
{
    protected static $appRoot;
    
    public static function setRootDir(string $appRoot)
    {
        static::$appRoot = $appRoot;
    }
    
    public static function registerAutoload(string $moduleName)
    {
        spl_autoload_register(function($class) use($moduleName) {
            $namespacePrefix = "app\\modules\\{$moduleName}\\";
            if (stripos($class, $namespacePrefix) === 0) {
                $classWithoutApp = substr($class, 4);
                $classPath = str_replace('\\', '/', $classWithoutApp);
                $filePath = rtrim(static::$appRoot, '/'). "/{$classPath}.php";
                if (file_exists($filePath)) {
                    include $filePath;
                }
            }
        });
    }
}