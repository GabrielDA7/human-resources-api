Feature: _User_
  Background:
    Given the following fixtures files are loaded:
      | user |

  Scenario: Auth
    When I have the payload
    """
    {
      "email": "{{ user_1.email }}",
      "password": "password"
    }
    """
    And I request "POST /authentication_token"
    Then the response status code should be 200
    And the "token" property should be a string

  Scenario: Post
    When I authenticate with user "user@gmail.com" and password "password"

  Scenario: Create valid user
    When I have the payload
    """
    {
      "email": "valid.user@gmail.com",
      "roles": [
        "ROLE_USER"
      ],
      "password": "password"
    }
    """
    And I request "POST /users"
    Then the response status code should be 201
    And the "id" property should be an integer
    And the "email" property should be a string equalling "valid.user@gmail.com"

  Scenario: Expecting error on email already exists
    When I have the payload
    """
    {
      "email": "{{ user_1.email }}",
      "roles": [
        "ROLE_USER"
      ],
      "password": "password"
    }
    """
    And I request "POST /users"
    Then the response status code should be 400
    And the "error" property should be a string equalling "EmailAlreadyExistsException"

  Scenario: Expecting error on bad email format
    When I have the payload
    """
    {
      "email": "bad.email",
      "roles": [
        "ROLE_USER"
      ],
      "password": "password"
    }
    """
    And I request "POST /users"
    Then the response status code should be 400
    And the "email" property should be a string equalling "This value is not a valid email address."


  Scenario: Expecting error when trying to create an user admin
    When I have the payload
    """
    {
      "email": "valid.user@gmail.com",
      "roles": [
        "ROLE_ADMIN"
      ],
      "password": "password"
    }
    """
    And I request "POST /users"
    Then the response status code should be 400
    And the "roles" property should be a string equalling "This value can't be ROLE_ADMIN."

  Scenario: get user
    When I request "GET /users/{{ user_1.id }}"
    Then the response status code should be 200
    And the "email" property should be a string equalling "{{ user_1.email }}"
    And the "password" property should not exist

  Scenario: get user that does not exists
    When I request "GET /users/-1"
    Then the response status code should be 404

  Scenario: confirm valid token
    When I request "GET /users/confirm/{{ user_not_confirmed.confirmationToken }}"
    Then the response status code should be 200
    And the "id" property should be an integer equalling "{{ user_not_confirmed.id }}"
    And the "enabled" property should be a boolean equalling "true"
    And the "confirmationToken" property should be null

  Scenario: confirm bad token
    When I request "GET /users/confirm/bad_token{{ user_not_confirmed.confirmationToken }}"
    Then the response status code should be 400

  Scenario: get all users
    When I request "GET /users"
    Then the response status code should be 200
    And the "hydra:totalItems" property should be an integer equalling "3"

  Scenario: update logged user
    When I authenticate with user "{{ user_1.email }}" and password "password"
    And I have the payload
    """
    {
      "email": "new.email@gmail.com"
    }
    """
    And I set the "content-type" header to be "application/merge-patch+json"
    And I request "PATCH /users/{{ user_1.id }}"
    Then the response status code should be 200
    And the "id" property should be an integer equalling "{{ user_1.id }}"
    And the "email" property should be a string equalling "new.email@gmail.com"

  Scenario: update other user without admin role
    When I authenticate with user "{{ user_1.email }}" and password "password"
    And I have the payload
    """
    {
      "email": "new.email@gmail.com"
    }
    """
    And I set the "content-type" header to be "application/merge-patch+json"
    And I request "PATCH /users/{{ user_admin_1.id }}"
    Then the response status code should be 403

  Scenario: update other user with admin role
    When I authenticate with user "{{ user_admin_1.email }}" and password "password"
    And I have the payload
    """
    {
      "email": "new.email@gmail.com"
    }
    """
    And I set the "content-type" header to be "application/merge-patch+json"
    And I request "PATCH /users/{{ user_1.id }}"
    Then the response status code should be 200
    And the "id" property should be an integer equalling "{{ user_1.id }}"
    And the "email" property should be a string equalling "new.email@gmail.com"

  Scenario: delete logged user
    When I authenticate with user "{{ user_1.email }}" and password "password"
    And I request "DELETE /users/{{ user_1.id }}"
    Then the response status code should be 204
