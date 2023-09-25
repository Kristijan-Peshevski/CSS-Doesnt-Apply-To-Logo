<?php

namespace Staatic\Vendor\Symfony\Component\CssSelector\Node;

interface NodeInterface
{
    public function getNodeName() : string;
    public function getSpecificity() : Specificity;
    public function __toString() : string;
}
