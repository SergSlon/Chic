<?php

namespace Chic;

use Chic\Exceptions\I18nException,
    Chic\Helpers\Folder;

/**
 * Read, process and return translated strings
 */
class I18n
{
    private static $lang = null;
    private static $langs = null;
    private static $langsValues = array();
    private static $langDir = null;

    /**
     * Initialise the default lang, langs and load the langs_values.
     * Use the i18n.default lang if none specified
     * 
     * @param string $lang force the use of a lang
     * @param string $langDir a path to the lang directory, use the on in app/lang by default
     */
    public static function init($lang = null, $langDir = null)
    {
        static::$langs = Config::get('i18n.langs');
        static::$langDir = APP_PATH.'lang';
        
        if ($langDir !== null) {
            static::$langDir = $langDir;
        }
        
        $_lang = $lang;
        if ($_lang === null) {
            $_lang = Config::get('i18n.default');
        }
        
        if (static::isLang($_lang) === false) {
            throw new Exceptions\I18nException('Invalid lang : '.$_lang);
        }
        
        static::lang($_lang);
        
        foreach(static::$langs as $aLang) {
            static::addLang($aLang);
        }
    }
    
    /**
     * Set the lang if specified. Return the current lang.
     * 
     * @param string $lang an existing lang
     * @return string the current lang
     */
	public static function lang($lang = null)
	{
		if ($lang !== null && static::isLang($lang)) {
			static::$lang = $lang;
		}

		return static::$lang;
	}
    
    /**
     * Check if the passed lang exits
     * 
     * @param string $lang an existing lang
     * @return boolean true if $lang exists
     */
    public static function isLang($lang)
	{
		return in_array($lang, static::$langs);
	}
	
    /**
     * The langs list
     * 
     * @return array the lang list
     */
    public static function langs()
	{
        if (static::$langs === null) {
            static::init();
        }
        
		return static::$langs;
	}

    /**
     * Return a translated string
     *
     * @param string $key an existing lang key
     * @param array $vars a list of value for the string variables
     * @param type $lang force use of an other lang than the default
     * @return string the corresponding lang value
     */
	public static function t($key, array $vars = array(), $lang = null)
	{
        if (empty(static::$langsValues)) {			
			static::init();
		}
        
		if ($lang === null) {
            $_lang = static::lang();
        } else {
            if (static::isLang($lang) === false) {
                throw new Exceptions\I18nException('Invalid lang : '.$lang);
            }
            
            $_lang = $lang;
        }

        $value = null;
        $langKeys = explode('.', $key);
        foreach($langKeys as $langKey) {
            if ($value === null) {
                $value = static::$langsValues[$_lang][$langKey];
            } else {
                $value = $value[$langKey];
            }
        }
        
        if ($value === null) {
            throw new I18nException('The key '.$key.' is not available in langage '.$_lang);
        }
        
        if (empty($vars) === false) {
            $value = static::updateString($value, $vars);
        }
		return $value;
	}

    /**
     * Read a lang file and return an array.
     * Overload this method to use other lang file than php
     * 
     * @param string $langFile the path to a lang file
     * @return array of lang key => values
     */
    public static function read($langFile)
    {
        if (file_exists($langFile) === false) {
            throw new I18nException('The lang file does not exists : '.$langFile);
        }
        
        return include($langFile);
    }
    
    /**
     * Load all the lang folder's files
     * 
     * @param string $lang a folder lang name
     */
	private static function addLang($lang)
	{
        if (file_exists(static::$langDir.DS.$lang) === false) {
            throw new I18nException('The lang folder does not exists : '.$lang);
        }
        
        static::$langsValues[$lang] = array();
        
        $files = Folder::filesToArrayList(static::$langDir.DS.$lang);
        foreach($files as $file) {
            static::$langsValues[$lang] = array_merge(static::$langsValues[$lang], static::read($file));
        }
	}
    
    /**
     * Update a string to replace variables with value
     *
     * @param string $string the string to update
     * @param array $vars the variable values
     * @return string the string updated
     */
    private static function updateString($string, array $vars) {
        $search = explode(',', '/{{ *'.implode(' *}}/,/{{ *', array_keys($vars)).' *}}/');
        $replace = array_values($vars);
        return preg_replace($search, $replace, $string);
    }
}
