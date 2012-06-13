<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class FeatureContext extends BehatContext
{
    public $output;
    
    public function __construct(array $parameters)
    {
        //Initialize
        require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'defines.php';
        $_SERVER['SERVER_NAME'] = '';
        
        $this->useContext('config_context', new ConfigContext(array(
            'configPath' => __DIR__.DS.'data'.DS.'config'
        )));
        $this->useContext('i18n_context', new I18nContext(array(
            'i18nPath' => __DIR__.DS.'data'.DS.'lang'
        )));
    }
    
    /**
     * @Then /^I should get "([^"]*)"$/
     */
    public function iShouldGet($string)
    {
        if ((string) $string !== $this->output) {
            throw new Exception(
                "Actual value is : ".$this->output.PHP_EOL."Waiting for : ".$string
            );
        }
    }
}