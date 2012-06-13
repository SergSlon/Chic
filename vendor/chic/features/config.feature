Feature: Config
Allow to read configuration files and return the values.
Use specified environment (with param or configuration)

Scenario: Get configuration
    Given I init Config with environment ""
    When I call Config->get("i18n.default")
    Then I should get "en"

Scenario: Get configuration value on specified environment
    Given I init Config with environment "dev"
    When I call Config->get("i18n.default")
    Then I should get "fr"
    And I call Config->get("i18n.langs")
    Then I should get array "en", "fr"

Scenario: Get configuration value on automatic use environment
    Given $_SERVER['SERVER_NAME'] php array contain "localhost"
    And I init Config with environment ""
    When I call Config->get("i18n.default")
    Then I should get "fr"