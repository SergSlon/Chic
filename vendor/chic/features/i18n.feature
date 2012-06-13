Feature: I18n
Allow strings to be internationnalized and variable

Scenario: Get string for default lang
    Given I init I18n with lang ""
    When I call I18n->t("home.title")
    Then I should get "The Hello world"

Scenario: Get string for initialized lang
    Given I init I18n with lang "fr"
    When I call I18n->t("home.title")
    Then I should get "Le coucou du monde"

Scenario: Get string for specified lang
    Given I init I18n with lang ""
    When I call I18n->t("home.title", array(), "fr")
    Then I should get "Le coucou du monde"

Scenario: Get variable string
    When I call I18n->t("home.content", array("name" => "Michel", "name2" => "Gerard"))
    Then I should get "Say hello to Michel, Michel and Gerard"