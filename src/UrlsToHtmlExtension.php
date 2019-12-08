<?php
declare(strict_types=1);

namespace VStelmakh\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UrlsToHtmlExtension extends AbstractExtension
{
    private const DELIMITER = '/';

    public function getFilters(): array
    {
        return [
            new TwigFilter('urls_to_html', [$this, 'formatUrlsToHtml']),
        ];
    }

    public function formatUrlsToHtml(string $text, array $protocols = []): string
    {
        $protocolRegex = $this->getProtocolRegex($protocols);
        $urlRegex = self::DELIMITER . '(' . $protocolRegex . ':\/\/[\S]+\b\/?)' . self::DELIMITER . 'i';
        return preg_replace($urlRegex, '<a href="$1">$1</a>', $text);
    }

    private function getProtocolRegex(array $protocols = []): string
    {
        if (empty($protocols)) {
            return  '[a-z]+';
        }

        $escapedProtocols = array_map(function ($item) {
            return preg_quote($item, self::DELIMITER);
        }, $protocols);

        return '(?:' . implode('|', $escapedProtocols) . ')';
    }
}