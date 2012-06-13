<?php

use Behat\Behat\Context\BehatContext,
    \Chic\I18n;

class I18nContext extends BehatContext
{
    private $i18nFolderPath;
    
    public function __construct(array $parameters)
    {
        $this->i18nFolderPath = $parameters['i18nPath'];
    }

    /**
     * @Given /^I init I18n with lang "([^"]*)"$/
     */
    public function iInitI18nWithLang($lang)
    {
        $this->getMainContext()->getSubcontext('config_context')->iInitConfigWithEnvironment('');
        
        if ($lang != '') {
            I18n::init($lang, $this->i18nFolderPath);
        } else {
            I18n::init(null, $this->i18nFolderPath);
        }
    }

    /**
     * @When /^I call I18n->t\("home\.title"\)$/
     */
    public function iCallI18nTHomeTitle()
    {
        $this->getMainContext()->output = I18n::t('home.title');
    }
    
    /**
     * @When /^I call I18n->t\("home\.title", array\(\), "fr"\)$/
     */
    public function iCallI18nTHomeTitleArrayFr()
    {
        $this->getMainContext()->output = I18n::t('home.title', array(), 'fr');
    }

    /**
     * @When /^I call I18n->t\("home\.content", array\("name" => "Michel", "name2" => "Gerard"\)\)$/
     */
    public function iCallI18nTHomeContentArrayNameMichel()
    {
        $this->getMainContext()->output = I18n::t('home.content', array('name' => 'Michel', 'name2' => 'Gerard'));
    }
}
