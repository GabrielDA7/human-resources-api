Feature: _Offer_
  Background:
    Given the following fixtures files are loaded:
      | user |
      | offer |

  Scenario: get all offers
    When I authenticate with user "{{ user_1.email }}" and password "password"
    And I request "GET /offers"
    Then the response status code should be 200
    And the "hydra:totalItems" property should be an integer equalling "1"

  Scenario: Create offer without recruiter role
    When I authenticate with user "{{ user_1.email }}" and password "password"
    And I have the payload
    """
    {
        "name": "dev offer",
        "companyDescription": "string",
        "description": "string",
        "date": "2020-06-24T19:52:33.038Z",
        "type": "string",
        "location": "string"
    }
    """
    And I request "POST /offers"
    Then the response status code should be 403

  Scenario: Create offer with recruiter role
    When I authenticate with user "{{ user_recruiter.email }}" and password "password"
    And I have the payload
    """
    {
        "name": "dev offer",
        "companyDescription": "string",
        "description": "string",
        "date": "2020-06-24T19:52:33.038Z",
        "type": "string",
        "location": "string"
    }
    """
    And I request "POST /offers"
    Then the response status code should be 201
