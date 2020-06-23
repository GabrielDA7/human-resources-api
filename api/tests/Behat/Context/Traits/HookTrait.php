<?php

namespace App\Tests\Behat\Context\Traits;

use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;

trait HookTrait
{
    /**
     * @BeforeSuite
     */
    public static function beforeSuite()
    {
        //var_dump("before suite");
        StaticDriver::setKeepStaticConnections(true);
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario()
    {
        //var_dump("before sena");
        /** Init default content-type */
        $this->requestHeaders["content-type"] = "application/ld+json";
        StaticDriver::beginTransaction();
    }

    /**
     * @AfterScenario
     */
    public function afterScenario()
    {
        //var_dump("after sena");
        $this->requestHeaders["content-type"] = "";
        StaticDriver::rollBack();
    }

    /**
     * @AfterSuite
     */
    public static function afterSuite()
    {
        //var_dump("after suite");
        StaticDriver::setKeepStaticConnections(false);
    }
}
