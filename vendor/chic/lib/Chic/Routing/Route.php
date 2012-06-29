<?php

namespace Chic\Routing;

use Chic\Config,
    Chic\Exceptions\RouterException;

class Route
{
    public $routePattern = null;
    public $namespace = null;
    public $controller = null;
    public $method = null;
    public $methodArgs = null;
    public $httpMethods = null;
    public $closure = null;
    public $filter = null;
    private $patterns = array(
        ':num?' => '(\d+)?',
		':any?' => '([\w\.-_]+)?',
		':all?' => '(.+)?',
		':num' => '\d+',
		':any' => '[\w\.-_]+',
		':all' => '.+',
        ':method' => '[\w_]+',
        ':controller' => '[\w_]+'
	);
    
    public function __construct($routePattern, $httpMethods = null, $closureOrControllerMethod = null, $filter = null)
    {
        if ($closureOrControllerMethod === null) {
            $routeParts = explode('/', $routePattern);
            switch (sizeof($routeParts)) {
                case 1:
                    if ($routeParts[0] !== '(:controller)') {
                        $this->namespace = ucfirst($routePattern);
                        $this->controller = ucfirst($routePattern);
                    }
                    $this->method = Config::get('router.defaultMethodName');
                    break;
                default:
                    if ($routeParts[0] !== '(:controller)') {
                        $this->namespace = ucfirst($routeParts[0]);
                        $this->controller = ucfirst($routeParts[0]);
                    }

                    if ($routeParts[1] !== '(:method)') {
                        $this->method = $routeParts[1];
                    }
                    break;
            }
        } else {
            if (is_callable($closureOrControllerMethod)) {
                $this->closure = $closureOrControllerMethod;
            } else {
                $namespaceController = explode('\\', $closureOrControllerMethod);
                $this->namespace = ucfirst($namespaceController[0]);
                
                $controllerMethod = explode('#', $namespaceController[1]);
                $this->controller = $controllerMethod[0];
                $this->method = $controllerMethod[1];
            }
        }
        
        $this->routePattern = $routePattern;
        $this->httpMethods = $httpMethods;
        if ($httpMethods === null) {
            $this->httpMethods = Config::get('router.defaultHttpMethods');
        }
        $this->filter = $filter;
    }
    
    /**
     * Try to match this $url with the routePattern expression
     * 
     * @param string $url 
     */
    public function match($url)
    {
        if ($url === $this->routePattern) {
            return true;
        }
        
        if (preg_match_all($this->getRoutePattern($this->routePattern), $url, $args)) {
            $index = 1;
            
            if (isset($args[0]) === true) {
                unset($args[0]);
            }
            
            if ($this->closure === null) {
                if ($this->controller === null) {
                    if (isset($args[$index]) === false) {
                        throw new RouterException('Url matched with '.$this->routePattern.' but cannot get the controler');
                    }

                    $this->namespace = ucfirst($args[$index][0]);
                    $this->controller = ucfirst($args[$index][0]);
                    unset($args[$index]);
                    $index++;
                }

                if ($this->method === null) {
                    if (isset($args[$index]) === false) {
                        throw new RouterException('Url matched with '.$this->routePattern.' but cannot get the method');
                    }

                    $this->method = $args[$index][0];
                    unset($args[$index]);
                }
            }

            if (sizeof($args) > 0) {
                $methodArgs = array();
                foreach($args as $arg) {
                    $methodArgs[] = $arg[0];
                }
                $this->methodArgs = $methodArgs;
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Call the route's closure if defined, call the controller's method
     * 
     * @return the controller's method called result or the closure result
     */
    public function execute()
    {
        $filterRes = true;
        if ($this->filter !== null) {
            $filterRes = $this->filter();
        }
        
        if ($filterRes === true) {
            if ($this->closure === null) {
                return call_user_func(array($this->namespace.'\\'.$this->controller, $this->method));
            } else {
                return call_user_func_array($this->closure, $this->methodArgs);
            }
        }
        
        return false;
    }
    
    private function getRoutePattern($routePatternKey) 
    {
        $routePatterns = array_keys($this->patterns);
        $routePatternsReplace = array_values($this->patterns);
        
        return ';^'.str_replace($routePatterns, $routePatternsReplace, $routePatternKey).'$;';
    }
}