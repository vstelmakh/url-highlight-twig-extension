<?php

declare(strict_types=1);

namespace VStelmakh\UrlHighlightTwigExtension\Tests;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use PHPUnit\Framework\TestCase;
use VStelmakh\UrlHighlight\UrlHighlight;
use VStelmakh\UrlHighlightTwigExtension\UrlHighlightExtension;

class UrlHighlightExtensionTest extends TestCase
{
    /**
     * @var UrlHighlight
     */
    private $urlHighlight;

    /**
     * @var UrlHighlightExtension
     */
    private $urlHighlightExtension;

    public function setUp(): void
    {
        $this->urlHighlight = new UrlHighlight();
        $this->urlHighlightExtension = new UrlHighlightExtension($this->urlHighlight);
    }

    public function testGetFilters(): void
    {
        /** @var TwigFilter[] $filters */
        $filters = $this->urlHighlightExtension->getFilters();
        $urlsToHtmlFilter = $filters[0];

        $name = $urlsToHtmlFilter->getName();
        $this->assertSame('urls_to_html', $name);

        $callable = $urlsToHtmlFilter->getCallable();
        $this->assertInstanceOf(UrlHighlight::class, $callable[0]);
        $this->assertSame('highlightUrls', $callable[1]);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function testUrlsToHtml(): void
    {
        $twig = $this->createTwig($this->urlHighlightExtension);

        $text = '<h1>Test</h1><div>This is example: http://example.com.</div>';
        $expected = '<h1>Test</h1><div>This is example: <a href="http://example.com">http://example.com</a>.</div>';

        $template = $twig->createTemplate('{{ text|urls_to_html }}');
        $actual = $twig->render($template, ['text' => $text]);

        $this->assertSame($expected, $actual);
    }

    /**
     * @param UrlHighlightExtension $urlHighlightExtension
     * @return Environment
     */
    private function createTwig(UrlHighlightExtension $urlHighlightExtension): Environment
    {
        $loader = new FilesystemLoader('');
        $twig = new Environment($loader, []);
        $twig->addExtension($urlHighlightExtension);
        return $twig;
    }
}
