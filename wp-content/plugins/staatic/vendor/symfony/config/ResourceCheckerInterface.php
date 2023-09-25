<?php

namespace Staatic\Vendor\Symfony\Component\Config;

use Staatic\Vendor\Symfony\Component\Config\Resource\ResourceInterface;
interface ResourceCheckerInterface
{
    /**
     * @param \Staatic\Vendor\Symfony\Component\Config\Resource\ResourceInterface $metadata
     */
    public function supports($metadata);
    /**
     * @param \Staatic\Vendor\Symfony\Component\Config\Resource\ResourceInterface $resource
     * @param int $timestamp
     */
    public function isFresh($resource, $timestamp);
}
