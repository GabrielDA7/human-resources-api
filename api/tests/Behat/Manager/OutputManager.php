<?php

namespace App\Tests\Behat\Manager;

use Symfony\Component\Console\Output\ConsoleOutput;

class OutputManager
{
    /**
     * @var ConsoleOutput
     */
    private $output;

    private $useFancyExceptionReporting = true;

    /**
     * Returns the prettified equivalent of the input if the input is valid JSON.
     * Returns the original input if it is not valid JSON.
     *
     * @param $input
     *
     * @return string
     * @throws \Exception
     */
    public function prettifyJson($input)
    {
        $decodedJson = json_decode($input);

        if($decodedJson === null) { // JSON is invalid
            return $input;
        }

        return json_encode($decodedJson, JSON_PRETTY_PRINT);
    }


    public function printDebug($string)
    {
        $this->getOutput()->writeln($string);
    }


    /**
     * @return ConsoleOutput
     */
    public function getOutput()
    {
        if ($this->output === null)  {
            $this->output = new ConsoleOutput();
        }

        return $this->output;
    }
}
