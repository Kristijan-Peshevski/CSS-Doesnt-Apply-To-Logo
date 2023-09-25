<?php

namespace Staatic\Crawler\CrawlProfile;

use Staatic\Vendor\Psr\Http\Message\UriInterface;
use Staatic\Crawler\UrlTransformer\UrlTransformation;
interface CrawlProfileInterface
{
    public function baseUrl() : UriInterface;
    public function destinationUrl() : UriInterface;
    /**
     * @param UriInterface $resolvedUrl
     */
    public function shouldCrawl($resolvedUrl) : bool;
    /**
     * @param UriInterface $resolvedUrl
     */
    public function normalizeUrl($resolvedUrl) : UriInterface;
    /**
     * @param UriInterface $url
     * @param UriInterface|null $foundOnUrl
     */
    public function transformUrl($url, $foundOnUrl = null) : UrlTransformation;
}
