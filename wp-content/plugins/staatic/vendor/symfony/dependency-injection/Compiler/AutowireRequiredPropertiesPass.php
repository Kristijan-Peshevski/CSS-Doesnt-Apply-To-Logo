<?php

namespace Staatic\Vendor\Symfony\Component\DependencyInjection\Compiler;

use ReflectionNamedType;
use Staatic\Vendor\Symfony\Component\DependencyInjection\ContainerInterface;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Definition;
use Staatic\Vendor\Symfony\Component\DependencyInjection\TypedReference;
use Staatic\Vendor\Symfony\Contracts\Service\Attribute\Required;
class AutowireRequiredPropertiesPass extends AbstractRecursivePass
{
    /**
     * @param mixed $value
     * @param bool $isRoot
     * @return mixed
     */
    protected function processValue($value, $isRoot = \false)
    {
        $value = parent::processValue($value, $isRoot);
        if (!$value instanceof Definition || !$value->isAutowired() || $value->isAbstract() || !$value->getClass()) {
            return $value;
        }
        if (!($reflectionClass = $this->container->getReflectionClass($value->getClass(), \false))) {
            return $value;
        }
        $properties = $value->getProperties();
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if (!($type = $reflectionProperty->getType()) instanceof ReflectionNamedType) {
                continue;
            }
            $doc = \false;
            if (!$reflectionProperty->getAttributes(Required::class) && (\false === ($doc = $reflectionProperty->getDocComment()) || \false === \stripos($doc, '@required') || !\preg_match('#(?:^/\\*\\*|\\n\\s*+\\*)\\s*+@required(?:\\s|\\*/$)#i', $doc))) {
                continue;
            }
            if ($doc) {
                trigger_deprecation('symfony/dependency-injection', '6.3', 'Using the "@required" annotation on property "%s::$%s" is deprecated, use the "Symfony\\Contracts\\Service\\Attribute\\Required" attribute instead.', $reflectionProperty->class, $reflectionProperty->name);
            }
            if (\array_key_exists($name = $reflectionProperty->getName(), $properties)) {
                continue;
            }
            $type = $type->getName();
            $value->setProperty($name, new TypedReference($type, $type, ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $name));
        }
        return $value;
    }
}
