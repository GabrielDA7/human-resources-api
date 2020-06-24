<?php

namespace App\Tests\Behat\Context\Traits;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator;

trait AuthTrait
{
    /**
     * The token to use with HTTP authentication
     *
     * @var string
     */
    protected $token;

    /**
     * @Given /^I authenticate with user "([^"]*)" and password "([^"]*)"$/
     */
    public function iAuthenticateWithEmailAndPassword($email, $password)
    {
        $this->requestPayload = ["email" => $email, "password" => $password];
        $this->iRequest("POST", "/authentication_token");
        $this->token = $this->arrayGet($this->getScopePayload(), "token", true);
    }
}
