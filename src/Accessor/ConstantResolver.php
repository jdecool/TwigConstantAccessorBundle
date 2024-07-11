<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\Accessor;

/**
 * @phpstan-type ConstantConfiguration array{class: class-string, alias?: ?string, matches?: ?string}
 */
class ConstantResolver
{
    /**
     * @param list<ConstantConfiguration> $classes
     *
     * @return array<string, mixed>
     */
    public function fromClassList(array $classes): array
    {
        $constants = [];

        foreach ($classes as $classConfiguration) {
            $class = $classConfiguration['class'];
            if (!class_exists($class) && !interface_exists($class) && !enum_exists($class)) {
                throw new \LogicException("Class $class does not exist.");
            }

            $reflection = new \ReflectionClass($class);

            $key = $this->getClassKey($reflection, $classConfiguration);
            if (isset($constants[$key])) {
                throw new \LogicException("$key class or alias already defined and cannot be override.");
            }

            $values = $this->extractConstants($reflection, $classConfiguration);
            if (empty($values)) {
                continue;
            }

            $constants[$key] = $values;
        }

        return $constants;
    }

    /**
     * @param ConstantConfiguration $classConfiguration
     */
    private function getClassKey(\ReflectionClass $reflectionClass, array $classConfiguration): string
    {
        if (!empty($classConfiguration['alias'])) {
            return $classConfiguration['alias'];
        }

        return $reflectionClass->getShortName();
    }

    /**
     * @param ConstantConfiguration $classConfiguration
     *
     * @return array<string, mixed>
     */
    private function extractConstants(\ReflectionClass $reflectionClass, array $classConfiguration): array
    {
        $values = [];
        foreach ($reflectionClass->getConstants() as $constant => $value) {
            $reflectionConstant = new \ReflectionClassConstant($reflectionClass->getName(), $constant);
            if (!$this->matchVisibility($reflectionConstant) || !$this->matchRegexp($constant, $classConfiguration)) {
                continue;
            }

            if ($value instanceof \BackedEnum) {
                $value = $value->value;
            }

            $values[$constant] = $value;
        }

        return $values;
    }

    private function matchVisibility(\ReflectionClassConstant $reflectionConstant): bool
    {
        return $reflectionConstant->isPublic();
    }

    /**
     * @param ConstantConfiguration $classConfiguration
     */
    private function matchRegexp(string $constant, array $classConfiguration): bool
    {
        $pattern = $classConfiguration['matches'] ?? null;
        if (null === $pattern) {
            return true;
        }

        if (false === ($matches = preg_match($pattern, $constant))) {
            throw new \InvalidArgumentException("RegExp rule \"$pattern\" is not valid.");
        }

        return (bool) $matches;
    }
}
