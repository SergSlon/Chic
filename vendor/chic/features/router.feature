Feature: Router
Gather project routes and execute them.
Apply filter on routes.

Scenario: Add simplest route
    When I call Router::add(new Route("posts"))
    And I get the route "posts"
    Then I should get Namespace = "Posts"
    And I should get Controller = "Posts"
    And I should get Method = "default"
    And I should get HttpMethods = "GET","POST"

Scenario: Add controller/method route
    When I call Router::add(new Route("directory/test"))
    And I get the route "directory/test"
    Then I should get Namespace = "Directory"
    And I should get Controller = "Directory"
    And I should get Method = "test"

Scenario: Add "method as parameter" route
    When I call Router::add(new Route("directory/(:method)"))
    And I call Router::match("directory/tellMeHow")
    Then I should get "How"

Scenario: Valid (:num) routes
    When I create a Route "test/(:method)/(:num)"
    And I call match for "test/call/2"
    Then I should get Method = "call"
    And I should get Args = "2"
    And I call match for "test/call/notNum"
    Then I should get match "false"

Scenario: Valid (:controller) routes
    When I create a Route "(:controller)"
    And I call match for "test"
    Then I should get Namespace = "Test"
    And I should get Controller = "Test"
    And I should get Method = "default"

Scenario: Valid (:controller)/(:method) routes
    When I create a Route "(:controller)/(:method)" 
    And I call match for "toast/called"
    Then I should get Namespace = "Toast"
    And I should get Controller = "Toast"    
    Then I should get Method = "called"
    And I call match for "test/call/notNum"
    Then I should get match "false"

Scenario: Define closure route
    When I call Router::add(closure) on "admin/my-content(:num)"
    And I call Router::match("admin/my-content2")
    Then I should get "I'm a closure with param : 2"

Scenario: Define direct route
    When I create a direct Route "directory/tellMeHow"
    And I call execute
    Then I should get "How"

Scenario: Apply a filter on route
    When I create a Route "filteredarea/action" with filter
    And I call execute
    Then I should get "false"
    And I initiate SESSION "access" whith "1"
    And I call execute
    Then I should get "in a filtered world"