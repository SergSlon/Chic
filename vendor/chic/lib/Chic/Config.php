<?php

namespace Chic;

use Chic\Exceptions\ConfigException;

/**
 * Read and return the configuration values
 */
class Config
{
    private static $config = null;
    private static $configDir = null;
    private static $environments = array();
    
    /**
     * Load the base configuration file and others if needed
     * 
     * @param string an environment value to force the load of its configuration
     * @param string $configDir a path to the config directory, use the on in app/config by default
     */
    public static function init($specificEnv = null, $configDir = null) 
    {
        static::$configDir = APP_PATH.'config';
        static::$config = array();
        static::$environments = array();
        
        if ($configDir !== null) {
            static::$configDir = $configDir;
        }
        
        static::addConfig('env_base.php');
        
        if (isset(static::$config['environments'])) {
            static::$environments = array_keys(static::$config['environments']);
        }
        
        if ($specificEnv !== null) {
            //Load the specified environment
            if (in_array($specificEnv, static::$environments) === true) {
                static::addConfig('env_'.$specificEnv.'.php');
            } else {
                throw new ConfigException('Environment does not exist : '.$specificEnv);
            }
        } else {
            //Check if an environment has to be loaded
            foreach(static::$environments as $env) {
                $envFunc = static::$config['environments'][$env];
                if ($envFunc()) {
                    static::addConfig('env_'.$env.'.php');
                }
            }
        }
    }
    
    /**
     * Return a configuration value.
     * If the key doesn't exists, an Exception is thrown
     * 
     * @param string a configuration key, use '.' to seprate keys
     * @return string the configuration value 
     */
    public static function get($key) 
    {
        if (empty(static::$config)) {
            static::init();
        }
        
        $value = null;
        $configKeys = explode('.', $key);
        foreach($configKeys as $configKey) {
            if ($value === null) {
                if (isset(static::$config[$configKey]) === false) {
                    throw new ConfigException('Key Not found : '.$key);
                }
                
                $value = static::$config[$configKey];
            } else {
                if (isset($value[$configKey]) === false) {
                    throw new ConfigException('Key Not found : '.$key);
                }
                
                $value = $value[$configKey];
            }
        }
        
        return $value;
    }
    
    /**
     * Read a configuration file and return an array.
     * Overload this method to use other config file than php
     * 
     * @param string $configFile the path to a configuration file
     * @return array of configuration key => values
     */
    public static function read($configFile)
    {
        if (file_exists(static::$configDir.DS.$configFile) === false) {
            throw new ConfigException('The file does not exists in '.static::$configDir.DS.$configFile);
        }
        
        return include(static::$configDir.DS.$configFile);
    }
    
    /**
     * Get the new configuration file key => values and
     * merge it with current configuration
     * 
     * @param string path to the configuration file to add 
     */
    private static function addConfig($configFile) 
    {
        static::$config = array_replace_recursive(static::$config, static::read($configFile));
    }
}
