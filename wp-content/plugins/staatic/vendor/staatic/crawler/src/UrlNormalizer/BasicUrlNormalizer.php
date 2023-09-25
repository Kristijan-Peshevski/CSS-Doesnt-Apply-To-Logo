<?php

namespace Staatic\Crawler\UrlNormalizer;

use Staatic\Vendor\GuzzleHttp\Psr7\UriNormalizer;
use Staatic\Vendor\Psr\Http\Message\UriInterface;
final class BasicUrlNormalizer implements UrlNormalizerInterface
{
    /**
     * @var bool
     */
    private $keepFragment = \false;
    /**
     * @var bool
     */
    private $keepQuery = \false;
    public function __construct(bool $keepFragment = \false, bool $keepQuery = \false)
    {
        $this->keepFragment = $keepFragment;
        $this->keepQuery = $keepQuery;
    }
    /**
     * @param UriInterface $url
     */
    public function normalize($url) : UriInterface
    {
        if (!$this->keepFragment && $url->getFragment()) {
            $url = $url->withFragment('');
        }
        if (!$this->keepQuery && $url->getQuery()) {
            $url = $url->withQuery('');
        }
        $normalizations = UriNormalizer::PRESERVING_NORMALIZATIONS | UriNormalizer::REMOVE_DUPLICATE_SLASHES | UriNormalizer::SORT_QUERY_PARAMETERS;
        return UriNormalizer::normalize($url, $normalizations);
    }
}
