<?php

declare(strict_types=1);

namespace VStelmakh\TwigUrlHighlightExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UrlHighlightExtension extends AbstractExtension
{
    private const DELIMITER = '/';

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('urls_to_html', [$this, 'formatUrlsToHtml'], [
                'pre_escape' => 'html',
                'is_safe' => ['html']
            ]),
        ];
    }

    /**
     * @param string $text
     * @param array|string[] $protocols
     * @return string
     */
    public function formatUrlsToHtml(string $text, array $protocols = []): string
    {
        $protocolRegex = $this->getProtocolRegex($protocols);
        $urlRegex = self::DELIMITER . '(' . $protocolRegex . ':\/\/[\S]+\b\/?)' . self::DELIMITER . 'i';
        return preg_replace($urlRegex, '<a href="$1">$1</a>', $text);
    }

    /**
     * @param array|string[] $protocols
     * @return string
     */
    private function getProtocolRegex(array $protocols = []): string
    {
        if (empty($protocols)) {
            return '[a-z]+';
        }

        $escapedProtocols = array_map(function ($item) {
            return preg_quote($item, self::DELIMITER);
        }, $protocols);

        return '(?:' . implode('|', $escapedProtocols) . ')';
    }
}
