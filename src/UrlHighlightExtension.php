<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlightTwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use VStelmakh\UrlHighlight\UrlHighlight;

class UrlHighlightExtension extends AbstractExtension
{
    /** @var UrlHighlight */
    private $urlHighlight;

    public function __construct()
    {
        $this->urlHighlight = new UrlHighlight();
    }

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('urls_to_html', [$this->urlHighlight, 'highlightUrls'], [
                'is_safe' => ['html']
            ]),
        ];
    }
}
