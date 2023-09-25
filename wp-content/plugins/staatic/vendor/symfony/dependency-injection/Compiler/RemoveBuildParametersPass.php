<?php

namespace Staatic\Vendor\Symfony\Component\DependencyInjection\Compiler;

use Staatic\Vendor\Symfony\Component\DependencyInjection\ContainerBuilder;
class RemoveBuildParametersPass implements CompilerPassInterface
{
    /**
     * @var mixed[]
     */
    private $removedParameters = [];
    /**
     * @param ContainerBuilder $container
     */
    public function process($container)
    {
        $parameterBag = $container->getParameterBag();
        $this->removedParameters = [];
        foreach ($parameterBag->all() as $name => $value) {
            if ('.' === ($name[0] ?? '')) {
                $this->removedParameters[$name] = $value;
                $parameterBag->remove($name);
                $container->log($this, \sprintf('Removing build parameter "%s".', $name));
            }
        }
    }
    public function getRemovedParameters() : array
    {
        return $this->removedParameters;
    }
}
