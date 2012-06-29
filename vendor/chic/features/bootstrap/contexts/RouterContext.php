<?php

use Behat\Behat\Context\BehatContext,
    Chic\Routing\Router,
    Chic\Routing\Route,
    Chic\Routing\Ressource;

class RouterContext extends BehatContext
{
    private $routeFolderPath;
    private $currentRoute;
    private $matchResult;
    
    public function __construct(array $parameters)
    {
        $this->routeFolderPath = $parameters['routeFolderPath'];
    }
    
    /**
     * @When /^I call Router::add\(new Route\("([^"]*)"\)\)$/
     */
    public function iCallRouterAddNewRoutePosts($routeName)
    {
        Router::add(new Route($routeName));
    }

    /**
     * @Given /^I get the route "([^"]*)"$/
     */
    public function iGetTheRoute($routeName)
    {
        $this->currentRoute = Router::get($routeName);
    }

    /**
     * @Then /^I should get Namespace = "([^"]*)"$/
     */
    public function iShouldGetNamespace($namespace)
    {
        $this->getMainContext()->assertEquals($this->currentRoute->namespace, $namespace);
    }

    /**
     * @Given /^I should get Controller = "([^"]*)"$/
     */
    public function iShouldGetController($controller)
    {
        $this->getMainContext()->assertEquals($this->currentRoute->controller, $controller);
    }

    /**
     * @Given /^I should get Method = "([^"]*)"$/
     */
    public function iShouldGetMethod($method)
    {
        $this->getMainContext()->assertEquals($this->currentRoute->method, $method);
    }

    /**
     * @Given /^I should get HttpMethods = "GET","POST"$/
     */
    public function iShouldGetHttpMethodsGetPost()
    {
        $this->getMainContext()->assertEquals($this->currentRoute->httpMethods, array('GET', 'POST'));
    }
    
    /**
     * @Given /^I should get Args = "([^"]*)"$/
     */
    public function iShouldGetArgs($arg)
    {
        $this->getMainContext()->assertEquals($this->currentRoute->methodArgs[0], $arg);
    }
    
    /**
     * @Given /^I call Router::match\("([^"]*)"\)$/
     */
    public function iCallRouterMatch($url)
    {
        $this->getMainContext()->output = Router::match($url);
    }
    
    /**
     * @Given /^I call match for "([^"]*)"$/
     */
    public function iCallMatchFor($url)
    {
        $this->matchResult = $this->currentRoute->match($url);
    }
    
    /**
     * @When /^I create a Route "([^"]*)"$/
     */
    public function iCreateARoute($routePattern)
    {
        $this->currentRoute = new Route($routePattern);
    }
    
    /**
     * @Then /^I should get match "([^"]*)"$/
     */
    public function iShouldGetMatch($matchResult)
    {
        if ($matchResult === 'false') {
            $this->getMainContext()->assertEquals($this->matchResult, false);
        }
    }
    
    /**
     * @When /^I call Router::add\(closure\) on "([^"]*)"$/
     */
    public function iCallRouterAddClosureOn($closureRoute)
    {
        Router::add(new Route($closureRoute, 'GET', function($id) {
            return 'I\'m a closure with param : '.$id;
        }));
    }
    
    /**
     * @When /^I create a direct Route "([^"]*)"$/
     */
    public function iCreateADirectRoute($directRoutePattern)
    {
        $this->currentRoute = new Route($directRoutePattern, 'GET', 'Directory\\Directory#tellMeHow');
    }
    
    /**
     * @When /^I create a Route "([^"]*)" with filter$/
     */
    public function iCreateARouteWithFilter($routePattern)
    {
        $this->currentRoute = new Route($routePattern, null, null, function() {
            return $_SESSION['access'] === '1';
        });
    }
    
    /**
     * @Given /^I call execute$/
     */
    public function iCallExecute()
    {
        $this->getMainContext()->output = $this->currentRoute->execute();
    }
    
    /**
     * @Given /^I initiate SESSION "([^"]*)" whith "([^"]*)"$/
     */
    public function iInitiateSessionWhith($sessionName, $sessionValue)
    {
        $_SESSION[$sessionName] = $sessionValue;
    }

}
