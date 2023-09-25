<?php

namespace Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits;

trait ConstructorTrait
{
    /**
     * @param string $constructor
     * @return static
     */
    public final function constructor($constructor)
    {
        $this->definition->setFactory([null, $constructor]);
        return $this;
    }
}
