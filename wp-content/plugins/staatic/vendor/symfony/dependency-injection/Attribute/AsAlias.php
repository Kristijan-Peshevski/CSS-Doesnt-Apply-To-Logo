<?php

namespace Staatic\Vendor\Symfony\Component\DependencyInjection\Attribute;

use Attribute;
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class AsAlias
{
    /**
     * @var string|null
     */
    public $id;
    /**
     * @var bool
     */
    public $public = \false;
    public function __construct(?string $id = null, bool $public = \false)
    {
        $this->id = $id;
        $this->public = $public;
    }
}
