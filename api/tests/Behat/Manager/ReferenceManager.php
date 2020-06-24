<?php

namespace App\Tests\Behat\Manager;

class ReferenceManager
{
    public function replaceReferences(array $fixtureContext, string $reference): string {
        $countRef = substr_count($reference, "{{");
        for ($i = 0; $i < $countRef; $i++) {
            $ref = $this->extractRef($reference);
            $path = explode(".", $ref);
            $result = null;
            for ($pathStep = 0; $pathStep < count($path); $pathStep++) {
                if ($pathStep == 0)
                    $result = $fixtureContext[$path[$pathStep]];
                else {
                    $getter = $this->createGetter($path[$pathStep]);
                    $result = $result->$getter();
                }
            }
            $reference = preg_replace('/{{(.*?)}}/', $result, $reference, 1);
        }
        return $reference;
    }

    private function createGetter($property) {
        return "get" . ucfirst($property);
    }

    private function extractRef($string) : string {
        preg_match('/{{(.*?)}}/', $string, $ref);
        $ref = str_replace(" ", "", $ref[0]);
        $ref = str_replace("{{", "", $ref);
        return str_replace("}}", "", $ref);
    }
}
