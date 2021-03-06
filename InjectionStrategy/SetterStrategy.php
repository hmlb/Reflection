<?php
declare(strict_types = 1);

namespace Innmind\Reflection\InjectionStrategy;

use Innmind\Reflection\InjectionStrategyInterface;
use Innmind\Reflection\Exception\LogicException;
use Innmind\Immutable\StringPrimitive;

class SetterStrategy implements InjectionStrategyInterface
{
    private $setter;

    public function __construct()
    {
        $this->setter = new StringPrimitive('set%s');
    }

    /**
     * {@inheritdoc}
     */
    public function supports($object, string $property, $value): bool
    {
        $refl = new \ReflectionObject($object);

        return $refl->hasMethod(
            (string) $this->setter->sprintf(
                (string) (new StringPrimitive($property))->camelize()
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function inject($object, string $property, $value)
    {
        if (!$this->supports($object, $property, $value)) {
            throw new LogicException;
        }

        $setter = (string) $this->setter->sprintf(
            (string) (new StringPrimitive($property))->camelize()
        );
        $object->$setter($value);
    }
}
