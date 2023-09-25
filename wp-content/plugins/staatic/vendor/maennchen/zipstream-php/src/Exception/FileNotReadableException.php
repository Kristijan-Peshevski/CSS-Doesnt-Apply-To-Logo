<?php

declare (strict_types=1);
namespace Staatic\Vendor\ZipStream\Exception;

use Staatic\Vendor\ZipStream\Exception;
class FileNotReadableException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct("The file with the path {$path} isn't readable.");
    }
}
