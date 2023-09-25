<?php

namespace Staatic\Vendor\Symfony\Component\DependencyInjection\Attribute;

use Attribute;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Reference;
#[Attribute(Attribute::TARGET_PARAMETER)]
class AutowireServiceClosure extends Autowire
{
    public function __construct(string $service)
    {
        parent::__construct(new ServiceClosureArgument(new Reference($service)));
    }
}
