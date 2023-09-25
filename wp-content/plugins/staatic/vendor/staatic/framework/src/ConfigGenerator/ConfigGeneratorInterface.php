<?php

namespace Staatic\Framework\ConfigGenerator;

use Staatic\Framework\Result;
interface ConfigGeneratorInterface
{
    /**
     * @param Result $result
     */
    public function processResult($result) : void;
    public function getFiles() : array;
}
