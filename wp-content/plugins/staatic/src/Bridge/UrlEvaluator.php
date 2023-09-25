<?php

namespace Staatic\WordPress\Bridge;

use Staatic\Vendor\GuzzleHttp\Psr7\Uri;
use Staatic\Vendor\GuzzleHttp\Psr7\UriNormalizer;
use Staatic\Vendor\Psr\Http\Message\UriInterface;
use Staatic\Crawler\UrlEvaluator\InternalUrlEvaluator;
use Staatic\Crawler\UrlEvaluator\UrlEvaluatorInterface;

final class UrlEvaluator implements UrlEvaluatorInterface
{
    /**
     * @var UriInterface
     */
    private $baseUrl;

    private const WILDCARD_PLACEHOLDER = '___STAATIC_WILDCARD___';

    /**
     * @var UrlEvaluatorInterface
     */
    private $decoratedEvaluator;

    /**
     * @var mixed[]
     */
    private $simpleExcludeRules = [];

    /**
     * @var mixed[]
     */
    private $wildcardExcludeRules = [];

    /**
     * @var mixed[]
     */
    private $regexExcludeRules = [];

    public function __construct(UriInterface $baseUrl, array $excludeUrls = [])
    {
        $this->baseUrl = $baseUrl;
        $this->decoratedEvaluator = new InternalUrlEvaluator($baseUrl);
        $this->initializeExcludeRules($excludeUrls);
    }

    private function initializeExcludeRules(array $excludeUrls) : void
    {
        foreach ($excludeUrls as $excludeUrl) {
            if ($this->isRegexRule($excludeUrl)) {
                $this->regexExcludeRules[] = $this->regexRule($excludeUrl);
            } elseif (strpos($excludeUrl, '*') !== false) {
                $this->wildcardExcludeRules[] = $this->wildcardRule($excludeUrl);
            } else {
                $this->simpleExcludeRules[] = $this->simpleRule($excludeUrl);
            }
        }
    }

    private function isRegexRule(string $possiblePattern) : bool
    {
        if (!(strncmp($possiblePattern, '~', strlen('~')) === 0 && substr_compare(
            $possiblePattern,
            '~',
            -strlen('~')
        ) === 0)) {
            return \false;
        }
        if (\strlen($possiblePattern) <= 2) {
            return \false;
        }
        // Silence to ignore "compilation" failures.
        return @\preg_match($possiblePattern, '') !== \false;
    }

    private function wildcardRule(string $excludeUrl) : string
    {
        $excludeUrl = \str_replace('*', self::WILDCARD_PLACEHOLDER, $excludeUrl);
        $excludeUrl = (string) $this->normalizedPathRelativeReference(new Uri($excludeUrl));

        return \sprintf('~^%s$~i', \str_replace(self::WILDCARD_PLACEHOLDER, '.+?', \preg_quote($excludeUrl, '~')));
    }

    private function simpleRule(string $excludeUrl) : string
    {
        return (string) $this->normalizedPathRelativeReference(new Uri($excludeUrl));
    }

    private function regexRule(string $excludeUrl) : string
    {
        return $excludeUrl . 'i';
    }

    /**
     * @param UriInterface $resolvedUrl
     */
    public function shouldCrawl($resolvedUrl) : bool
    {
        $pathRelativeReference = (string) $this->normalizedPathRelativeReference($resolvedUrl);
        if ($this->matchesExcludeRule($pathRelativeReference)) {
            return \false;
        }
        $basePath = $this->baseUrl->getPath();
        $path = $resolvedUrl->getPath();
        if ($basePath && $basePath !== '/') {
            if (strncmp($path, $basePath, strlen($basePath)) !== 0) {
                return \false;
            }
            $path = \mb_substr($path, \mb_strlen(\rtrim($basePath, '/')));
        }
        if (strncmp($path, '/xmlrpc.php', strlen('/xmlrpc.php')) === 0) {
            return \false;
        }
        if (strncmp($path, '/wp-comments-post.php', strlen('/wp-comments-post.php')) === 0) {
            return \false;
        }
        if (strncmp($path, '/wp-login.php', strlen('/wp-login.php')) === 0) {
            return \false;
        }
        if (\rtrim($path, '/') === '/wp-admin') {
            return \false;
        }
        // Short links are not supported.
        if (\preg_match('~^/\\?p=\\d+~', $pathRelativeReference)) {
            return \false;
        }

        return $this->decoratedEvaluator->shouldCrawl($resolvedUrl);
    }

    private function matchesExcludeRule(string $pathRelativeReference) : bool
    {
        foreach ($this->simpleExcludeRules as $rule) {
            if (\strcasecmp($pathRelativeReference, $rule) === 0) {
                return \true;
            }
        }
        foreach ($this->wildcardExcludeRules as $rule) {
            if (\preg_match($rule, $pathRelativeReference) === 1) {
                return \true;
            }
        }
        foreach ($this->regexExcludeRules as $rule) {
            if (\preg_match($rule, $pathRelativeReference) === 1) {
                return \true;
            }
        }

        return \false;
    }

    /**
     * Converts URL into a normalized relative-path reference or relative reference with
     * an absolute path. In other words, removes the scheme, user-data and authority
     * segments while normalizing the path and query.
     */
    private function normalizedPathRelativeReference(UriInterface $url) : UriInterface
    {
        $normalizations = UriNormalizer::CAPITALIZE_PERCENT_ENCODING | UriNormalizer::DECODE_UNRESERVED_CHARACTERS | UriNormalizer::CONVERT_EMPTY_PATH | UriNormalizer::REMOVE_DOT_SEGMENTS | UriNormalizer::REMOVE_DUPLICATE_SLASHES | UriNormalizer::SORT_QUERY_PARAMETERS;
        $normalizedUrl = UriNormalizer::normalize($url, $normalizations);

        return (new Uri())->withPath($normalizedUrl->getPath())->withQuery($normalizedUrl->getQuery())->withFragment(
            $normalizedUrl->getFragment()
        );
    }
}
