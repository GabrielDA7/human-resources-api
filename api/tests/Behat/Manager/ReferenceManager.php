<?php

namespace App\Tests\Behat\Manager;

class ReferenceManager
{
    const PATH_SEPARATOR = ".";
    const REFERENCE_PREFIX = "{{";
    const REFERENCE_SUFFIX = "}}";
    const REFERENCE_REGEX = "/{{(.*?)}}/";

    public function replaceReferences(array $fixtureContext, string $valueWithReferences): string {
        $countReferenceToReplace = substr_count($valueWithReferences, self::REFERENCE_PREFIX);
        for ($i = 0; $i < $countReferenceToReplace; $i++) {
            $valueWithReferences = $this->replaceReference($fixtureContext, $valueWithReferences);
        }
        return $valueWithReferences;
    }

    private function replaceReference(array $fixtureContext, string $valueWithReferences) {
        $ref = $this->extractFirstRef($valueWithReferences);
        $path = explode(self::PATH_SEPARATOR, $ref);
        $value = null;
        for ($pathStep = 0; $pathStep < count($path); $pathStep++) {
            $value = $this->findValueForCurrentStep($pathStep, $fixtureContext, $path, $value);
        }
        return $this->replaceFirstOccurrence($value, $valueWithReferences);
    }

    private function findValueForCurrentStep(int $pathStep, array $fixtureContext, array $path, $value) {
        if ($pathStep == 0)
            return $this->findEntity($fixtureContext, $path, $pathStep);
        $getter = $this->createGetter($path[$pathStep]);
        return $value->$getter();
    }

    private function replaceFirstOccurrence($value, string $valueWithReferences) {
        return preg_replace(self::REFERENCE_REGEX, $value, $valueWithReferences, 1);
    }

    private function createGetter($property): string {
        return "get" . ucfirst($property);
    }

    private function extractFirstRef($string) : string {
        preg_match(self::REFERENCE_REGEX, $string, $ref);
        $ref = str_replace(" ", "", $ref[0]);
        $ref = str_replace(self::REFERENCE_PREFIX, "", $ref);
        return str_replace(self::REFERENCE_SUFFIX, "", $ref);
    }

    private function findEntity(array $fixtureContext, array $path, string $pathStep) {
        return $fixtureContext[$path[$pathStep]];
    }
}
