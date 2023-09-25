<?php

namespace Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator;

use Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits\AbstractTrait;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits\AutoconfigureTrait;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits\AutowireTrait;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits\BindTrait;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits\DecorateTrait;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits\DeprecateTrait;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits\LazyTrait;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits\PublicTrait;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits\ShareTrait;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Loader\Configurator\Traits\TagTrait;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Definition;
class FromCallableConfigurator extends AbstractServiceConfigurator
{
    use AbstractTrait;
    use AutoconfigureTrait;
    use AutowireTrait;
    use BindTrait;
    use DecorateTrait;
    use DeprecateTrait;
    use LazyTrait;
    use PublicTrait;
    use ShareTrait;
    use TagTrait;
    public const FACTORY = 'services';
    /**
     * @var ServiceConfigurator
     */
    private $serviceConfigurator;
    public function __construct(ServiceConfigurator $serviceConfigurator, Definition $definition)
    {
        $this->serviceConfigurator = $serviceConfigurator;
        parent::__construct($serviceConfigurator->parent, $definition, $serviceConfigurator->id);
    }
    public function __destruct()
    {
        $this->serviceConfigurator->__destruct();
    }
}
