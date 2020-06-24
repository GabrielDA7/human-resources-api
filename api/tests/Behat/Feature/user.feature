Feature: _Stack_
  Background:
    Given the following fixtures files are loaded:
      | user |

  Scenario: Auth
    When I have the payload
    """
    {
      "email": "user@gmail.com",
      "password": "password"
    }
    """
    And I request "POST /authentication_token"
    Then the response status code should be 200
    And the "token" property should be a string

  Scenario: Post
    When I authenticate with user "user@gmail.com" and password "password"
