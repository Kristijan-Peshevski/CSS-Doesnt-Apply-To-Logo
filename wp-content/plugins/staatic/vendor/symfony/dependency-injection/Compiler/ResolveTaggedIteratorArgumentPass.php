<?php

namespace Staatic\Vendor\Symfony\Component\DependencyInjection\Compiler;

use Staatic\Vendor\Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
class ResolveTaggedIteratorArgumentPass extends AbstractRecursivePass
{
    use PriorityTaggedServiceTrait;
    /**
     * @param mixed $value
     * @param bool $isRoot
     * @return mixed
     */
    protected function processValue($value, $isRoot = \false)
    {
        if (!$value instanceof TaggedIteratorArgument) {
            return parent::processValue($value, $isRoot);
        }
        $exclude = $value->getExclude();
        if ($value->excludeSelf()) {
            $exclude[] = $this->currentId;
        }
        $value->setValues($this->findAndSortTaggedServices($value, $this->container, $exclude));
        return $value;
    }
}
