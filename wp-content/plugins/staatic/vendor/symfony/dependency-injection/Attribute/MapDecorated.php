<?php

namespace Staatic\Vendor\Symfony\Component\DependencyInjection\Attribute;

use Attribute;
trigger_deprecation('symfony/dependency-injection', '6.3', 'The "%s" class is deprecated, use "%s" instead.', MapDecorated::class, AutowireDecorated::class);
#[Attribute(Attribute::TARGET_PARAMETER)]
class MapDecorated
{
}
