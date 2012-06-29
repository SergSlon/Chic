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
        $this->useContext('router_context', new RouterContext(array(
            'routeFolderPath' => __DIR__.DS.'data'.DS.'routes'
        )));
    }
    
    /**
     * @Then /^I should get "([^"]*)"$/
     */
    public function iShouldGet($string)
    {
        if ($string === 'false') {
            $this->assertEquals($this->output, false);
        } else if ($string === 'true') {
            $this->assertEquals($this->output, true);
        } else {
            $this->assertEquals($this->output, $string);
        }
    }
    
    public function assertEquals($val1, $val2)
    {
        $throwException = true;
            
        if (is_array($val1) && is_array($val2)) {
            if (sizeof(array_diff_assoc($val1, $val2)) === 0) {
                $throwException = false;
            }
        } else {
            if ($val1 === $val2) {
                $throwException = false;
            }
        }
        
        if ($throwException) {
            throw new Exception(
                "Actual value is : ".print_r($val1, true).PHP_EOL."Waiting for : ".print_r($val2, true)
            );
        }
    }
}