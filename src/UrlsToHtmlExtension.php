<?php
declare(strict_types=1);

namespace VStelmakh\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UrlsToHtmlExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('urls_to_html', [$this, 'formatUrlsToHtml']),
        ];
    }

    public function formatUrlsToHtml(string $text): string
    {
        return preg_replace('/(https?:\/\/[\S]+\b\/?)/i', '<a href="$1">$1</a>', $text);
    }
}