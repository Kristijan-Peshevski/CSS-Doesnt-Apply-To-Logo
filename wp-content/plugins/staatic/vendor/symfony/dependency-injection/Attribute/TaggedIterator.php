<?php

namespace Staatic\Vendor\Symfony\Component\DependencyInjection\Attribute;

use Attribute;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
#[Attribute(Attribute::TARGET_PARAMETER)]
class TaggedIterator extends Autowire
{
    /**
     * @var string
     */
    public $tag;
    /**
     * @var string|null
     */
    public $indexAttribute;
    /**
     * @var string|null
     */
    public $defaultIndexMethod;
    /**
     * @var string|null
     */
    public $defaultPriorityMethod;
    /**
     * @var string|mixed[]
     */
    public $exclude = [];
    /**
     * @var bool
     */
    public $excludeSelf = \true;
    /**
     * @param string|mixed[] $exclude
     */
    public function __construct(string $tag, ?string $indexAttribute = null, ?string $defaultIndexMethod = null, ?string $defaultPriorityMethod = null, $exclude = [], bool $excludeSelf = \true)
    {
        $this->tag = $tag;
        $this->indexAttribute = $indexAttribute;
        $this->defaultIndexMethod = $defaultIndexMethod;
        $this->defaultPriorityMethod = $defaultPriorityMethod;
        $this->exclude = $exclude;
        $this->excludeSelf = $excludeSelf;
        parent::__construct(new TaggedIteratorArgument($tag, $indexAttribute, $defaultIndexMethod, \false, $defaultPriorityMethod, (array) $exclude, $excludeSelf));
    }
}
