#Feature: _Stack_
#  Background:
#    Given the following fixtures files are loaded:
#      | tag       |
#      | stack     |
#
#  Scenario: Post stack
#    Given I authenticate with user "<string>" and password "<string>"
#    Given I have the payload
#    """
#    {
#        "name": "Coucou",
#        "description": "description de fou!!!"
#    }
#    """
#    Given I request "POST /stacks"
#    And the response status code should be 201
#    And the "name" property should equal "Coucou"
#
#  Scenario: Get collection
#    Given I request "GET /stacks"
#    And the response status code should be 200
#    And the "hydra:totalItems" property should be an integer equalling "11"
#    And scope into the "hydra:search" property
#    And the "hydra:mapping" property should be an integer
#    And reset scope
#    Then print last response
