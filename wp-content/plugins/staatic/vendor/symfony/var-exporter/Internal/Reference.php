<?php

namespace Staatic\Vendor\Symfony\Component\VarExporter\Internal;

class Reference
{
    public $id;
    public $value;
    public $count = 0;
    public function __construct(int $id, $value = null)
    {
        $this->id = $id;
        $this->value = $value;
    }
}
