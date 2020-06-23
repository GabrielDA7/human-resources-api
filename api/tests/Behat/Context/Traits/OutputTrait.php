<?php

namespace App\Tests\Behat\Context\Traits;

use App\Tests\Behat\Manager\OutputManager;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Testwork\Tester\Result\TestResult;
use Symfony\Component\DomCrawler\Crawler;

trait OutputTrait
{
    /**
     * @var OutputManager $outputManager
     */
    private OutputManager $outputManager;

    /**
     * @Given /^print last response$/
     */
    public function printLastResponse()
    {
        if ($this->lastResponse) {
            // Build the first line of the response (protocol, protocol version, statuscode, reason phrase)
            $response = 'HTTP/1.1 ' . $this->lastResponse->getStatusCode() . "\r\n";

            // Add the headers
            foreach($this->lastResponse->getHeaders() as $key => $value) {
                $response .= sprintf("%s: %s\r\n", $key, $value[0]);
            }

            // Add the response body
            $response .= $this->outputManager->prettifyJson($this->lastResponse->getContent(false));

            // Print the response
            $this->outputManager->printDebug($response);
        }
    }

    /**
     * @AfterScenario
     */
    public function printLastResponseOnError(AfterScenarioScope $scope)
    {
        if($scope->getTestResult()->getResultCode() == TestResult::FAILED) {
            if($this->lastResponse === null) {
                return;
            }

            $body = $this->lastResponse->getContent(false);

            $this->outputManager->printDebug('');
            $this->outputManager->printDebug('<error>Failure!</error> when making the following request:');
            $this->outputManager->printDebug(sprintf('<comment>%s</comment>: <info>%s</info>', $this->lastRequest->getMethod(), $this->lastRequest->getUri())."\n");

            if (in_array($this->lastResponse->getHeaders()['content-type'], ['application/json', 'application/problem+json'])) {
                $this->outputManager->printDebug($this->prettifyJson($body));
            } else {
                // the response is HTML - see if we should print all of it or some of it
                $isValidHtml = strpos($body, '</body>') !== false;

                if($isValidHtml) {
                    $this->outputManager->printDebug('<error>Failure!</error> Below is a summary of the HTML response from the server.');

                    // finds the h1 and h2 tags and prints them only
                    $crawler = new Crawler($body);
                    foreach($crawler->filter('h1, h2')->extract(array('_text')) as $header) {
                        $this->outputManager->printDebug(sprintf('        ' . $header));
                    }
                } else {
                    $this->outputManager->printDebug($body);
                }
            }
        }
    }
}
