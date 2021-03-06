<?php
declare(strict_types = 1);

namespace Innmind\Reflection\InjectionStrategy;

use Innmind\Reflection\InjectionStrategyInterface;
use Innmind\Reflection\Exception\LogicException;
use Innmind\Immutable\StringPrimitive;

/**
 * Looks for a method named exactly like the property
 *
 * Example:
 * <code>
 * private $foo;
 *
 * public function foo($foo);
 * </code>
 */
class NamedMethodStrategy implements InjectionStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($object, string $property, $value): bool
    {
        $refl = new \ReflectionObject($object);

        $property = (string) (new StringPrimitive($property))
            ->camelize()
            ->lcfirst();

        return $refl->hasMethod($property) &&
            $refl->getMethod($property)->getNumberOfParameters() > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function inject($object, string $property, $value)
    {
        if (!$this->supports($object, $property, $value)) {
            throw new LogicException;
        }

        $property = (string) (new StringPrimitive($property))
            ->camelize()
            ->lcfirst();

        $object->$property($value);
    }
}
