<?php

namespace Chic\Routing;

use Chic\Routing\Route,
    Chic\Routing\Ressource,
    Chic\Exceptions\RouterException;

class Router
{
    private static $routes = array();
    private static $routeNotFound = null;
    
    /**
     * Add a route to the Router route list
     * 
     * @param Route or Ressource $routeOrRessource the route to add
     */
    public static function add($route)
    {
        if ($route instanceof Route) {
            static::$routes[$route->routePattern] = $route;
        } else {
            if (is_object($route)) {
                throw new RouterException('Only Route or Ressource can be added. This object is : '.get_class($route));
            } else {
                throw new RouterException('Only Route or Ressource can be added.');
            }
        }
    }
    
    /**
     * Return the Route named $routeName if exists. Send an Exception instead.
     * 
     * @param string $routeName
     * @return Route the found
     */
    public static function get($routeName)
    {
        if (isset(static::$routes[$routeName]) === false) {
            throw new RouterException('This route does not exists : '.$routeName);
        }
        
        return static::$routes[$routeName];
    }
    
    /**
     * Try to match the url with added routes
     * 
     * @param string $url 
     */
    public static function match($url) 
    {
        foreach(static::$routes as $route) {
            if ($route->match($url)) {
                return $route->execute();
            }
        }
        
        if (static::$routeNotFound !== null) {
            static::$routeNotFound();
        } else {
            throw new RouterException('The current url cannot be matched : '.$url);
        }
    }
    
    /**
     * Define a closure function to execute when no route match an url
     *
     * @param function $func
     */
    public static function routeNotFound($func)
    {
        if (is_callable($func)) {
            static::$routeNotFound = $func;
        }
    }
}