<?php

declare(strict_types=1);

namespace Staatic\WordPress\Factory;

use Staatic\Vendor\Psr\Http\Message\UriInterface;
use Staatic\Crawler\CrawlProfile\CrawlProfileInterface;
use Staatic\Crawler\UrlEvaluator\UrlEvaluatorInterface;
use Staatic\WordPress\Bridge\CrawlProfile;
use Staatic\WordPress\Bridge\UrlEvaluator;
use Staatic\WordPress\Setting\Build\ExcludeUrlsSetting;

final class CrawlProfileFactory
{
    /**
     * @var ExcludeUrlsSetting
     */
    private $excludeUrls;

    public function __construct(ExcludeUrlsSetting $excludeUrls)
    {
        $this->excludeUrls = $excludeUrls;
    }

    public function __invoke(UriInterface $baseUrl, UriInterface $destinationUrl) : CrawlProfileInterface
    {
        $crawlProfile = new CrawlProfile($baseUrl, $destinationUrl);
        $urlEvaluator = $this->createUrlEvaluator($baseUrl);

        return $crawlProfile->withUrlEvaluator($urlEvaluator);
    }

    private function createUrlEvaluator(UriInterface $baseUrl) : UrlEvaluatorInterface
    {
        $excludeUrls = ExcludeUrlsSetting::resolvedValue($this->excludeUrls->value());
        $excludeUrls = \apply_filters('staatic_exclude_urls', $excludeUrls, $baseUrl);

        return new UrlEvaluator($baseUrl, $excludeUrls);
    }
}
