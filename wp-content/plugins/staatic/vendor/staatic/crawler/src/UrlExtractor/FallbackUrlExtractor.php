<?php

namespace Staatic\Crawler\UrlExtractor;

final class FallbackUrlExtractor extends AbstractPatternUrlExtractor
{
    /**
     * @var string|null
     */
    private $filterBasePath;
    private const CHAR_SUB_DELIMS = '!\\$&\'\\(\\)\\*\\+,;=';
    private const CHAR_UNRESERVED = 'A-Za-z0-9\\-\\._\\~\\pL';
    private const CHAR_PCT_ENCODED = '%(?=[A-Fa-f0-9]{2})';
    public function __construct(?callable $filterCallback = null, ?callable $transformCallback = null, ?string $filterBasePath = null)
    {
        $this->filterBasePath = $filterBasePath;
        parent::__construct($filterCallback, $transformCallback);
    }
    /**
     * @param string|null $filterBasePath
     */
    public function setFilterBasePath($filterBasePath) : void
    {
        $this->filterBasePath = $filterBasePath;
    }
    protected function getPatterns() : array
    {
        $search = $this->baseUrl->getAuthority();
        if ($this->filterBasePath && $this->filterBasePath !== '/') {
            $search .= \rtrim($this->filterBasePath, '/') . '/';
        }
        return [['pattern' => \implode('', ['~(', '(?:https?:)?//' . \preg_quote($search, '~'), '(?:[' . self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . ':@/]|' . self::CHAR_PCT_ENCODED . ')*', '(?:\\?(?:[' . self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . ':@/?]|' . self::CHAR_PCT_ENCODED . '])*)?', '(?:#(?:[' . self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . ':@/?]|' . self::CHAR_PCT_ENCODED . '])*)?', ')~iu'])], ['pattern' => \implode('', ['~(', '(?:https?:)?\\\\/\\\\/' . \preg_quote($this->jsonEncode($search), '~'), '(?:[' . self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . ':@]|\\\\/|' . self::CHAR_PCT_ENCODED . ')*', '(?:\\?(?:[' . self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . ':@?]|\\\\/|' . self::CHAR_PCT_ENCODED . '])*)?', '(?:#(?:[' . self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . ':@?]|\\\\/|' . self::CHAR_PCT_ENCODED . '])*)?', ')~iu']), 'encode' => function (string $value) {
            return $this->jsonEncode($value);
        }, 'decode' => function (string $value) {
            return $this->jsonDecode($value);
        }], ['pattern' => \implode('', ['~(', '(?:https?:)?%2F%2F' . \preg_quote(\rawurlencode($search), '~'), '(?:[' . self::CHAR_UNRESERVED . ']|' . self::CHAR_PCT_ENCODED . ')*', ')~iu']), 'encode' => function (string $value) {
            return \rawurlencode($value);
        }, 'decode' => function (string $value) {
            return \rawurldecode($value);
        }]];
    }
    private function jsonEncode(string $string) : string
    {
        return \str_replace('/', '\\/', $string);
    }
    private function jsonDecode(string $string) : string
    {
        return \str_replace('\\/', '/', $string);
    }
}
