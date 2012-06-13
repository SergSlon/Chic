<?php

namespace Chic\Helpers;

/**
 * Read folders and get files
 */
class Folder
{
    private static $files;
    
    /**
     * Return a one level array with all the files in the specified folder,
     * even in subfolders.
     * 
     * @param string $pathToFolder a valid path to a folder
     * @return array the files list
     */
    public static function filesToArrayList($pathToFolder)
    {
        static::$files = array();
        
        $lists = scandir($pathToFolder);
      
        if (!empty($lists)) {
            foreach ($lists as $file) {
                if (substr($file, 0, 1) == '.') {
                    continue;
                }
                
                if (is_dir($pathToFolder.DS.$file)) {
                    static::filesToArrayList($pathToFolder.DS.$file); 
                } else {
                    static::$files[] = $pathToFolder.DS.$file;
                }
            }
        }

        return static::$files;
    }
    
    public static function filesToArrayTree()
    {
        //TODO
    }
}
