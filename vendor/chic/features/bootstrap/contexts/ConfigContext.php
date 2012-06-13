<?php

use Behat\Behat\Context\BehatContext,
    Chic\Config;

class ConfigContext extends BehatContext
{
    public $configFolderPath;
    
    public function __construct(array $parameters)
    {
        $this->configFolderPath = $parameters['configPath'];
    }

    /**
     * @When /^I call Config->get\("([^"]*)"\)$/
     */
    public function iCallConfigGeti18nDefault($key)
    {
        $this->getMainContext()->output = Chic\Config::get($key);
    }

    /**
     * @Given /^I init Config with environment "([^"]*)"$/
     */
    public function iInitConfigWithEnvironment($env)
    {
        if ($env !== '') {
            Config::init($env, $this->configFolderPath);
        } else {
            Config::init(null, $this->configFolderPath);
        }
    }

    /**
     * @Given /^\$_SERVER\[\'SERVER_NAME\'\] php array contain "([^"]*)"$/
     */
    public function serverServerNamePhpArrayContain($string)
    {
        $_SERVER['SERVER_NAME'] = $string;
    }

    /**
     * @Then /^I should get array "([^"]*)", "([^"]*)"$/
     */
    public function iShouldGetArrayEn($lang1, $lang2)
    {
        if (sizeof(array_diff_assoc($this->getMainContext()->output, array('en','fr'))) !== 0) {
            throw new Exception(
                "Actual value is : ".print_r($this->getMainContext()->output, true).PHP_EOL."Waiting for : ".print_r(array('en', 'fr'), true)
            );
        } 
    }
}
