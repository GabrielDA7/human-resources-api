<?php

namespace App\Tests\Behat\Context\Traits;

use App\Tests\Behat\Manager\FixtureManager;
use Behat\Gherkin\Node\TableNode;

trait FixturesTrait
{
    /**
     * @var FixtureManager
     */
    private FixtureManager $fixtureManager;

    /**
     * @Given /^the fixtures file "([^"]*)" is loaded$/
     */
    public function theFixturesFileIsLoaded(string $file)
    {
        $this->fixtureManager->load(['./fixtures/' . $file . '.yml']);
    }

    /**
     * @Given the following fixtures files are loaded:
     */
    public function theFixturesFilesAreLoaded(TableNode $table)
    {
        $files = array_map(fn($row) => './fixtures/' . $row[0] . '.yml', $table->getRows());

        $this->fixtureManager->load($files);
    }
}
