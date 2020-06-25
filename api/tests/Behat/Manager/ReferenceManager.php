<?php

namespace App\Tests\Behat\Manager;

class ReferenceManager
{
    const PATH_SEPARATOR = ".";
    const REFERENCE_PREFIX = "{{";
    const REFERENCE_SUFFIX = "}}";
    const REFERENCE_REGEX = "/{{(.*?)}}/";

    public function replaceReferences(array $fixtureContext, string $reference): string {
        $countReferenceToReplace = substr_count($reference, self::REFERENCE_PREFIX);
        for ($i = 0; $i < $countReferenceToReplace; $i++) {
            $reference = $this->replaceReference($fixtureContext, $reference);
        }
        return $reference;
    }

    private function replaceReference(array $fixtureContext, string $reference) {
        $ref = $this->extractRef($reference);
        $path = explode(self::PATH_SEPARATOR, $ref);
        $result = null;
        for ($pathStep = 0; $pathStep < count($path); $pathStep++) {
            if ($pathStep == 0)
                $result = $this->findEntity($fixtureContext, $path, $pathStep);
            else {
                $getter = $this->createGetter($path[$pathStep]);
                $result = $result->$getter();
            }
        }
        return preg_replace(self::REFERENCE_REGEX, $result, $reference, 1);
    }

    private function createGetter($property): string {
        return "get" . ucfirst($property);
    }

    private function extractRef($string) : string {
        preg_match(self::REFERENCE_REGEX, $string, $ref);
        $ref = str_replace(" ", "", $ref[0]);
        $ref = str_replace(self::REFERENCE_PREFIX, "", $ref);
        return str_replace(self::REFERENCE_SUFFIX, "", $ref);
    }

    private function findEntity(array $fixtureContext, array $path, string $pathStep) {
        return $fixtureContext[$path[$pathStep]];
    }
}
