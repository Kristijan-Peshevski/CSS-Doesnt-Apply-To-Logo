<?php

namespace Staatic\Crawler\UrlEvaluator;

use Staatic\Vendor\Psr\Http\Message\UriInterface;
interface UrlEvaluatorInterface
{
    /**
     * @param UriInterface $resolvedUrl
     */
    public function shouldCrawl($resolvedUrl) : bool;
}
